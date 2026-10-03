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
    <section class="hero-bg text-white pt-24 pb-28 lg:pt-36 lg:pb-36" id="audit">
        <div class="hero-grid-pattern"></div>
        <div class="hero-orb hero-orb--1"></div>
        <div class="hero-orb hero-orb--2"></div>
        <div class="hero-bg-mobile-glow"></div>
        <div class="scroll-cue"><span>بیشتر ببین</span><span class="scroll-cue-icon"></span></div>
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center relative z-10">
            <div>
                <span class="hero-status-badge"><span class="hero-status-dot"></span> نمونه تحلیل زنده</span>
                <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6 mt-4">سایتت واقعاً برای کسب‌وکارت کار می‌کند؟</h1>
                <p class="text-white/85 mb-8 leading-9 text-lg max-w-xl">عملکرد، سئو، سرعت و فرصت‌های رشد سایتت را بررسی کن.</p>
                <?php $formId = 'audit-form'; $buttonLabel = 'تحلیل رایگان'; include __DIR__ . '/partials/audit-form.php'; ?>
                <a href="#services" class="btn-outline border border-white/40 px-6 py-3 rounded-2xl inline-flex items-center gap-2 mt-5">مشاهده خدمات <?= svg_icon('arrow', 'w-5 h-5') ?></a>
            </div>
            <div class="relative overflow-visible mt-8 lg:mt-0">
                <div class="absolute z-20 -bottom-5 -right-2 md:-bottom-6 md:-right-4 bg-white/95 text-slate-900 rounded-2xl px-4 py-3 shadow-xl pulse-glow hidden md:block">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"><?= svg_icon('gauge', 'w-4 h-4') ?></span>
                        <div>
                            <div class="text-xs text-slate-500 mb-1">نمونه</div>
                            <div class="text-sm font-extrabold whitespace-nowrap">امتیاز <?= fa_number(72) ?> از <?= fa_number(100) ?></div>
                        </div>
                    </div>
                </div>
                <div class="hero-visual-frame" data-tilt>
                    <div class="hero-visual-chrome">
                        <span></span><span></span><span></span>
                        <div class="hero-visual-url">example.com</div>
                    </div>
                    <?php include __DIR__ . '/partials/insight-preview.php'; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="stat-bar-wrap relative z-20 -mt-10 lg:-mt-14">
        <div class="max-w-6xl mx-auto px-4">
            <div class="stat-bar rounded-[2rem] grid grid-cols-2 lg:grid-cols-4">
                <div class="stat-cell">
                    <span class="stat-icon"><?= svg_icon('laptop', 'w-6 h-6') ?></span>
                    <div>
                        <strong class="stat-num"><span data-count-to="7">۰</span>-<span data-count-to="14">۰</span></strong>
                        <span class="stat-label">روز تحویل سایت شرکتی</span>
                    </div>
                </div>
                <div class="stat-cell">
                    <span class="stat-icon"><?= svg_icon('chart', 'w-6 h-6') ?></span>
                    <div>
                        <strong class="stat-num"><span data-count-to="3">۰</span>-<span data-count-to="6">۰</span></strong>
                        <span class="stat-label">ماه تا نتیجه اولیه سئو</span>
                    </div>
                </div>
                <div class="stat-cell">
                    <span class="stat-icon"><?= svg_icon('check', 'w-6 h-6') ?></span>
                    <div>
                        <strong class="stat-num"><span data-count-to="100" data-count-suffix="%">۰</span></strong>
                        <span class="stat-label">پنل مدیریت اختصاصی</span>
                    </div>
                </div>
                <div class="stat-cell">
                    <span class="stat-icon"><?= svg_icon('phone', 'w-6 h-6') ?></span>
                    <div>
                        <strong class="stat-num">۲۴/۷</strong>
                        <span class="stat-label">پشتیبانی پس از تحویل</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="insight" class="pt-20 pb-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-emerald-600 font-extrabold">Sitedooz Insight</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">قبل از اینکه سایتت را عوض کنی، بفهم مشکلش کجاست.</h2>
                <p class="text-slate-600 leading-9">ممکن است همین حالا سایت داشته باشی و باز هم مشتری از دست بدهی. Insight همان سایت را بررسی می‌کند تا معلوم شود سرعت، سئو، موبایل یا مسیر تماس کجا ضعیف است.</p>
                <a href="#audit" class="btn-main bg-slate-900 text-white px-5 py-3 rounded-2xl inline-flex items-center gap-2 mt-6">سایت من را بررسی کن <?= svg_icon('arrow', 'w-4 h-4') ?></a>
            </div>
            <ul class="insight-board">
                <li><span><?= svg_icon('speed', 'w-4 h-4') ?></span>سرعت کم</li>
                <li><span><?= svg_icon('search', 'w-4 h-4') ?></span>سئوی ضعیف</li>
                <li><span><?= svg_icon('laptop', 'w-4 h-4') ?></span>تجربه نامناسب در موبایل</li>
                <li><span><?= svg_icon('phone', 'w-4 h-4') ?></span>مسیر نامشخص برای تماس یا خرید</li>
                <li><span><?= svg_icon('gear', 'w-4 h-4') ?></span>مشکل فنی</li>
                <li><span><?= svg_icon('newspaper', 'w-4 h-4') ?></span>فرصت محتوای از دست‌رفته</li>
            </ul>
        </div>
    </section>

    <section id="services" class="pt-8 pb-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-emerald-600 font-extrabold">چه می‌سازیم</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">خدمات سایت دوز</h2>
                <p class="text-slate-500 leading-8">از معرفی کسب‌وکار تا فروش آنلاین و رشد در گوگل؛ هر سه با یک تیم.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="premium-service-card card">
                    <div class="premium-service-glow premium-service-glow--emerald"></div>
                    <div class="premium-service-body">
                        <div class="premium-service-icon premium-service-icon--emerald mb-7"><?= svg_icon('laptop', 'w-8 h-8') ?></div>
                        <h3 class="font-black text-xl mb-3">طراحی سایت شرکتی</h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">صفحه‌ای حرفه‌ای برای معرفی خدمات، اعتمادسازی، نمایش نمونه‌کار و دریافت مشاوره.</p>
                        <ul class="space-y-3 text-sm text-slate-600 mb-6">
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> ساختار مناسب خدمات</li>
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> سرعت بالا و ظاهر مدرن</li>
                        </ul>
                        <a href="<?= site_url('tarahi-site-sherkati-gorgan') ?>" class="premium-service-link premium-service-link--emerald" data-track="service_opened">بیشتر بدانید <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                    </div>
                </div>
                <div class="premium-service-card card">
                    <div class="premium-service-glow premium-service-glow--sky"></div>
                    <div class="premium-service-body">
                        <div class="premium-service-icon premium-service-icon--sky mb-7"><?= svg_icon('cart', 'w-8 h-8') ?></div>
                        <h3 class="font-black text-xl mb-3">فروشگاه اینترنتی</h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">طراحی فروشگاه با مسیر خرید ساده، صفحات محصول خوانا و قابلیت توسعه برای آینده.</p>
                        <ul class="space-y-3 text-sm text-slate-600 mb-6">
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> چیدمان محصول‌محور</li>
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> ساختار قابل توسعه</li>
                        </ul>
                        <a href="<?= site_url('tarahi-site-foroushgahi-gorgan') ?>" class="premium-service-link premium-service-link--sky" data-track="service_opened">بیشتر بدانید <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                    </div>
                </div>
                <div class="premium-service-card card">
                    <div class="premium-service-glow premium-service-glow--teal"></div>
                    <div class="premium-service-body">
                        <div class="premium-service-icon premium-service-icon--teal mb-7"><?= svg_icon('chart', 'w-8 h-8') ?></div>
                        <h3 class="font-black text-xl mb-3">بهینه‌سازی سایت</h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">مرتب‌سازی محتوا، بهبود سرعت، لینک‌سازی داخلی و آماده‌سازی صفحات برای رشد طبیعی.</p>
                        <ul class="space-y-3 text-sm text-slate-600 mb-6">
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> بلاگ و صفحات هدفمند</li>
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> مدیریت محتوا از پنل</li>
                        </ul>
                        <a href="<?= site_url('seo-sherkati-gorgan') ?>" class="premium-service-link premium-service-link--teal" data-track="service_opened">بیشتر بدانید <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="portfolio" class="py-20 bg-slate-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">
                <div>
                    <span class="text-emerald-600 font-extrabold">نمونه کارها</span>
                    <h2 class="text-3xl md:text-4xl font-black mt-2">نمونه‌های سایت دوز</h2>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach (array_slice($portfolio, 0, 6) as $i => $item): ?>
                <article class="portfolio-card group bg-slate-900 rounded-3xl overflow-hidden shadow-sm card">
                    <div class="relative overflow-hidden aspect-[4/3]">
                        <img src="<?= asset_url($item['image'] ?? 'images/portfolio-corporate.svg') ?>" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" alt="<?= e($item['title'] ?? '') ?>" width="640" height="420" loading="lazy" decoding="async" />
                        <div class="portfolio-card-overlay absolute inset-0"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                            <span class="text-xs font-black text-emerald-300 mb-2 inline-block"><?= fa_number(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                            <h3 class="font-black text-xl mb-2"><?= e($item['title'] ?? '') ?></h3>
                            <p class="text-sm text-white/75 leading-7 portfolio-card-sub"><?= e($item['subtitle'] ?? '') ?></p>
                            <?php if (!empty($item['url']) && $item['url'] !== '#'): ?>
                            <a href="<?= e($item['url']) ?>" class="inline-flex items-center gap-2 mt-4 text-emerald-300 font-black text-sm portfolio-card-link" data-track="case_study_opened">مشاهده پروژه <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/partials/home-seo-results.php'; ?>

    <section id="existing-site" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="soft-card rounded-[2rem] p-8 md:p-12 grid lg:grid-cols-2 gap-8 items-center bg-white">
                <div>
                    <span class="text-emerald-600 font-extrabold">سایت فعلی</span>
                    <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">سایت داری؟ شاید نیازی به طراحی مجدد نداشته باشی.</h2>
                    <p class="text-slate-600 leading-9">ابتدا سایت فعلی را بررسی می‌کنیم. اگر قابل بهبود باشد، بهینه‌سازی می‌کنیم؛ اگر واقعاً نیاز به بازطراحی داشته باشد، مسیر مناسب را پیشنهاد می‌دهیم.</p>
                    <a href="#audit" class="btn-main bg-slate-900 text-white px-5 py-3 rounded-2xl inline-flex items-center gap-2 mt-6">دریافت گزارش سایت <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                </div>
                <ul class="insight-board">
                    <li><span><?= svg_icon('gauge', 'w-4 h-4') ?></span>اول همان نشانی را اندازه می‌گیریم</li>
                    <li><span><?= svg_icon('check', 'w-4 h-4') ?></span>اگر قابل بهبود باشد، بهینه‌سازی می‌کنیم</li>
                    <li><span><?= svg_icon('sparkles', 'w-4 h-4') ?></span>اگر ساختار جواب ندهد، بازطراحی پیشنهاد می‌شود</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="process" class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-emerald-600 font-extrabold">فرآیند اجرای پروژه</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-6">از تحلیل صفحه تا مدیریت و رشد</h2>
                <div class="space-y-5 relative process-timeline">
                    <?php foreach ([
                        ['تحلیل سایت', 'Insight سرعت، سئو، موبایل و مسیر تماس را روی همان نشانی اندازه می‌گیرد.'],
                        ['طراحی یا اصلاح', 'اگر ساخت جدید لازم باشد طراحی می‌کنیم؛ اگر نه، همان سایت را مرتب می‌کنیم.'],
                        ['سئو و ادامه رشد', 'محتوا، ساختار و اندازه‌گیری بعدی تا نتیجه قابل پیگیری باشد.'],
                    ] as $i => $step): ?>
                    <div class="soft-card rounded-3xl p-5 flex gap-4 hover-lift relative z-10 bg-white">
                        <div class="process-step-num w-12 h-12 rounded-2xl text-white flex items-center justify-center font-black flex-shrink-0"><?= fa_number($i + 1) ?></div>
                        <div>
                            <h3 class="font-black mb-2"><?= e($step[0]) ?></h3>
                            <p class="text-slate-600 leading-8 text-sm"><?= e($step[1]) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <img src="<?= asset_url('images/process-illustration.svg') ?>" width="700" height="520" class="shadow-xl w-full rounded-[2rem]" alt="فرآیند اجرای پروژه در سایت دوز: از تحلیل تا طراحی و مدیریت و فروش" loading="lazy">
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/partials/home-industries.php'; ?>
    <?php include __DIR__ . '/partials/home-brands.php'; ?>
    <?php include __DIR__ . '/partials/home-seo-article.php'; ?>
    <?php include __DIR__ . '/partials/home-faq.php'; ?>
    <?php include __DIR__ . '/partials/home-blog.php'; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
