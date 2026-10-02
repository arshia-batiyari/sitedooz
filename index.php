<?php
require_once __DIR__ . '/app/functions.php';

$settings = get_settings() ?? [];
$posts = array_slice(published_items(get_posts() ?? []), 0, 3);
$portfolio = array_values(array_filter(get_portfolio() ?? [], fn($item) => ($item['status'] ?? 'published') === 'published'));
$seoItems = array_values(array_filter(get_seo_items() ?? [], fn($item) => ($item['status'] ?? 'published') === 'published'));
render_public_head('سایت دوز - طراحی سایت در گرگان', 'طراحی سایت در گرگان با کیفیت بالا و قیمت مناسب و سئو با نتایج واقعی و قابل اندازه‌گیری. سایت دوز، متخصص طراحی و سئو کسب‌وکارهای گرگانی. مشاوره رایگان.', site_url(), 'images/logo.png', false, ['css/home.css']);
include __DIR__ . '/partials/header.php';
?>

<main>
    <section class="hero-bg text-white pt-24 pb-16 lg:pt-32 lg:pb-20" id="audit">
        <div class="hero-grid-pattern"></div>
        <div class="hero-orb hero-orb--1"></div>
        <div class="hero-orb hero-orb--2"></div>
        <div class="hero-bg-mobile-glow"></div>
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-10 items-center relative z-10">
            <div>
                <p class="text-emerald-200 font-extrabold mb-3">وب‌سایت، سئو و سیستم رشد</p>
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-5">سایتت واقعاً برای کسب‌وکارت کار می‌کند؟</h1>
                <p class="text-white/85 mb-6 leading-9 text-lg max-w-xl">سرعت، سئو، تجربه کاربری و فرصت‌های رشد سایتت را بررسی کن.</p>
                <?php $formId = 'audit-form'; $buttonLabel = 'تحلیل رایگان'; include __DIR__ . '/partials/audit-form.php'; ?>
                <a href="#services" class="inline-flex mt-5 text-white font-extrabold border-b border-white/40 pb-1">مشاهده خدمات Sitedooz</a>
            </div>
            <div class="mt-2 lg:mt-0">
                <?php include __DIR__ . '/partials/insight-preview.php'; ?>
            </div>
        </div>
    </section>

    <section id="insight" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-start">
            <div>
                <p class="section-kicker">Sitedooz Insight</p>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">قبل از اینکه سایتت را عوض کنی، بفهم مشکلش کجاست.</h2>
                <p class="text-slate-600 leading-9">ممکن است همین حالا سایت داشته باشی و باز هم مشتری از دست بدهی. Insight همان سایت را بررسی می‌کند تا معلوم شود سرعت، سئو، موبایل یا مسیر تماس کجا ضعیف است.</p>
                <a href="#audit" class="nav-audit mt-6">سایت من را بررسی کن</a>
            </div>
            <ul class="problem-list text-slate-700">
                <li>سرعت کم</li>
                <li>سئوی ضعیف</li>
                <li>تجربه نامناسب در موبایل</li>
                <li>مسیر نامشخص برای تماس یا خرید</li>
                <li>مشکل فنی</li>
                <li>فرصت محتوای از دست‌رفته</li>
            </ul>
        </div>
    </section>

    <section id="growth" class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4">
            <p class="section-kicker">سیستم رشد سایت‌دوز</p>
            <h2 class="text-3xl md:text-4xl font-black mt-3 mb-3">ما فقط سایت نمی‌سازیم.</h2>
            <p class="text-2xl font-black text-slate-800">ما سیستم رشد دیجیتال کسب‌وکار شما را می‌سازیم.</p>
            <p class="text-slate-600 leading-9 max-w-3xl mt-4">طراحی، سئو و بهینه‌سازی جدا از هم فروخته نمی‌شوند. هر کدام وقتی معنی دارد که به اندازه‌گیری و رشد بعدی وصل باشد.</p>
            <ol class="growth-flow" aria-label="مسیر رشد">
                <li>استراتژی</li>
                <li>وب‌سایت</li>
                <li>سئو</li>
                <li>تحلیل</li>
                <li>بهینه‌سازی</li>
                <li>رشد</li>
            </ol>
        </div>
    </section>

    <section id="services" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="max-w-2xl mb-12">
                <p class="section-kicker">خدمات</p>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">خدمات، حول نتیجه کسب‌وکار</h2>
                <p class="text-slate-600 leading-8">هر خدمت یک تکه جدا نیست. بسته به وضعیت فعلی سایت، از ساخت، فروش، دیده شدن یا اصلاح همان سایت شروع می‌کنیم.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $services = [
                    ['توسعه وب‌سایت', 'سایتی که خدمات را روشن بگوید و مسیر تماس داشته باشد.', 'tarahi-site-sherkati-gorgan'],
                    ['فروشگاه اینترنتی', 'مسیر دیدن محصول تا خرید، بدون پیچیدگی اضافه.', 'tarahi-site-foroushgahi-gorgan'],
                    ['سئو', 'دیده شدن در جستجوهایی که مشتری واقعی انجام می‌دهد.', 'seo-sherkati-gorgan'],
                    ['بهینه‌سازی سایت موجود', 'اگر سایت هست، اول همان را اندازه می‌گیریم و اصلاح می‌کنیم.', '#audit'],
                    ['صفحه فرود', 'یک صفحه مشخص برای یک پیشنهاد، روی همان ساختار سایت.', 'tarahi-site-sherkati-gorgan'],
                    ['رشد دیجیتال', 'ساخت یا اصلاح، به‌همراه اندازه‌گیری و ادامه بهبود.', 'about-us'],
                ];
                foreach ($services as $service): ?>
                <article class="soft-card rounded-3xl p-6 border border-slate-200/80">
                    <h3 class="font-black text-xl mb-3"><?= e($service[0]) ?></h3>
                    <p class="text-slate-600 leading-8 mb-5"><?= e($service[1]) ?></p>
                    <a href="<?= str_starts_with($service[2], '#') ? e($service[2]) : e(site_url($service[2])) ?>" class="text-emerald-700 font-extrabold" data-track="service_opened">بیشتر بدانید</a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="portfolio" class="py-20 bg-slate-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-12">
                <p class="section-kicker">نمونه‌کار</p>
                <h2 class="text-3xl md:text-4xl font-black mt-3">نمونه‌هایی از جنس پروژه</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach (array_slice($portfolio, 0, 6) as $item): ?>
                <article class="case-card">
                    <img src="<?= asset_url($item['image'] ?? 'images/portfolio-corporate.svg') ?>" alt="<?= e($item['title'] ?? '') ?>" width="640" height="420" loading="lazy" decoding="async">
                    <div>
                        <p class="text-emerald-700 text-sm font-extrabold">نوع پروژه</p>
                        <h3 class="font-black text-xl"><?= e($item['title'] ?? '') ?></h3>
                        <p class="text-slate-600 leading-8"><?= e($item['subtitle'] ?? '') ?></p>
                        <?php if (!empty($item['url']) && $item['url'] !== '#'): ?>
                        <a href="<?= e($item['url']) ?>" class="inline-flex mt-3 font-extrabold text-slate-900" data-track="case_study_opened">مشاهده پروژه</a>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/partials/home-seo-results.php'; ?>

    <section id="existing-site" class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4">
            <p class="section-kicker">سایت فعلی</p>
            <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">سایت داری؟ شاید نیازی به طراحی مجدد نداشته باشی.</h2>
            <p class="text-slate-600 leading-9 mb-6">ابتدا سایت فعلی را بررسی می‌کنیم. اگر قابل بهبود باشد، بهینه‌سازی می‌کنیم؛ اگر واقعاً نیاز به بازطراحی داشته باشد، مسیر مناسب را پیشنهاد می‌دهیم.</p>
            <a href="#audit" class="nav-audit">دریافت گزارش سایت</a>
        </div>
    </section>

    <section id="process" class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4">
            <p class="section-kicker">فرایند</p>
            <h2 class="text-3xl md:text-4xl font-black mt-3 mb-8">از فهم کسب‌وکار تا رشد</h2>
            <ol class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ([
                    ['۰۱', 'تحلیل کسب‌وکار', 'مخاطب، پیشنهاد و وضعیت سایت فعلی را مشخص می‌کنیم.'],
                    ['۰۲', 'استراتژی', 'تصمیم می‌گیریم ساخت جدید لازم است یا بهینه‌سازی همان سایت.'],
                    ['۰۳', 'ساخت / بهینه‌سازی', 'صفحه، ساختار و مشکلات قابل اصلاح را پیش می‌بریم.'],
                    ['۰۴', 'راه‌اندازی', 'سایت یا نسخه اصلاح‌شده را منتشر می‌کنیم.'],
                    ['۰۵', 'اندازه‌گیری', 'سرعت، دیده شدن و مسیر تماس را بعد از انتشار نگاه می‌کنیم.'],
                    ['۰۶', 'رشد', 'بر اساس همان داده‌ها، قدم بعدی را انتخاب می‌کنیم.'],
                ] as $step): ?>
                <li class="soft-card rounded-3xl p-6 bg-white">
                    <span class="text-emerald-700 font-black"><?= e($step[0]) ?></span>
                    <h3 class="font-black text-xl mt-2 mb-2"><?= e($step[1]) ?></h3>
                    <p class="text-slate-600 leading-8"><?= e($step[2]) ?></p>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>

    <?php include __DIR__ . '/partials/home-industries.php'; ?>
    <?php include __DIR__ . '/partials/home-brands.php'; ?>
    <?php include __DIR__ . '/partials/home-seo-article.php'; ?>
    <?php include __DIR__ . '/partials/home-faq.php'; ?>
    <?php include __DIR__ . '/partials/home-blog.php'; ?>

    <section class="hero-bg text-white py-16" id="audit-final">
        <div class="max-w-3xl mx-auto px-4 relative z-10">
            <h2 class="text-3xl md:text-4xl font-black mb-4">اول سایت را ببین، بعد تصمیم بگیر.</h2>
            <p class="text-white/80 leading-8 mb-6">نشانی را وارد کن. تحلیل اولیه رایگان است و به ثبت‌نام نیاز ندارد.</p>
            <?php $formId = 'audit-final-form'; $buttonLabel = 'تحلیل رایگان'; include __DIR__ . '/partials/audit-form.php'; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
