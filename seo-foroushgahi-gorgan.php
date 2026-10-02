<?php
require_once __DIR__ . '/app/functions.php';
$settings = get_settings();
render_public_head(
    'سئو سایت فروشگاهی در گرگان | سایت دوز',
    'سئو سایت فروشگاهی در گرگان — رتبه‌گیری برای کلمات محصولات و افزایش فروش آنلاین. سایت دوز متخصص سئو فروشگاه اینترنتی در گرگان. مشاوره رایگان.',
    site_url('seo-foroushgahi-gorgan'),
    'images/logo.png',
    true
);
include __DIR__ . '/partials/header.php';
?>
<main style="padding-top:4rem;">

    <!-- Hero -->
    <section class="hero-bg text-white pt-28 pb-20 lg:pt-32 lg:pb-24 relative overflow-hidden">
        <div class="hero-grid-pattern"></div>
        <div class="hero-orb hero-orb--1"></div>
        <div class="hero-orb hero-orb--2"></div>
        <div class="scroll-cue"><span>بیشتر ببین</span><span class="scroll-cue-icon"></span></div>
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center relative z-10">
            <div>
                <span
                    class="inline-block bg-violet-500/20 text-violet-300 font-extrabold text-sm px-4 py-2 rounded-full mb-5">سئو
                    فروشگاهی · گرگان</span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-6">سئو سایت فروشگاهی در گرگان</h1>
                <p class="text-white/80 leading-9 text-lg mb-8 max-w-xl">فروشگاه آنلاین بدون ترافیک، سود ندارد. سئو سایت
                    فروشگاهی در گرگان با سایت دوز — رتبه‌گیری برای کلمات محصولات، افزایش فروش، گزارش ماهانه.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="tel:<?= e($settings['phone'] ?? '') ?>"
                        class="btn-main bg-violet-500 hover:bg-violet-400 text-white px-7 py-4 rounded-2xl font-extrabold inline-flex items-center gap-2">
                        <?= svg_icon('phone', 'w-5 h-5') ?> مشاوره رایگان
                    </a>
                    <a href="<?= site_url('#foroushgahi-results') ?>"
                        class="btn-outline border border-white/40 px-7 py-4 rounded-2xl inline-flex items-center gap-2 font-bold">
                        نتایج واقعی <?= svg_icon('arrow', 'w-5 h-5') ?>
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-8 max-w-sm">
                    <div class="glass-card rounded-2xl p-4 text-center"><strong class="block text-xl">فروش</strong><span
                            class="text-xs text-white/70">بیشتر</span></div>
                    <div class="glass-card rounded-2xl p-4 text-center"><strong
                            class="block text-xl">ارگانیک</strong><span class="text-xs text-white/70">بدون هزینه
                            کلیک</span></div>
                    <div class="glass-card rounded-2xl p-4 text-center"><strong
                            class="block text-xl">پایدار</strong><span class="text-xs text-white/70">ماه‌ها
                            می‌ماند</span></div>
                </div>
            </div>
            <div class="relative overflow-visible">
                <div class="absolute z-20 -top-5 -left-3 glass-card rounded-2xl px-4 py-3 soft-float hidden md:block">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white/15 text-violet-300">
                            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 14a8 8 0 1 1 16 0" />
                                <path d="M12 14l4-4" />
                                <path d="M5 19h14" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs text-white/65 mb-1">سئو فروشگاهی</div>
                            <div class="text-sm font-extrabold text-violet-300 whitespace-nowrap">فروش بیشتر، کلیک
                                رایگان</div>
                        </div>
                    </div>
                </div>

                <div
                    class="absolute z-20 -bottom-14 -right-3 md:-bottom-10 md:-right-4 bg-white/95 text-slate-900 rounded-2xl px-4 py-3 shadow-xl pulse-glow hidden md:block">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19h16" />
                                <path d="M6 15l4-4 3 3 5-6" />
                                <path d="M18 8v4h-4" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs text-slate-500 mb-1">نتیجه سئو</div>
                            <div class="text-sm font-extrabold whitespace-nowrap">از بالای ۵۰ به رتبه ۳</div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual-frame" data-tilt>
                    <div class="hero-visual-chrome"><span></span><span></span><span></span><div class="hero-visual-url">sitedooz.ir</div></div>
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?= asset_url('images/hero-illustration-mobile.svg') ?>">
                        <img src="<?= asset_url('images/hero-illustration.svg') ?>"
                            width="900" height="600"
                            class="relative z-10 w-full soft-float" alt="سئو سایت فروشگاهی در گرگان — سایت دوز"
                            loading="eager" />
                    </picture>
                </div>

                <div class="hero-mobile-cards mt-4 grid grid-cols-2 gap-3 md:hidden">
                    <div class="hero-mobile-card rounded-2xl bg-white/95 px-3 py-3 shadow-lg border border-slate-100">
                        <div class="flex items-start gap-2">
                            <span
                                class="hero-icon inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-4 h-4"
                                    aria-hidden="true">
                                    <path d="M4 14a8 8 0 1 1 16 0" stroke="currentColor" stroke-width="2" />
                                    <path d="M12 14l4-4" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                    <path d="M5 19h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-[11px] text-slate-500 mb-1">سئو فروشگاهی</div>
                                <div class="text-sm font-extrabold text-violet-600 leading-6">فروش بیشتر</div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-mobile-card rounded-2xl bg-white/95 px-3 py-3 shadow-lg border border-slate-100">
                        <div class="flex items-start gap-2">
                            <span
                                class="hero-icon inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-4 h-4"
                                    aria-hidden="true">
                                    <path d="M4 19h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    <path d="M6 15l4-4 3 3 5-6" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18 8v4h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-[11px] text-slate-500 mb-1">نتیجه سئو</div>
                                <div class="text-sm font-extrabold text-emerald-600 leading-6">+۵۰ → رتبه ۳</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- تفاوت سئو فروشگاهی -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-violet-600 font-extrabold">چرا سئو فروشگاهی؟</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">سئو فروشگاهی با سئو شرکتی چه فرقی دارد؟</h2>
                <p class="text-slate-500 leading-8">سئو فروشگاهی روی صفحات محصول، دسته‌بندی‌ها و کلمات خرید تمرکز دارد.
                    هدف مستقیم آن افزایش فروش است نه صرفاً ترافیک.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
