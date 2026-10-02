<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\Signal;

final class ConversionAnalyzer
{
    /**
     * @return list<Signal>
     */
    public function analyze(CrawlResult $crawl): array
    {
        $pages = $crawl->htmlPages();
        if ($pages === []) {
            return [
                Signal::notApplicable('phone_number'),
                Signal::notApplicable('contact_form'),
                Signal::notApplicable('primary_cta'),
                Signal::review('cta_visibility'),
                Signal::notApplicable('whatsapp_or_contact_link'),
                Signal::notApplicable('service_or_contact_path'),
                Signal::notApplicable('form_friction'),
            ];
        }

        $phones = false;
        $form = false;
        $cta = false;
        $ctaEarly = false;
        $whatsapp = false;
        $path = false;
        $heavyForm = false;

        foreach ($pages as $page) {
            if ($page->phones !== []) {
                $phones = true;
            }
            if ($page->hasForm) {
                $form = true;
            }
            if ($page->formFieldCount > 10) {
                $heavyForm = true;
            }
            if ($page->ctas !== []) {
                $cta = true;
            }
            if ($page->ctas !== [] && preg_match('/مشاوره|تماس|ثبت|سفارش|خرید|درخواست|شروع|contact|call/iu', $page->textSample) === 1) {
                $ctaEarly = true;
            }
            if ($page->hasWhatsapp) {
                $whatsapp = true;
            }
            $urlPath = strtolower((string) parse_url($page->finalUrl, PHP_URL_PATH));
            if (preg_match('/contact|about|service|khadamat|tamas|ertebat/i', $urlPath) === 1) {
                $path = true;
            }
        }

        $signals = [
            $phones ? Signal::pass('phone_number') : Signal::fail('phone_number', 'medium'),
            $form ? Signal::pass('contact_form') : Signal::fail('contact_form', 'medium'),
            $cta ? Signal::pass('primary_cta') : Signal::fail('primary_cta', 'medium'),
            $whatsapp ? Signal::pass('whatsapp_or_contact_link') : Signal::fail('whatsapp_or_contact_link', 'low'),
            $path ? Signal::pass('service_or_contact_path') : Signal::fail('service_or_contact_path', 'low'),
        ];

        if (! $cta) {
            $signals[] = Signal::notApplicable('cta_visibility');
        } elseif ($ctaEarly) {
            $signals[] = Signal::pass('cta_visibility');
        } else {
            $signals[] = Signal::review('cta_visibility', null, 'دکمه اقدام دیده شد اما جایگاه آن در بالای صفحه قطعی نیست.');
        }

        if (! $form) {
            $signals[] = Signal::notApplicable('form_friction');
        } else {
            $signals[] = $heavyForm
                ? Signal::fail('form_friction', 'medium')
                : Signal::pass('form_friction');
        }

        return $signals;
    }
}
