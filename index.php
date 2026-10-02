<?php
require_once __DIR__ . '/app/functions.php';

$settings = get_settings() ?? [];
$posts = array_slice(published_items(get_posts() ?? []), 0, 3);
$portfolio = array_values(array_filter(get_portfolio() ?? [], fn($item) => ($item['status'] ?? 'published') === 'published'));
$seoItems = array_values(array_filter(get_seo_items() ?? [], fn($item) => ($item['status'] ?? 'published') === 'published'));
render_public_head('سایت دوز - طراحی سایت در گرگان', 'طراحی سایت در گرگان با کیفیت بالا و قیمت مناسب و سئو با نتایج واقعی و قابل اندازه‌گیری. سایت دوز، متخصص طراحی و سئو کسب‌وکارهای گرگانی. مشاوره رایگان.', site_url(), 'images/logo.png', true);
include __DIR__ . '/partials/header.php';
?>

<main>
    <section class="hero-bg text-white pt-24 pb-16 lg:pt-36 lg:pb-28">
        <div class="hero-grid-pattern"></div>
        <div class="hero-orb hero-orb--1"></div>
        <div class="hero-orb hero-orb--2"></div>
        <div class="hero-bg-mobile-glow"></div>
        <div class="scroll-cue"><span>بیشتر ببین</span><span class="scroll-cue-icon"></span></div>
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center relative z-10">
            <div>
                <span class="hero-status-badge"><span class="hero-status-dot"></span> در حال پذیرش پروژه‌های جدید</span>
                <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6 mt-4"><?= hero_title_highlighted($settings['hero_title'] ?? '') ?>
                </h1>
                <p class="text-white/85 mb-8 leading-9 text-lg max-w-xl"><?= e($settings['hero_description'] ?? '') ?>
                </p>
                <div class="flex flex-wrap gap-4 mb-2">
                    <a href="#portfolio"
                        class="btn-main bg-white text-slate-950 px-6 py-3 rounded-2xl font-extrabold inline-flex items-center gap-2">
                        نمونه کارها <?= svg_icon('arrow', 'w-5 h-5') ?>
                    </a>
                    <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>#consultation-form"
                        class="btn-outline border border-white/40 px-6 py-3 rounded-2xl inline-flex items-center gap-2">
                        <?= svg_icon('phone', 'w-5 h-5') ?> درخواست مشاوره
                    </a>
                </div>
            </div>
            <div class="relative overflow-visible mt-8 lg:mt-0">
                <div
                    class="absolute z-20 -bottom-6 -right-3 md:-bottom-6 md:-right-4 bg-white/95 text-slate-900 rounded-2xl px-4 py-3 shadow-xl pulse-glow hidden md:block">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex h-6 w-6 md:h-8 md:w-8 items-center justify-center rounded-lg md:rounded-xl bg-emerald-100 text-emerald-600">
                            <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5 md:w-4 md:h-4" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19h16" />
                                <path d="M6 15l4-4 3 3 5-6" />
                                <path d="M18 8v4h-4" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-[10px] md:text-xs text-slate-500 mb-0.5 md:mb-1">سئو و رشد</div>
                            <div class="text-xs md:text-sm font-extrabold whitespace-nowrap">رتبه بهتر، فروش بیشتر</div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual-frame" data-tilt>
                    <div class="hero-visual-chrome">
                        <span></span><span></span><span></span>
                        <div class="hero-visual-url">sitedooz.ir</div>
                    </div>
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?= asset_url('images/hero-illustration-mobile.svg') ?>">
                        <img src="<?= asset_url('images/hero-illustration.svg') ?>"
                        fetchpriority="high"
                        decoding="async"
                        width="900" height="600"
                        class="relative z-10 w-full soft-float" alt="نمایش خدمات سایت دوز: طراحی سایت، مدیریت آسان و رشد فروش"
                        loading="eager" />
                    </picture>
                </div>
            </div>

        </div>
    </section>

    <section class="stat-bar-wrap relative z-20 -mt-2 lg:-mt-4">
        <div class="max-w-6xl mx-auto px-4">
            <div class="stat-bar rounded-[2rem] grid grid-cols-2 lg:grid-cols-4">
                <div class="stat-cell">
                    <span class="stat-icon"><?= svg_icon('laptop', 'w-6 h-6') ?></span>
                    <div>
                        <strong class="stat-num"><span data-count-to="7" data-count-suffix="">۰</span>-<span data-count-to="14" data-count-suffix="">۰</span></strong>
                        <span class="stat-label">روز تحویل سایت شرکتی</span>
                    </div>
                </div>
                <div class="stat-cell">
                    <span class="stat-icon"><?= svg_icon('chart', 'w-6 h-6') ?></span>
                    <div>
                        <strong class="stat-num"><span data-count-to="3" data-count-suffix="">۰</span>-<span data-count-to="6" data-count-suffix="">۰</span></strong>
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

    <section id="services" class="pt-24 lg:pt-28 pb-20 bg-slate-50">
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
                        <div class="premium-service-icon premium-service-icon--emerald mb-7">
                            <?= svg_icon('laptop', 'w-8 h-8') ?>
                        </div>
                        <h3 class="font-black text-xl mb-3">طراحی سایت شرکتی</h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">صفحه‌ای حرفه‌ای برای معرفی خدمات، اعتمادسازی، نمایش
                            نمونه‌کار و دریافت مشاوره.</p>
                        <ul class="space-y-3 text-sm text-slate-600 mb-6">
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> ساختار
                                مناسب خدمات</li>
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> سرعت
                                بالا و ظاهر مدرن</li>
                        </ul>
                        <a href="<?= site_url('tarahi-site-sherkati-gorgan') ?>" class="premium-service-link premium-service-link--emerald">
                            بیشتر بدانید <?= svg_icon('arrow', 'w-4 h-4') ?>
                        </a>
                    </div>
                </div>
                <div class="premium-service-card card">
                    <div class="premium-service-glow premium-service-glow--sky"></div>
                    <div class="premium-service-body">
                        <div class="premium-service-icon premium-service-icon--sky mb-7">
                            <?= svg_icon('cart', 'w-8 h-8') ?>
                        </div>
                        <h3 class="font-black text-xl mb-3">فروشگاه اینترنتی</h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">طراحی فروشگاه با مسیر خرید ساده، صفحات محصول خوانا و
                            قابلیت توسعه برای آینده.</p>
                        <ul class="space-y-3 text-sm text-slate-600 mb-6">
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> چیدمان
                                محصول‌محور</li>
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> ساختار
                                قابل توسعه</li>
                        </ul>
                        <a href="<?= site_url('tarahi-site-foroushgahi-gorgan') ?>" class="premium-service-link premium-service-link--sky">
                            بیشتر بدانید <?= svg_icon('arrow', 'w-4 h-4') ?>
                        </a>
                    </div>
                </div>
                <div class="premium-service-card card">
                    <div class="premium-service-glow premium-service-glow--teal"></div>
                    <div class="premium-service-body">
                        <div class="premium-service-icon premium-service-icon--teal mb-7">
                            <?= svg_icon('chart', 'w-8 h-8') ?>
                        </div>
                        <h3 class="font-black text-xl mb-3">بهینه‌سازی سایت</h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">مرتب‌سازی محتوا، بهبود سرعت، لینک‌سازی داخلی و
                            آماده‌سازی صفحات برای رشد طبیعی.</p>
                        <ul class="space-y-3 text-sm text-slate-600 mb-6">
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> بلاگ و
                                صفحات هدفمند</li>
                            <li class="flex gap-2 items-center"><?= svg_icon('check', 'w-5 h-5 text-emerald-600') ?> مدیریت
                                محتوا از پنل</li>
                        </ul>
                        <a href="<?= site_url('seo-sherkati-gorgan') ?>" class="premium-service-link premium-service-link--teal">
                            بیشتر بدانید <?= svg_icon('arrow', 'w-4 h-4') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-emerald-600 font-extrabold">فرآیند اجرای پروژه</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-6">از طراحی صفحه تا مدیریت و فروش</h2>
                <div class="space-y-5 relative process-timeline">
                    <?php foreach ([['تحلیل ساختار', 'چیدمان بخش‌ها بر اساس نیاز کاربر و مسیر تماس.'], ['طراحی سریع و سبک', 'استفاده از کد سبک، تصاویر فشرده و انیمیشن‌های کنترل‌شده.'], ['مدیریت محصولات و محتوا', 'افزودن محصولات و بلاگ، فروش و مدیریت فروش.']] as $i => $step): ?>
                    <div class="soft-card rounded-3xl p-5 flex gap-4 hover-lift relative z-10">
                        <div
                            class="process-step-num w-12 h-12 rounded-2xl text-white flex items-center justify-center font-black flex-shrink-0">
                            <?= fa_number($i + 1) ?></div>
                        <div>
                            <h3 class="font-black mb-2"><?= e($step[0]) ?></h3>
                            <p class="text-slate-600 leading-8 text-sm"><?= e($step[1]) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <img src="<?= asset_url('images/process-illustration.svg') ?>" width="700" height="520" class="shadow-xl w-full"
                    alt="فرآیند اجرای پروژه در سایت دوز: از تحلیل تا طراحی و مدیریت و فروش" loading="lazy">
            </div>
        </div>
    </section>

    <section id="portfolio" class="py-20 bg-slate-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">
                <div><span class="text-emerald-600 font-extrabold">نمونه کارها</span>
                    <h2 class="text-3xl md:text-4xl font-black mt-2">نمونه های سایت دوز</h2>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach (array_slice($portfolio, 0, 6) as $i => $item): ?>
                <article class="portfolio-card group bg-slate-900 rounded-3xl overflow-hidden shadow-sm card">
                    <div class="relative overflow-hidden aspect-[4/3]">
                        <img src="<?= asset_url($item['image'] ?? 'images/portfolio-corporate.svg') ?>"
                            class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110"
                            alt="<?= e($item['title'] ?? '') ?>" loading="lazy" />
                        <div class="portfolio-card-overlay absolute inset-0"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                            <span class="text-xs font-black text-emerald-300 mb-2 inline-block"><?= fa_number(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                            <h3 class="font-black text-xl mb-2"><?= e($item['title'] ?? '') ?></h3>
                            <p class="text-sm text-white/75 leading-7 portfolio-card-sub"><?= e($item['subtitle'] ?? '') ?></p>
                            <?php if (!empty($item['url']) && $item['url'] !== '#'): ?>
                            <a href="<?= e($item['url']) ?>"
                                class="inline-flex items-center gap-2 mt-4 text-emerald-300 font-black text-sm portfolio-card-link">مشاهده
                                پروژه
                                <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="seo" class="seo-showcase py-20 bg-slate-950 text-white overflow-hidden relative">
        <div class="absolute inset-0 opacity-30"
            style="background:radial-gradient(circle at 18% 18%,#10b981,transparent 26%),radial-gradient(circle at 86% 76%,#38bdf8,transparent 28%),linear-gradient(135deg,#020617,#0f172a)">
        </div>
        <div class="max-w-6xl mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-5">
                <span class="text-emerald-300 font-extrabold">نمونه کارهای SEO</span>
            </div>

            <div class="seo-shot-frame rounded-[2rem] p-4 md:p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <span class="seo-shot-dot"></span><span class="seo-shot-dot"></span><span
                            class="seo-shot-dot"></span>
                        <strong class="mr-2 text-white">گزارش نمونه رتبه کلمات کلیدی</strong>
                    </div>
                    <span class="text-xs md:text-sm text-white/60">خروجی ها قابل مشاهده در گوگل است.</span>
                </div>

                <div class="seo-report-grid grid lg:grid-cols-3 gap-5">
                    <?php foreach (array_slice($seoItems, 0, 3) as $i => $item): ?>
                    <?php
                    $defaultKeywords = [['طراحی سایت فروشگاهی', 'ساخت فروشگاه اینترنتی', 'طراحی فروشگاه آنلاین'], ['طراحی سایت شرکتی', 'طراحی سایت خدماتی', 'شرکت طراحی سایت'], ['سئو سایت وردپرس', 'خدمات سئو سایت', 'بهینه سازی سایت']];
                    $beforeRanks = [['+۵۰', '۳۸', '۴۶'], ['۴۱', '+۵۰', '۳۳'], ['۳۷', '۴۴', '+۵۰']];
                    $afterRanks = [['۳', '۵', '۸'], ['۲', '۶', '۹'], ['۴', '۷', '۱۰']];
                    $projectLabels = ['پروژه فروشگاهی', 'پروژه شرکتی', 'پروژه خدماتی'];
                    $resultLabels = ['۳ کلمه در صفحه اول', '۲ کلمه Top 5', '۳ کلمه قابل گزارش'];
                    
                    $rawKeywords = trim((string) ($item['keyword'] ?? ''));
                    $keywords = preg_split('/[،,|]+/u', $rawKeywords, -1, PREG_SPLIT_NO_EMPTY);
                    $keywords = array_values(array_map('trim', $keywords ?: []));
                    if (count($keywords) < 3) {
                        $keywords = $defaultKeywords[$i] ?? $defaultKeywords[0];
                    }
                    $keywords = array_slice($keywords, 0, 3);
                    ?>
                    <article
                        class="seo-report-card rounded-[1.75rem] border border-white/20 p-5 md:p-6 text-slate-900">
                        <div class="seo-report-topbar">
                            <span class="seo-report-label"><?= svg_icon('chart', 'w-4 h-4') ?>
                                <?= e($projectLabels[$i] ?? 'پروژه SEO') ?></span>
                            <span class="seo-report-date">نمونه رتبه</span>
                        </div>

                        <h3 class="font-black text-xl leading-8 mt-5 mb-2"><?= e($item['rank'] ?? 'رشد رتبه کلمات') ?>
                        </h3>
                        <p class="text-sm text-slate-600 leading-8 mb-4"><?= e($item['summary'] ?? '') ?></p>

                        <div class="seo-keyword-list" aria-label="جدول کلمات کلیدی بالا آمده">
                            <div class="seo-rank-row is-head">
                                <span>کلمه کلیدی</span>
                                <span>قبل</span>
                                <span>الان</span>
                            </div>
                            <?php    foreach ($keywords as $kIndex => $keyword): ?>
                            <div class="seo-rank-row">
                                <span class="seo-keyword-text"><?= e($keyword) ?></span>
                                <span class="seo-rank-before"><?= e($beforeRanks[$i][$kIndex] ?? '+۵۰') ?></span>
                                <span
                                    class="seo-rank-after <?= ($afterRanks[$i][$kIndex] ?? '') === '۱' ? 'top-one' : '' ?>"><?= e($afterRanks[$i][$kIndex] ?? '۱۰') ?></span>
                            </div>
                            <?php    endforeach; ?>
                        </div>


                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="brands" class="py-16 bg-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-4">
            <p class="text-center text-slate-400 font-bold text-sm mb-6">کسب‌وکارهایی که به سایت دوز اعتماد کرده‌اند</p>
            <div class="brand-slider rounded-3xl bg-slate-50 border border-slate-100 py-6 overflow-hidden"
                aria-label="اسلایدر برندهای همکار">
                <div class="brand-track">
                    <?php $brandList = ['alijenab.png' => 'علی‌جناب','inyab.png' => 'بانک اطلاعاتی صنایع کشور', 'avan.png' => 'آوان', 'inyab.webp' => 'اینیاب', 'elinor.ico' => 'الینور', 'shadila.webp' => 'شادیلا', 'fiore.webp' => 'فیوره', 'ownze.webp' => 'اونزی']; ?>
                    <?php for ($rep = 0; $rep < 3; $rep++): ?>
                    <?php foreach ($brandList as $brand => $brandName): ?>
                    <div class="brand-slide">
                        <img src="<?= asset_url('images/' . $brand) ?>" alt="نمونه کار سایت دوز برای برند <?= e($brandName) ?>" loading="lazy">
                    </div>
                    <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="blog-preview" class="py-20 bg-slate-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">
                <div>
                    <span class="text-emerald-600 font-black">وبلاگ سایت دوز</span>
                </div>
                <a href="<?= site_url('blog') ?>"
                    class="bg-slate-900 text-white px-5 py-3 rounded-2xl font-black hover:bg-emerald-600 transition inline-flex items-center gap-2">مشاهده
                    همه مقالات <?= svg_icon('arrow', 'w-5 h-5') ?></a>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach ($posts as $i => $post): ?>
                <article class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200/70 card">
                    <a href="<?= post_url($post) ?>">
                        <img src="<?= asset_url($post['cover'] ?? 'images/blog-writing-visual.svg') ?>"
                            class="h-52 w-full object-cover transition duration-500 hover:scale-105"
                            alt="<?= e($post['title'] ?? '') ?>" loading="lazy" />
                    </a>
                    <div class="p-6">
                        <div class="text-xs text-emerald-600 font-black mb-3"><?= e($post['category'] ?? 'بلاگ') ?> ·
                            <?= format_date($post['published_at'] ?? '') ?></div>
                        <h3 class="font-black text-lg mb-3 leading-8"><a href="<?= post_url($post) ?>"
                                class="hover:text-emerald-600 transition"><?= e($post['title'] ?? '') ?></a></h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">
                            <?= e($post['excerpt'] ?: excerpt($post['content'] ?? '', 120)) ?></p>
                        <a href="<?= post_url($post) ?>"
                            class="inline-flex items-center gap-2 text-slate-900 font-black hover:text-emerald-600">ادامه
                            مطلب <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- صفحات کلاستر -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-emerald-600 font-extrabold">خدمات تخصصی در گرگان</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-3">طراحی سایت و سئو در گرگان</h2>
                <p class="text-slate-500 max-w-xl mx-auto leading-8">هر کسب‌وکار نیاز متفاوتی دارد. صفحه تخصصی خودتان
                    را
                    انتخاب کنید.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="<?= site_url('tarahi-site-sherkati-gorgan') ?>"
                    class="group soft-card rounded-3xl p-7 hover-lift block border-2 border-transparent hover:border-emerald-200 transition-colors">
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5 group-hover:bg-emerald-100 transition-colors">
                        <?= svg_icon('laptop', 'w-7 h-7') ?></div>
                    <h3 class="font-black text-lg mb-2">طراحی سایت شرکتی</h3>
                    <p class="text-slate-500 text-sm leading-7 mb-4">سایت حرفه‌ای برای معرفی خدمات و اعتمادسازی</p>
                    <span class="text-emerald-600 font-black text-sm inline-flex items-center gap-1">مشاهده
                        <?= svg_icon('arrow', 'w-4 h-4') ?></span>
                </a>
                <a href="<?= site_url('tarahi-site-foroushgahi-gorgan') ?>"
                    class="group soft-card rounded-3xl p-7 hover-lift block border-2 border-transparent hover:border-sky-200 transition-colors">
                    <div
                        class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mb-5 group-hover:bg-sky-100 transition-colors">
                        <?= svg_icon('cart', 'w-7 h-7') ?></div>
                    <h3 class="font-black text-lg mb-2">طراحی سایت فروشگاهی</h3>
                    <p class="text-slate-500 text-sm leading-7 mb-4">فروشگاه آنلاین با درگاه پرداخت و مدیریت موجودی</p>
                    <span class="text-sky-600 font-black text-sm inline-flex items-center gap-1">مشاهده
                        <?= svg_icon('arrow', 'w-4 h-4') ?></span>
                </a>
                <a href="<?= site_url('seo-sherkati-gorgan') ?>"
                    class="group soft-card rounded-3xl p-7 hover-lift block border-2 border-transparent hover:border-teal-200 transition-colors">
                    <div
                        class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:bg-teal-100 transition-colors">
                        <?= svg_icon('chart', 'w-7 h-7') ?></div>
                    <h3 class="font-black text-lg mb-2">سئو سایت شرکتی</h3>
                    <p class="text-slate-500 text-sm leading-7 mb-4">رتبه‌گیری در گوگل برای کلمات خدماتی در گرگان</p>
                    <span class="text-teal-600 font-black text-sm inline-flex items-center gap-1">مشاهده
                        <?= svg_icon('arrow', 'w-4 h-4') ?></span>
                </a>
                <a href="<?= site_url('seo-foroushgahi-gorgan') ?>"
                    class="group soft-card rounded-3xl p-7 hover-lift block border-2 border-transparent hover:border-violet-200 transition-colors">
                    <div
                        class="w-14 h-14 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mb-5 group-hover:bg-violet-100 transition-colors">
                        <?= svg_icon('search', 'w-7 h-7') ?></div>
                    <h3 class="font-black text-lg mb-2">سئو سایت فروشگاهی</h3>
                    <p class="text-slate-500 text-sm leading-7 mb-4">رتبه‌گیری برای کلمات محصولات و افزایش فروش</p>
                    <span class="text-violet-600 font-black text-sm inline-flex items-center gap-1">مشاهده
                        <?= svg_icon('arrow', 'w-4 h-4') ?></span>
                </a>
            </div>
        </div>
    </section>

    <section id="seo-content" class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="relative">

                <!-- محتوا -->
                <div id="seo-text-wrapper" class="relative overflow-hidden"
                    style="max-height:320px; transition: max-height 0.8s cubic-bezier(0.16,1,0.3,1);">

                    <div class="prose-gorgan text-slate-700 leading-9 space-y-8">

                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">طراحی سایت در گرگان و
                            سئو سایت در گرگان؛ با سایت دوز از صفر تا صفحه اول گوگل</h2>
                        <p>کسب‌وکار شما در گرگان است. مشتریانتان هر روز در گوگل دنبال خدمات و محصولات می‌گردند. اگر در
                            آن نتایج نباشید، رقیبتان مشتری شما را می‌گیرد. طراحی سایت در گرگان و این فرایند دقیقاً برای
                            همین هستند — تا شما را جلوی چشم همان مشتریانی بگذارند که همین الان دارند دنبال شما می‌گردند.
                        </p>
                        <p>سایت دوز در طراحی سایت در گرگان و سئو گرگان تخصص دارد. ما سایت‌هایی می‌سازیم که سریع‌اند،
                            مدرن‌اند و از همان روز اول برای رتبه‌گیری در گوگل آماده هستند. بعد با سئو سایت در گرگان
                            آن‌ها را بالا می‌آوریم تا مشتری واقعی بیاورند.</p>
                        <p>در این صفحه همه چیزی که باید درباره طراحی سایت در گرگان و بهینه‌سازی سایت در گرگان بدانید را
                            توضیح می‌دهیم. از اینکه چرا کسب‌وکارهای گرگانی به سایت نیاز دارند، تا اینکه فرایند کار ما
                            چیست، نتایج واقعی چه هستند و چطور می‌توانید همین امروز شروع کنید.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">چرا کسب‌وکارهای گرگانی به طراحی سایت در
                            گرگان نیاز دارند؟</h2>
                        <p>گرگان مرکز استان گلستان است. شهری با جمعیت قابل‌توجه، اقتصاد رو به رشد و رقابت فزاینده در
                            اکثر صنف‌ها. در این فضا، طراحی سایت در گرگان دیگر یک انتخاب اختیاری نیست. یک ضرورت است.</p>
                        <p>وقتی کسی در گرگان دنبال دندانپزشک می‌گردد، اول گوگل را باز می‌کند. وقتی می‌خواهد لوازم خانگی
                            بخرد، اول گوگل را باز می‌کند. وقتی به وکیل، مشاور، پیمانکار، رستوران یا هر خدمت دیگری نیاز
                            دارد، اول گوگل را باز می‌کند. اگر سایت شما در آن نتایج نباشد، عملاً برای آن مشتری وجود
                            ندارید.</p>
                        <p>طراحی سایت در گرگان به کسب‌وکار شما امکان می‌دهد ۲۴ ساعت شبانه‌روز، هفت روز هفته و بدون
                            تعطیلی در دسترس مشتریان باشید. سایت خوب برخلاف یک کارمند هیچ‌وقت مرخصی نمی‌رود، هیچ‌وقت خسته
                            نمی‌شود و هیچ‌وقت اشتباه نمی‌کند.</p>
                        <p>اما طراحی سایت در گرگان به‌تنهایی کافی نیست. صدها کسب‌وکار در گرگان سایت دارند که هیچ
                            مشتری‌ای از آن نمی‌آید. سایت‌هایی که در گوگل دیده نمی‌شوند، یا طراحی ضعیف دارند و
                            بازدیدکننده را فراری می‌دهند. اینجاست که این خدمت وارد ماجرا می‌شود.</p>
                        <p>بهینه‌سازی موتور جستجو در گرگان یعنی بهینه‌سازی سایت شما برای موتور جستجوی گوگل. یعنی وقتی
                            کاربر گرگانی عبارتی مرتبط با کسب‌وکار شما جستجو می‌کند، سایت شما در نتایج اول ظاهر شود. این
                            ترکیب طراحی سایت در گرگان با سئو سایت در گرگان است که واقعاً مشتری می‌آورد.</p>
                        <p>سایت دوز هر دو را با هم ارائه می‌دهد. از همان لحظه‌ای که طراحی سایت در گرگان را شروع می‌کنیم،
                            سئوی محلی هم در ذهن داریم. ساختار سایت، سرعت صفحات، محتوا، تایتل‌ها و URL‌ها — همه از ابتدا
                            برای سئو محلی گرگان بهینه می‌شوند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">طراحی سایت در گرگان؛ سایت دوز چه می‌سازد؟
                        </h2>
                        <p>وقتی از طراحی سایت در گرگان صحبت می‌کنیم، منظورمان فقط یک صفحه زیبا نیست. منظورمان یک ابزار
                            فروش است. سایتی که کاربر را جذب کند، نگه دارد و به مشتری تبدیل کند.</p>

                        <h3 class="text-xl font-black text-slate-800">سایت شرکتی در گرگان</h3>
                        <p>طراحی سایت در گرگان برای کسب‌وکارهای خدماتی یعنی ویترین دیجیتال حرفه‌ای. پزشک، وکیل، مشاور،
                            شرکت ساختمانی، آموزشگاه، آژانس — همه به سایتی نیاز دارند که اعتماد بسازد و مشتری را به تماس
                            تشویق کند.</p>
                        <p>طراحی سایت در گرگان برای کسب‌وکارهای خدماتی در این مجموعه شامل این موارد است:</p>
                        <p>ساختار هدفمند که کاربر را از اولین نگاه تا لحظه تماس هدایت می‌کند. طراحی مدرن و حرفه‌ای که در
                            نگاه اول اعتماد ایجاد کند. صفحه خدمات واضح و کامل. بخش نمونه‌کار یا پورتفولیو. سیستم دریافت
                            مشاوره یا رزرو آنلاین. و مهم‌تر از همه، پایه‌های محکم برای بهینه‌سازی سایت برای گوگل.</p>

                        <h3 class="text-xl font-black text-slate-800">سایت فروشگاهی در گرگان</h3>
                        <p>طراحی سایت در گرگان برای فروشگاه‌های آنلاین داستان متفاوتی دارد. اینجا هدف فروش است. مشتری
                            باید محصول را ببیند، اطلاعاتش را بخواند، به کیفیت اطمینان کند و خرید کند — همه در کمترین
                            زمان ممکن.</p>
                        <p>طراحی سایت در گرگان برای فروشگاه آنلاین در سایت دوز یعنی: صفحات محصول جذاب و کامل، مسیر خرید
                            ساده و کوتاه، درگاه پرداخت امن، سیستم مدیریت موجودی، کد تخفیف و باشگاه مشتریان، و ساختار فنی
                            که سئو سایت در گرگان روی آن راحت انجام شود.</p>

                        <h3 class="text-xl font-black text-slate-800">سایت تبلیغاتی و لندینگ پیج در گرگان</h3>
                        <p>گاهی نیاز نیست کل سایت بسازید. یک لندینگ پیج قوی برای یک محصول، یک خدمت یا یک کمپین خاص
                            می‌تواند بیشتر از یک سایت کامل نتیجه بدهد. طراحی سایت در گرگان در قالب لندینگ پیج یکی دیگر
                            از خدمات سایت دوز است.</p>

                        <h3 class="text-xl font-black text-slate-800">بهینه‌سازی سایت موجود در گرگان</h3>
                        <p>اگر سایت دارید اما نتیجه نمی‌گیرید، لزوماً نیاز به طراحی سایت در گرگان از صفر ندارید. گاهی
                            بهینه‌سازی سایت موجود — مرتب‌سازی محتوا، بهبود سرعت، لینک‌سازی داخلی و این فرایند — کافی
                            است.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">ویژگی‌های طراحی سایت در گرگان در سایت دوز
                        </h2>
                        <p>طراحی سایت در گرگان توسط سایت دوز با چند اصل اساسی انجام می‌شود که آن را از بقیه متمایز
                            می‌کند:</p>

                        <h3 class="text-xl font-black text-slate-800">سرعت؛ اولین اولویت</h3>
                        <p>سرعت بارگذاری صفحات هم برای تجربه کاربری مهم است و هم برای سئو گرگان. گوگل سایت‌های کند را
                            جریمه می‌کند. کاربری که صفحه ۵ ثانیه طول می‌کشد تا باز شود، سایت را می‌بندد و به رقیب
                            می‌رود.</p>
                        <p>سایت دوز در طراحی سایت در گرگان از کد سبک، تصاویر فشرده و انیمیشن‌های کنترل‌شده استفاده
                            می‌کند. نتیجه سایتی است که در کمتر از ۳ ثانیه بارگذاری می‌شود — چه روی کامپیوتر، چه روی
                            موبایل.</p>

                        <h3 class="text-xl font-black text-slate-800">موبایل‌فرندلی</h3>
                        <p>بیش از ۷۰ درصد کاربران ایرانی از موبایل اینترنت استفاده می‌کنند. طراحی سایت در گرگان باید روی
                            موبایل کاملاً درست کار کند. در تیم ما هر سایت از ابتدا برای موبایل طراحی می‌شود، نه اینکه
                            بعداً برای موبایل «تنظیم» شود.</p>

                        <h3 class="text-xl font-black text-slate-800">پنل مدیریت ساده</h3>
                        <p>بعد از طراحی سایت در گرگان و تحویل پروژه، باید بتوانید سایتتان را خودتان مدیریت کنید. سایت
                            دوز پنل مدیریتی تحویل می‌دهد که بدون نیاز به هیچ دانش برنامه‌نویسی، می‌توانید متن، تصویر،
                            محصول و بلاگ اضافه یا تغییر دهید.</p>

                        <h3 class="text-xl font-black text-slate-800">آماده برای سئو سایت در گرگان</h3>
                        <p>هر سایتی که سایت دوز می‌سازد، از همان ابتدا برای بهینه‌سازی سایت در گرگان آماده است. این
                            یعنی:</p>
                        <p>ساختار URL تمیز و خوانا. تایتل و متا دیسکریپشن درست برای هر صفحه. ساختار هدینگ‌های H1 تا H6
                            اصولی. تصاویر با alt text مناسب. نقشه سایت XML. فایل robots.txt صحیح. گواهی SSL فعال. و سرعت
                            بالا که گوگل دوست دارد.</p>
                        <p>وقتی طراحی سایت در گرگان با این پایه‌ها انجام شود، سئو سایت در گرگان بعداً سریع‌تر و با هزینه
                            کمتر نتیجه می‌دهد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">سئو سایت در گرگان؛ چطور کار می‌کند و چرا
                            مهم است؟</h2>
                        <p>این خدمت یعنی مجموعه اقداماتی که باعث می‌شود سایت شما در نتایج جستجوی گوگل برای کلمات کلیدی
                            مرتبط با کسب‌وکارتان در گرگان رتبه بالایی داشته باشد.</p>
                        <p>وقتی کسی در گرگان «خدمات حسابداری گرگان» یا «رستوران گرگان» یا «تعمیر موبایل گرگان» جستجو
                            می‌کند، گوگل از بین صدها سایت، بهترین‌ها را در صفحه اول نمایش می‌دهد. بهینه‌سازی موتور جستجو
                            در گرگان کاری می‌کند که سایت شما در آن بهترین‌ها باشد.</p>

                        <h3 class="text-xl font-black text-slate-800">چرا سئو سایت در گرگان از تبلیغات بهتر است؟</h3>
                        <p>تبلیغات گوگل (Google Ads) نتایج فوری می‌دهد اما وقتی بودجه تمام می‌شود، همه چیز قطع می‌شود.
                            سئوی محلی کندتر نتیجه می‌دهد — معمولاً ۳ تا ۶ ماه — اما نتیجه‌اش پایدار است. رتبه‌ای که با
                            سئو سایت در گرگان به دست می‌آید، ماه‌ها و حتی سال‌ها می‌ماند بدون اینکه هزینه مداوم داشته
                            باشد.</p>
                        <p>علاوه بر این، کاربران به نتایج ارگانیک گوگل بیشتر از تبلیغات اعتماد دارند. وقتی سایت شما با
                            سئو محلی گرگان در نتایج اول باشد، اعتبار بیشتری نسبت به تبلیغ پولی دارد.</p>

                        <h3 class="text-xl font-black text-slate-800">بخش‌های اصلی سئو سایت در گرگان</h3>
                        <p>بهینه‌سازی سایت برای گوگل در سایت دوز پنج بخش اصلی دارد:</p>
                        <p>بخش اول — تحقیق کلمات کلیدی: قبل از هر کاری باید بدانیم کاربران گرگانی با چه عبارت‌هایی سایت
                            شما را جستجو می‌کنند. این تحقیق پایه این فرایند است. اشتباه در این مرحله یعنی ماه‌ها وقت و
                            پول هدر رفته.</p>
                        <p>بخش دوم — سئو داخلی: بهینه‌سازی تایتل صفحات، متا دیسکریپشن‌ها، ساختار هدینگ‌ها، محتوای صفحات،
                            تصاویر و لینک‌های داخلی. این بخش سئو گرگان پایه‌ای‌ترین و ضروری‌ترین است.</p>
                        <p>بخش سوم — سئو فنی: سرعت سایت، سازگاری با موبایل، ساختار URL، گواهی SSL، نقشه سایت و سایر
                            فاکتورهای فنی که گوگل برای رتبه‌بندی مهم می‌داند. سئو سایت در گرگان بدون پایه فنی محکم پیش
                            نمی‌رود.</p>
                        <p>بخش چهارم — سئو محلی: برای کسب‌وکارهای گرگانی، سئو محلی بخشی از بهینه‌سازی سایت در گرگان است
                            که اهمیت ویژه دارد. ثبت و بهینه‌سازی گوگل مای‌بیزینس، استفاده از نام شهر و محله در محتوا،
                            جمع‌آوری نظرات مثبت مشتریان و ثبت سایت در دایرکتوری‌های محلی — همه اینها این خدمت را تقویت
                            می‌کنند.</p>
                        <p>بخش پنجم — تولید محتوا: گوگل محتوایی را بالا می‌آورد که واقعاً به سوال کاربر پاسخ می‌دهد.
                            بهینه‌سازی موتور جستجو در گرگان بدون تولید محتوای منظم و هدفمند ناقص است. ما در کنار سئو
                            سایت در گرگان، بلاگ هدفمند با موضوعات مرتبط با کسب‌وکار شما تولید می‌کند.</p>

                        <h3 class="text-xl font-black text-slate-800">سئو سایت در گرگان؛ نتایج واقعی</h3>
                        <p>سایت دوز نتایج سئوی محلی را مستند می‌کند. در پروژه‌های قبلی:</p>
                        <p>برای پروژه‌های شرکتی: کلمه «طراحی سایت شرکتی» از رتبه ۴۱ به رتبه ۲ رسیده. کلمه «شرکت طراحی
                            سایت» از رتبه ۳۳ به رتبه ۹. این نتایج در گوگل قابل بررسی هستند.</p>
                        <p>برای پروژه‌های فروشگاهی: کلمه «طراحی سایت فروشگاهی» از بالای ۵۰ به رتبه ۳ رسیده. کلمه «ساخت
                            فروشگاه اینترنتی» از رتبه ۳۸ به رتبه ۵.</p>
                        <p>برای خدمات سئو: کلمه «سئو سایت وردپرس» از رتبه ۳۷ به رتبه ۴. کلمه «خدمات سئو سایت» از رتبه ۴۴
                            به رتبه ۷.</p>
                        <p>این اعداد نشان می‌دهند سئو محلی گرگان با رویکرد درست در بازه زمانی معقول نتیجه می‌دهد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">سئو سایت در گرگان برای چه کسب‌وکارهایی؟
                        </h2>
                        <p>بهینه‌سازی سایت برای گوگل برای تقریباً هر کسب‌وکاری که مشتری محلی دارد مفید است. اما برخی
                            صنف‌ها بیشتر از بقیه سود می‌برند:</p>
                        <p>پزشکان و کلینیک‌ها: مردم گرگان قبل از انتخاب پزشک در گوگل جستجو می‌کنند. سئو سایت در گرگان
                            برای کلینیک‌ها مستقیماً به افزایش مراجعان منجر می‌شود.</p>
                        <p>وکلا و مشاوران: «وکیل در گرگان»، «مشاوره حقوقی گرگان» — اینها کلماتی هستند که این فرایند
                            می‌تواند سایت شما را برای آن‌ها بالا بیاورد.</p>
                        <p>رستوران، کافه و هتل: گردشگرانی که به گرگان می‌آیند و مردم محلی که دنبال تجربه جدید می‌گردند،
                            در گوگل جستجو می‌کنند. سئو گرگان آن‌ها را به سایت شما می‌رساند.</p>
                        <p>فروشگاه‌های آنلاین: محصولات گرگانی — از صنایع دستی تا محصولات کشاورزی تا پوشاک — می‌توانند با
                            بهینه‌سازی سایت در گرگان به مشتریان سراسر ایران برسند.</p>
                        <p>آموزشگاه‌ها و مراکز آموزشی: «آموزشگاه زبان گرگان»، «کلاس گیتار گرگان» — سئو سایت در گرگان
                            دانش‌آموزان بیشتری می‌آورد.</p>
                        <p>شرکت‌های ساختمانی و پیمانکاری: رقابت در این بازار در گرگان زیاد است. این خدمت به کسانی که
                            زودتر شروع می‌کنند مزیت می‌دهد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">فرایند کار سایت دوز در طراحی سایت در گرگان
                            و سئو سایت در گرگان</h2>
                        <p>سایت دوز یک فرایند شفاف و سه‌مرحله‌ای دارد:</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله اول: مشاوره و تحلیل</h3>
                        <p>اول از همه گوش می‌دهیم. کسب‌وکار شما چیست؟ مشتری هدفتان کیست؟ رقبای اصلی در گرگان کدام‌اند؟
                            هدف از طراحی سایت در گرگان چیست — معرفی، فروش یا جذب سرنخ؟</p>
                        <p>بر اساس این مکالمه، ساختار سایت پیشنهاد می‌دهیم. کلمات کلیدی هدف برای بهینه‌سازی موتور جستجو
                            در گرگان انتخاب می‌شوند. رقبا در گوگل بررسی می‌شوند تا بدانیم برای رسیدن به صفحه اول چقدر
                            کار لازم است.</p>
                        <p>مشاوره اول کاملاً رایگان است.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله دوم: طراحی و توسعه</h3>
                        <p>طراحی سایت در گرگان بر اساس تحلیل مرحله اول آغاز می‌شود. ساختار صفحات، طراحی بصری، توسعه فنی
                            و بهینه‌سازی سرعت — همه با هم و همزمان انجام می‌شوند.</p>
                        <p>محتوای اولیه صفحات هم در این مرحله نوشته یا بهینه می‌شود. این محتوا از ابتدا با نگاه به سئوی
                            محلی نوشته می‌شود — کلمات کلیدی درست، ساختار مناسب و پاسخ به سوالات واقعی کاربران گرگانی.
                        </p>

                        <h3 class="text-xl font-black text-slate-800">مرحله سوم: راه‌اندازی و رشد</h3>
                        <p>سایت منتشر می‌شود. اما کار اینجا تمام نیست. سئو سایت در گرگان یک فرایند مداوم است. رتبه‌های
                            سایت هر ماه پایش می‌شوند. محتوای جدید منتشر می‌شود. لینک‌سازی انجام می‌شود. و گزارش ماهانه
                            از پیشرفت سئو محلی گرگان ارائه می‌شود.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">هزینه طراحی سایت در گرگان و سئو سایت در
                            گرگان</h2>
                        <p>یکی از اولین سوالاتی که مشتریان می‌پرسند اینست که هزینه طراحی سایت در گرگان چقدر است. جواب
                            صادقانه: بستگی دارد.</p>
                        <p>طراحی سایت در گرگان برای یک کسب‌وکار کوچک با چند صفحه ساده با طراحی سایت در گرگان برای یک
                            فروشگاه بزرگ با صدها محصول فرق دارد. اما در هر دو حالت، سایت دوز قیمتی ارائه می‌دهد که برای
                            بازار گرگان واقعی و منطقی باشد.</p>
                        <p>بهینه‌سازی سایت برای گوگل هم دو مدل دارد:</p>
                        <p>مدل پروژه‌ای: یک‌بار هزینه می‌کنید، سایت بهینه می‌شود، پایه‌های این فرایند گذاشته می‌شود.
                            برای کسب‌وکارهایی که بودجه محدود دارند مناسب است.</p>
                        <p>مدل ماهانه: هر ماه کار سئو گرگان ادامه دارد — محتوای جدید، لینک‌سازی، پایش رتبه‌ها و
                            بهینه‌سازی مداوم. برای کسب‌وکارهایی که می‌خواهند رتبه‌هایشان را توسعه دهند و نگه دارند مناسب
                            است.</p>
                        <p>برای دریافت قیمت دقیق طراحی سایت در گرگان و سئو سایت در گرگان، با این مجموعه مشاوره رایگان
                            بگیرید.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">چرا سایت دوز را برای طراحی سایت و سئو در
                            گرگان انتخاب کنید؟</h2>
                        <p>در بازار طراحی سایت در گرگان گزینه‌های مختلفی وجود دارد. اما سایت دوز چند تفاوت اساسی با بقیه
                            دارد:</p>
                        <p>یک جا، دو تخصص: اکثر شرکت‌های طراحی سایت در گرگان یا طراحی بلدند یا بهینه‌سازی سایت در گرگان.
                            سایت دوز هر دو را با هم ارائه می‌دهد. این یعنی هماهنگی کامل بین طراحی سایت در گرگان و این
                            خدمت از روز اول.</p>
                        <p>نتایج قابل اندازه‌گیری: ما رتبه‌ها را قبل از شروع بهینه‌سازی موتور جستجو در گرگان ثبت می‌کنیم
                            و هر ماه گزارش می‌دهیم. می‌توانید با چشمان خودتان رشد را در گوگل ببینید.</p>
                        <p>شفافیت کامل: در طراحی سایت در گرگان و سئو سایت در گرگان، هیچ هزینه پنهانی وجود ندارد. از
                            ابتدا قیمت شفاف می‌گوییم و به آن پایبند هستیم.</p>
                        <p>پشتیبانی واقعی: بعد از تحویل طراحی سایت در گرگان، اگر سوال داشتید، مشکلی پیش آمد یا نیاز به
                            تغییر داشتید، تیم سایت دوز در دسترس است.</p>
                        <p>درک بازار محلی: تیم ما بازار گرگان را می‌شناسد. می‌دانیم چه کلماتی جستجو می‌شوند، رقابت در هر
                            صنف چقدر است و چه رویکردی در سئوی محلی برای این بازار نتیجه می‌دهد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">سوالات پرتکرار درباره طراحی سایت در گرگان و
                            سئو سایت در گرگان</h2>

                        <h3 class="text-xl font-black text-slate-800">چقدر طول می‌کشد طراحی سایت در گرگان آماده شود؟
                        </h3>
                        <p>زمان طراحی سایت در گرگان به پیچیدگی پروژه بستگی دارد. یک سایت شرکتی ساده معمولاً ۷ تا ۱۴ روز
                            کاری. یک فروشگاه اینترنتی کامل ۲۱ تا ۳۰ روز. برای زمان دقیق پروژه خودتان، جزئیات را با ما در
                            میان بگذارید.</p>

                        <h3 class="text-xl font-black text-slate-800">نتایج سئو محلی گرگان چه موقع دیده می‌شود؟</h3>
                        <p>بهینه‌سازی سایت برای گوگل در بازار محلی گرگان — که رقابتش از تهران کمتر است — معمولاً در ۳ تا
                            ۶ ماه نتایج اولیه می‌دهد. برخی کلمات کم‌رقابت‌تر ممکن است در همان ماه اول یا دوم به صفحه اول
                            بروند.</p>

                        <h3 class="text-xl font-black text-slate-800">آیا بعد از طراحی سایت در گرگان می‌توانم محتوا را
                            خودم تغییر دهم؟</h3>
                        <p>بله. سایت دوز در طراحی سایت در گرگان پنل مدیریت ساده تحویل می‌دهد که بدون دانش فنی می‌توانید
                            محتوا، تصاویر و متن‌ها را تغییر دهید.</p>

                        <h3 class="text-xl font-black text-slate-800">آیا سئو سایت در گرگان باید ماهانه ادامه داشته
                            باشد؟</h3>
                        <p>این فرایند مداوم نتایج بهتری می‌دهد. اما اگر بودجه محدود دارید، با یک پروژه پایه هم می‌توانید
                            شروع کنید. در مشاوره رایگان توضیح می‌دهیم کدام مدل برای شما مناسب‌تر است.</p>

                        <h3 class="text-xl font-black text-slate-800">چطور می‌توانم سفارش طراحی سایت در گرگان یا سئو
                            گرگان بدهم؟</h3>
                        <p>از همین صفحه درخواست مشاوره بدهید. تیم سایت دوز در کوتاه‌ترین زمان با شما تماس می‌گیرد.</p>

                        <h3 class="text-xl font-black text-slate-800">آیا سایت دوز فقط در گرگان کار می‌کند؟</h3>
                        <p>سایت دوز در طراحی سایت در گرگان و بهینه‌سازی سایت در گرگان تخصص دارد اما به صورت آنلاین به
                            کسب‌وکارهای سراسر کشور هم خدمت ارائه می‌دهد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">طراحی سایت در گرگان و سئو سایت در گرگان
                            برای کدام صنف‌ها مناسب است؟</h2>
                        <p>ما در طراحی سایت در گرگان و سئو سایت در گرگان برای طیف گسترده‌ای از صنف‌ها تجربه دارد:</p>
                        <p>کلینیک‌ها و مطب‌های پزشکی که می‌خواهند مراجعان بیشتری جذب کنند. دفاتر حقوقی و مشاوره که به
                            اعتمادسازی آنلاین نیاز دارند. شرکت‌های ساختمانی و پیمانکاری که می‌خواهند نمونه‌کارهایشان
                            دیده شود. آموزشگاه‌ها و مراکز آموزشی که دنبال دانش‌آموز بیشتر هستند. رستوران‌ها، کافه‌ها و
                            هتل‌ها که می‌خواهند در جستجوی گردشگران باشند. فروشگاه‌های آنلاین که می‌خواهند بفروشند. و هر
                            کسب‌وکار دیگری که مشتریانش در گوگل جستجو می‌کنند.</p>
                        <p>اگر کسب‌وکار شما در این لیست نیست، باز هم با سایت دوز صحبت کنید. احتمالاً طراحی سایت در گرگان
                            و این خدمت برای شما هم راه‌حل دارد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">همین الان شروع کنید</h2>
                        <p>هر روزی که سایت ندارید یا بهینه‌سازی موتور جستجو در گرگان را شروع نکرده‌اید، مشتری از دست
                            می‌دهید. رقبای شما در حال رشد هستند. هر ماه تأخیر یعنی یک ماه عقب‌تر از رقیب.</p>
                        <p>طراحی سایت در گرگان با سایت دوز یعنی سایتی که واقعاً کار می‌کند. سئوی محلی با سایت دوز یعنی
                            دیده شدن توسط مشتریانی که همین الان دارند شما را جستجو می‌کنند.</p>
                        <p>مشاوره اول کاملاً رایگان است. جزئیات کسب‌وکارتان را بگویید، ما بهترین راه‌حل را پیشنهاد
                            می‌دهیم.</p>
                        <p class="font-black text-emerald-700">سایت دوز — متخصص طراحی سایت در گرگان و سئو سایت در گرگان
                            برای کسب‌وکارهای گرگانی.</p>

                    </div>

                    <!-- fade overlay هنگام بسته بودن -->
                    <div id="seo-fade" class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none"
                        style="background:linear-gradient(to bottom,transparent,#fff);transition:opacity 0.5s ease;">
                    </div>
                </div>

                <!-- دکمه‌ها -->
                <div class="text-center mt-6">
                    <button id="seo-expand-btn" onclick="toggleSeoContent(true)"
                        class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white font-extrabold px-8 py-4 rounded-2xl shadow-lg transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                        مشاهده متن کامل
                    </button>
                    <button id="seo-collapse-btn" onclick="toggleSeoContent(false)" style="display:none;"
                        class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 active:scale-95 text-slate-800 font-extrabold px-8 py-4 rounded-2xl shadow transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 15l-6-6-6 6" />
                        </svg>
                        بستن
                    </button>
                </div>

            </div>
        </div>
    </section>

    <section id="faq" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-emerald-600 font-black">سوالات پرتکرار</span>
                <h2 class="text-3xl md:text-4xl font-black mt-2">سوالات متداول درباره طراحی سایت در گرگان و سئو سایت در
                    گرگان</h2>
            </div>
            <div class="space-y-4">
                <?php
$faqs = [
    ['چقدر طول می‌کشد طراحی سایت در گرگان آماده شود؟', 'زمان طراحی سایت در گرگان به پیچیدگی پروژه بستگی دارد. یک سایت شرکتی ساده معمولاً ۷ تا ۱۴ روز کاری. یک فروشگاه اینترنتی کامل ۲۱ تا ۳۰ روز. برای زمان دقیق پروژه خودتان، جزئیات را با ما در میان بگذارید.'],
    ['نتایج سئو سایت در گرگان چه موقع دیده می‌شود؟', 'سئو سایت در گرگان در بازار محلی گرگان — که رقابتش از تهران کمتر است — معمولاً در ۳ تا ۶ ماه نتایج اولیه می‌دهد. برخی کلمات کم‌رقابت‌تر ممکن است در همان ماه اول یا دوم به صفحه اول بروند.'],
    ['آیا بعد از طراحی سایت در گرگان می‌توانم محتوا را خودم تغییر دهم؟', 'بله. سایت دوز در طراحی سایت در گرگان پنل مدیریت ساده تحویل می‌دهد که بدون دانش فنی می‌توانید محتوا، تصاویر و متن‌ها را تغییر دهید.'],
    ['آیا سئو سایت در گرگان باید ماهانه ادامه داشته باشد؟', 'سئو سایت در گرگان مداوم نتایج بهتری می‌دهد. اما اگر بودجه محدود دارید، با یک پروژه پایه هم می‌توانید شروع کنید. در مشاوره رایگان توضیح می‌دهیم کدام مدل برای شما مناسب‌تر است.'],
    ['آیا سایت دوز فقط در گرگان کار می‌کند؟', 'سایت دوز در طراحی سایت در گرگان و سئو سایت در گرگان تخصص دارد اما به صورت آنلاین به کسب‌وکارهای سراسر کشور هم خدمت ارائه می‌دهد.'],
];
foreach ($faqs as $faq): ?>
                <div class="faq-item soft-card rounded-2xl p-5 cursor-pointer">
                    <div class="flex justify-between items-center gap-4">
                        <h3 class="font-black text-lg"><?= e($faq[0]) ?></h3>
                        <?= svg_icon('chevron', 'w-5 h-5 faq-icon transition-transform duration-300 text-emerald-600') ?>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 leading-8 pt-4"><?= e($faq[1]) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script type="application/ld+json"><?= json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(static fn($faq) => [
                '@type' => 'Question',
                'name' => $faq[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq[1]],
            ], $faqs),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