$cards = [
    ['cart', 'سئو صفحات محصول', 'هر محصول صفحه‌ای با تایتل، متا و محتوای بهینه دارد. گوگل محصولات شما را پیدا می‌کند.', 'bg-violet-50 text-violet-600'],
    ['search', 'سئو دسته‌بندی', 'صفحات دسته‌بندی با حجم جستجوی بالاتر — ترافیک بیشتر و فروش بیشتر.', 'bg-sky-50 text-sky-600'],
    ['star', 'کلمات خرید', '«خرید آنلاین» + محصول شما + گرگان — کلماتی که نیت خرید دارند.', 'bg-emerald-50 text-emerald-600'],
    ['speed', 'سرعت فروشگاه', 'فروشگاه کند مشتری را فراری می‌دهد. سرعت هم UX و هم رتبه سئو را بهبود می‌دهد.', 'bg-teal-50 text-teal-600'],
    ['chart', 'ریچ اسنیپت', 'نمایش قیمت، موجودی و رتبه در نتایج گوگل — CTR بالاتر.', 'bg-amber-50 text-amber-600'],
    ['check', 'لینک‌سازی داخلی', 'شبکه‌ای از لینک‌های داخلی بین محصولات و دسته‌بندی‌ها — قدرت سئو تقسیم می‌شود.', 'bg-rose-50 text-rose-600'],
];
foreach ($cards as $c): ?>
                <div class="service-card bg-white p-8 rounded-3xl shadow-sm border border-slate-100 card">
                    <div class="w-14 h-14 rounded-2xl <?= $c[3] ?> flex items-center justify-center mb-6">
                        <?= svg_icon($c[0], 'w-8 h-8') ?></div>
                    <h3 class="font-black text-xl mb-3"><?= e($c[1]) ?></h3>
                    <p class="text-sm text-gray-600 leading-8"><?= e($c[2]) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- فرایند سئو فروشگاهی -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-14 items-center">
            <div>
                <img src="<?= asset_url('images/process-illustration.svg') ?>" width="700" height="520" class="shadow-xl w-full"
                    alt="سئو فروشگاه اینترنتی گرگان" loading="lazy">
            </div>
            <div>
                <span class="text-violet-600 font-extrabold">فرایند کار</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-8">سئو سایت فروشگاهی در گرگان چطور انجام می‌شود؟</h2>
                <div class="space-y-4">
                    <?php
