<?php

declare(strict_types=1);

namespace App\Services\Audits;

final class RobotsRules
{
    /**
     * @param  list<array{agent: string, rules: list<array{allow: bool, path: string}>}>  $groups
     * @param  list<string>  $sitemaps
     */
    public function __construct(
        public readonly array $groups,
        public readonly array $sitemaps,
        public readonly bool $found,
        public readonly bool $checked = true,
    ) {}

    public static function allowAll(bool $checked = false): self
    {
        return new self([], [], false, $checked);
    }

    public function isAllowed(string $url, string $userAgent): bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = is_string($path) && $path !== '' ? $path : '/';
        $query = parse_url($url, PHP_URL_QUERY);
        $target = $path.(is_string($query) && $query !== '' ? '?'.$query : '');

        $group = $this->matchingGroup($userAgent);
        if ($group === null) {
            return true;
        }

        $best = null;
        $bestLength = -1;
        foreach ($group['rules'] as $rule) {
            if (! $this->matches($rule['path'], $target)) {
                continue;
            }
            $length = strlen($rule['path']);
            if ($length > $bestLength) {
                $best = $rule;
                $bestLength = $length;
            }
        }

        return $best === null || $best['allow'];
    }

    /**
     * @return array{agent: string, rules: list<array{allow: bool, path: string}>}|null
     */
    private function matchingGroup(string $userAgent): ?array
    {
        $agent = strtolower($userAgent);
        $best = null;
        $bestLength = -1;
        $wildcard = null;

        foreach ($this->groups as $group) {
            if ($group['agent'] === '*') {
                $wildcard = $group;

                continue;
            }
            if ($group['agent'] !== '' && str_contains($agent, $group['agent']) && strlen($group['agent']) > $bestLength) {
                $best = $group;
                $bestLength = strlen($group['agent']);
            }
        }

        return $best ?? $wildcard;
    }

    private function matches(string $rule, string $path): bool
    {
        $quoted = preg_quote($rule, '#');
        $pattern = str_replace(['\*', '\$'], ['.*', '$'], $quoted);
        if (! str_ends_with($rule, '$')) {
            $pattern .= '.*';
        }

        return preg_match('#^'.$pattern.'#', $path) === 1;
    }
}
