<?php

declare(strict_types=1);

namespace App\Services\Audits;

final class RobotsParser
{
    /**
     * @param  list<array{agent: string, rules: list<array{allow: bool, path: string}>}>  $groups
     * @param  list<string>  $sitemaps
     */
    public function parse(string $body, bool $found): RobotsRules
    {
        $groups = [];
        $sitemaps = [];
        $currentAgents = [];
        $currentRules = [];

        $flush = function () use (&$groups, &$currentAgents, &$currentRules): void {
            if ($currentAgents === []) {
                $currentRules = [];

                return;
            }
            foreach ($currentAgents as $agent) {
                $groups[] = ['agent' => $agent, 'rules' => $currentRules];
            }
            $currentAgents = [];
            $currentRules = [];
        };

        foreach (preg_split('/\R/', $body) ?: [] as $line) {
            $line = trim(preg_replace('/#.*$/', '', $line) ?? '');
            if ($line === '' || ! str_contains($line, ':')) {
                continue;
            }
            [$field, $value] = array_map('trim', explode(':', $line, 2));
            $field = strtolower($field);
            if ($field === 'user-agent') {
                if ($currentRules !== []) {
                    $flush();
                }
                $currentAgents[] = strtolower($value);
            } elseif ($field === 'allow' && $value !== '') {
                $currentRules[] = ['allow' => true, 'path' => $value];
            } elseif ($field === 'disallow' && $value !== '') {
                $currentRules[] = ['allow' => false, 'path' => $value];
            } elseif ($field === 'sitemap' && $value !== '') {
                $sitemaps[] = $value;
            }
        }
        $flush();

        return new RobotsRules($groups, array_values(array_unique($sitemaps)), $found);
    }
}