$steps = [
    ['آنالیز فروشگاه', 'بررسی ساختار فعلی، صفحات محصول، سرعت و وضعیت فعلی در گوگل.'],
    ['تحقیق کلمات کلیدی خرید', 'پیدا کردن کلماتی که نیت خرید دارند — «خرید + محصول + گرگان».'],
    ['بهینه‌سازی صفحات', 'تایتل، متا، محتوا و ساختار هر صفحه محصول و دسته‌بندی.'],
    ['ریچ اسنیپت و اسکیما', 'نمایش قیمت و رتبه مستقیم در نتایج گوگل.'],
    ['گزارش ماهانه', 'رتبه‌ها و فروش هر ماه مقایسه می‌شوند. نتیجه واقعی می‌بینید.'],
];
foreach ($steps as $i => $step): ?>
                    <div class="soft-card rounded-2xl p-5 flex gap-4 hover-lift">
                        <div
                            class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 font-black flex items-center justify-center shrink-0">
                            <?= fa_number($i + 1) ?></div>
                        <div>
                            <h3 class="font-black mb-1"><?= e($step[0]) ?></h3>
                            <p class="text-slate-500 text-sm leading-7"><?= e($step[1]) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- نتایج واقعی -->
    <section id="foroushgahi-results" class="py-20 bg-slate-950 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none"
            style="background:radial-gradient(circle at 20% 50%,#8b5cf6,transparent 40%),radial-gradient(circle at 80% 50%,#38bdf8,transparent 40%)">
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <div class="text-center mb-14">
                <span class="text-violet-300 font-extrabold">نتایج واقعی</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3">سئو فروشگاهی — ارقام واقعی</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php
$results = [
    ['طراحی سایت فروشگاهی', '+۵۰', '۳'],
    ['ساخت فروشگاه اینترنتی', '۳۸', '۵'],
    ['طراحی فروشگاه آنلاین', '۴۶', '۸'],
];
foreach ($results as $r): ?>
                <div class="bg-white/10 border border-white/20 rounded-3xl p-7 text-center backdrop-blur-sm hover-lift">
                    <div class="text-white/60 text-sm mb-4 font-bold"><?= e($r[0]) ?></div>
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-3xl font-black text-slate-400"><?= e($r[1]) ?></div>
                            <div class="text-xs text-white/50 mt-1">قبل</div>
                        </div>
                        <svg class="w-6 h-6 text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M5 12h14M13 5l7 7-7 7" />
                        </svg>
                        <div class="text-center">
                            <div class="text-3xl font-black text-violet-400"><?= e($r[2]) ?></div>
                            <div class="text-xs text-white/50 mt-1">الان</div>
                        </div>
                    </div>
                    <div class="text-xs text-violet-300 font-bold bg-violet-500/20 rounded-full px-3 py-1 inline-block">
                        صفحه اول گوگل</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- لینک کلاستر -->
    <section class="py-14 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-violet-600 font-extrabold">خدمات مرتبط</span>
                <h2 class="text-2xl font-black mt-2">سایر خدمات سایت دوز در گرگان</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-5">
                <a href="<?= site_url('tarahi-site-sherkati-gorgan') ?>"
                    class="soft-card rounded-2xl p-6 hover-lift block">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                        <?= svg_icon('laptop', 'w-5 h-5') ?></div>
                    <h3 class="font-black mb-2">طراحی سایت شرکتی در گرگان</h3>
                    <p class="text-slate-500 text-sm">ساخت سایت حرفه‌ای از صفر</p>
                </a>
                <a href="<?= site_url('tarahi-site-foroushgahi-gorgan') ?>"
                    class="soft-card rounded-2xl p-6 hover-lift block">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center mb-4">
                        <?= svg_icon('cart', 'w-5 h-5') ?></div>
                    <h3 class="font-black mb-2">طراحی سایت فروشگاهی در گرگان</h3>
                    <p class="text-slate-500 text-sm">فروشگاه آنلاین با درگاه پرداخت</p>
                </a>
                <a href="<?= site_url('seo-sherkati-gorgan') ?>" class="soft-card rounded-2xl p-6 hover-lift block">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                        <?= svg_icon('chart', 'w-5 h-5') ?></div>
                    <h3 class="font-black mb-2">سئو سایت شرکتی در گرگان</h3>
                    <p class="text-slate-500 text-sm">رتبه‌گیری برای کلمات خدماتی</p>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-white">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-black mb-5">فروشگاه آنلاین خود را در گوگل بالا ببرید</h2>
            <p class="text-slate-500 leading-8 mb-8">مشاوره اول کاملاً رایگان است. همین امروز شروع کنید.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="tel:<?= e($settings['phone'] ?? '') ?>"
                    class="btn-main bg-violet-500 hover:bg-violet-600 text-white px-8 py-4 rounded-2xl font-extrabold inline-flex items-center gap-2">
                    <?= svg_icon('phone', 'w-5 h-5') ?> مشاوره رایگان
                </a>
                <a href="<?= site_url() ?>"
                    class="btn-outline border border-slate-200 px-8 py-4 rounded-2xl font-bold inline-flex items-center gap-2">
                    <?= svg_icon('arrow', 'w-5 h-5') ?> صفحه اصلی
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
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">سئو سایت فروشگاهی در
                            گرگان چیست و چرا اهمیت دارد؟</h2>

                        <p>سئو سایت فروشگاهی در گرگان یعنی بهینه‌سازی فروشگاه اینترنتی برای اینکه در جستجوهای محلی و
                            مرتبط با خرید، بیشتر دیده شود و مشتری واقعی جذب کند. اگر یک فروشگاه آنلاین در گرگان داری،
                            فقط داشتن محصول کافی نیست؛ باید کاری کنی که وقتی کاربر عباراتی مثل «فروشگاه اینترنتی گرگان»
                            یا نام محصول + گرگان را جستجو می‌کند، سایت تو در نتایج بالا ظاهر شود.</p>

                        <p>اهمیت سئوی فروشگاهی در گرگان به این دلیل بیشتر می‌شود که رقابت محلی معمولاً از شهرهای بزرگ
                            کمتر است، اما خریدهای هدفمند و نزدیک به منطقه می‌توانند نرخ تبدیل بالاتری داشته باشند. برای
                            مثال، یک فروشگاه پوشاک اگر روی کلمات محلی، صفحات دسته‌بندی و توضیحات محصول به‌خوبی کار کند،
                            می‌تواند به‌مرور ترافیک و فروش بیشتری بگیرد.</p>

                        <p>در فروشگاه‌های اینترنتی، فقط صفحه اصلی مهم نیست؛ دسته‌بندی‌ها، صفحات محصول، محتوای اعتمادساز،
                            سرعت سایت و تجربه کاربری هم در سئو نقش مستقیم دارند. به همین دلیل سئوی فروشگاهی ترکیبی از
                            کار فنی، محتوایی و محلی است.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">مزایا و معایب سرمایه‌گذاری روی سئو سایت
                            فروشگاهی</h2>

                        <h3 class="text-xl font-black text-slate-800">مزایا</h3>
                        <p>سئو می‌تواند ترافیک پایدار، اعتماد بیشتر، هدف‌گیری دقیق مشتری محلی و بهبود تجربه کاربری را
                            برای فروشگاه ایجاد کند. برخلاف تبلیغات موقت، نتیجه سئو در صورت اجرای درست، ماندگارتر است و
                            در بلندمدت بازدهی بهتری دارد.</p>

                        <h3 class="text-xl font-black text-slate-800">معایب</h3>
                        <p>سئو زمان‌بر است، به محتوای مستمر نیاز دارد و در دسته‌های پرفروش رقابت می‌تواند شدید باشد.
                            همچنین اگر سایت از نظر فنی ضعیف باشد، حتی با محتوای خوب هم نتیجه کامل به‌دست نمی‌آید.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">نکات تخصصی برای سئوی فروشگاهی در گرگان</h2>

                        <p><strong>تحقیق کلمات کلیدی محلی:</strong> عبارت‌هایی را پیدا کن که هم نیاز خرید دارند و هم به
                            گرگان یا محله‌های آن مرتبط‌اند.</p>
                        <p><strong>بهینه‌سازی Google My Business:</strong> برای اعتمادسازی و دیده‌شدن محلی، این بخش را
                            کامل و دقیق تنظیم کن.</p>
                        <p><strong>بهینه‌سازی فنی سایت:</strong> سرعت، ساختار URL، ایندکس‌پذیری و نسخه موبایل باید
                            استاندارد باشد.</p>
                        <p><strong>تولید محتوای واقعی برای محصول:</strong> توضیحات محصول نباید کپی باشد؛ باید ارزش خرید
                            را روشن کند.</p>
                        <p><strong>لینک‌سازی محلی:</strong> دریافت لینک از منابع معتبر محلی می‌تواند اعتبار سایت را بالا
                            ببرد.</p>
                        <p><strong>تحلیل مستمر:</strong> رفتار کاربران، صفحات پربازدید و نرخ تبدیل باید مرتب بررسی شوند.
                        </p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">اشتباهات رایج و راه‌حل‌ها</h2>

                        <p>تمرکز فقط روی کلمات عمومی، نادیده گرفتن سرعت سایت، کپی‌کردن توضیحات محصول، نداشتن Google My
                            Business، ضعف محتوای وبلاگ و بهینه‌نبودن نسخه موبایل از اشتباهات رایج در فروشگاه‌های
                            اینترنتی هستند.</p>

                        <p>راه‌حل این است که از همان ابتدا، سایت را هم برای کاربر و هم برای گوگل بهینه کنی؛ یعنی هم
                            محتوای خوب داشته باشی و هم ساختار فنی درست.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">مقایسه روش‌های سئو</h2>

                        <p><strong>سئو محلی پایه:</strong> مناسب فروشگاه‌های تازه‌کار که می‌خواهند در نتایج محلی دیده
                            شوند.</p>
                        <p><strong>سئو محتوایی:</strong> مناسب فروشگاه‌هایی که می‌خواهند با مقاله و صفحات راهنما ورودی
                            بگیرند.</p>
                        <p><strong>سئو فنی پیشرفته:</strong> مناسب سایت‌هایی که از نظر ساختاری نیاز به اصلاح جدی دارند.
                        </p>
                        <p><strong>لینک‌سازی محلی و ملی:</strong> برای افزایش اعتبار دامنه و رقابت در کلمات سخت‌تر.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">چطور استراتژی مناسب را انتخاب کنیم؟</h2>

                        <p>اگر فروشگاهت تازه راه‌اندازی شده، بهتر است کار را با Google My Business و سئوی محلی شروع کنی.
                            اگر قبلاً محتوا تولید کرده‌ای، تمرکز بیشتر باید روی بهینه‌سازی محتوا باشد. و اگر رقابت بالا
                            است، ترکیب سئوی فنی و لینک‌سازی ضروری می‌شود.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">چرا سایت‌دوز؟</h2>

                        <p>سایت‌دوز با تجربه در سئو فروشگاهی، تحلیل اولیه رایگان، برنامه عملیاتی ماهانه و ترکیب سئوی
                            فنی، محتوا و GMB می‌تواند مسیر رشد فروشگاه اینترنتی تو را دقیق‌تر و سریع‌تر کند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">سوالات متداول</h2>

                        <p><strong>هزینه سئو فروشگاهی چقدر است؟</strong> به وضعیت سایت و رقابت بستگی دارد، اما معمولاً
                            به‌صورت ماهانه محاسبه می‌شود.</p>
                        <p><strong>چقدر طول می‌کشد نتیجه بگیریم؟</strong> معمولاً چند ماه زمان لازم است و نتیجه وابسته
                            به رقابت و کیفیت اجراست.</p>
                        <p><strong>Google My Business لازم است؟</strong> بله، مخصوصاً برای فروشگاه‌هایی که هدف محلی
                            دارند.</p>
                        <p><strong>سئو را خودم انجام بدهم یا برون‌سپاری کنم؟</strong> اگر زمان و تجربه کافی نداری،
                            برون‌سپاری منطقی‌تر است.</p>
                        <p><strong>بهترین پلتفرم برای فروشگاه چیست؟</strong> بستگی به نیاز پروژه دارد، اما ساختار فنی و
                            قابلیت توسعه بسیار مهم است.</p>
                        <p><strong>شبکه‌های اجتماعی چه نقشی دارند؟</strong> برای جذب توجه و پشتیبانی از برند مفیدند، اما
                            جای سئو را نمی‌گیرند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">جمع‌بندی</h2>

                        <p>سئو سایت فروشگاهی در گرگان یک فرآیند تدریجی اما سودآور است. اگر می‌خواهی فروشگاهت در نتایج
                            جستجو دیده شود و فروش واقعی بگیرد، باید از همین حالا روی بهینه‌سازی فنی، محتوایی و محلی کار
                            کنی.</p>

                        <p class="font-black text-emerald-700">اگر بخواهی، می‌توانی همین امروز با یک برنامه درست، مسیر
                            رشد فروشگاهت را شروع کنی.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">بخش سئو فنی</h2>
                        <p><strong>متا تایتل:</strong> سئو سایت فروشگاهی در گرگان | راهنمای کامل افزایش فروش</p>

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
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                        مشاهده متن کامل
                    </button>
                    <button id="seo-collapse-btn" onclick="toggleSeoContent(false)" style="display:none;"
                        class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 active:scale-95 text-slate-800 font-extrabold px-8 py-4 rounded-2xl shadow transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 15l-6-6-6 6" />
                        </svg>
                        بستن
                    </button>
                </div>

            </div>
        </div>
    </section>

</main>
<?php include __DIR__ . '/partials/footer.php'; ?>