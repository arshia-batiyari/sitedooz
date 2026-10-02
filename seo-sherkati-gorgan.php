<?php
require_once __DIR__ . '/app/functions.php';
$settings = get_settings();
render_public_head(
    'سئو سایت شرکتی در گرگان | سایت دوز',
    'سئو سایت شرکتی در گرگان با نتایج واقعی و قابل اندازه‌گیری. رتبه‌گیری در گوگل برای کلمات کلیدی کسب‌وکار شما در گرگان. مشاوره رایگان با سایت دوز.',
    site_url('seo-sherkati-gorgan'),
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
                    class="inline-block bg-teal-500/20 text-teal-300 font-extrabold text-sm px-4 py-2 rounded-full mb-5">سئو
                    سایت شرکتی · گرگان</span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-6">سئو سایت شرکتی در گرگان</h1>
                <p class="text-white/80 leading-9 text-lg mb-8 max-w-xl">وقتی مشتری گرگانی در گوگل دنبال خدمات شما
                    می‌گردد، سایتتان اول باشد. سئو سایت شرکتی در گرگان با سایت دوز — نتایج واقعی، گزارش ماهانه، بدون
                    تعهد پنهان.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="tel:<?= e($settings['phone'] ?? '') ?>"
                        class="btn-main bg-teal-500 hover:bg-teal-400 text-white px-7 py-4 rounded-2xl font-extrabold inline-flex items-center gap-2">
                        <?= svg_icon('phone', 'w-5 h-5') ?> مشاوره رایگان
                    </a>
                    <a href="<?= site_url('#seo-results') ?>"
                        class="btn-outline border border-white/40 px-7 py-4 rounded-2xl inline-flex items-center gap-2 font-bold">
                        نتایج واقعی <?= svg_icon('arrow', 'w-5 h-5') ?>
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-8 max-w-sm">
                    <div class="glass-card rounded-2xl p-4 text-center"><strong class="block text-xl">۳–۶</strong><span
                            class="text-xs text-white/70">ماه نتیجه</span></div>
                    <div class="glass-card rounded-2xl p-4 text-center"><strong class="block text-xl">گوگل</strong><span
                            class="text-xs text-white/70">صفحه اول</span></div>
                    <div class="glass-card rounded-2xl p-4 text-center"><strong
                            class="block text-xl">گزارش</strong><span class="text-xs text-white/70">ماهانه</span></div>
                </div>
            </div>
            <div class="relative overflow-visible">
                <div class="absolute z-20 -top-5 -left-3 glass-card rounded-2xl px-4 py-3 soft-float hidden md:block">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white/15 text-teal-300">
                            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M20 20l-3.5-3.5" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs text-white/65 mb-1">سئو شرکتی</div>
                            <div class="text-sm font-extrabold text-teal-300 whitespace-nowrap">رتبه اول گوگل</div>
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
                            <div class="text-sm font-extrabold whitespace-nowrap">از رتبه ۴۱ به رتبه ۲</div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual-frame" data-tilt>
                    <div class="hero-visual-chrome"><span></span><span></span><span></span><div class="hero-visual-url">sitedooz.ir</div></div>
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?= asset_url('images/hero-illustration-mobile.svg') ?>">
                        <img src="<?= asset_url('images/hero-illustration.svg') ?>"
                            width="900" height="600"
                            class="relative z-10 w-full soft-float" alt="سئو سایت شرکتی در گرگان — سایت دوز"
                            loading="eager" />
                    </picture>
                </div>

                <div class="hero-mobile-cards mt-4 grid grid-cols-2 gap-3 md:hidden">
                    <div class="hero-mobile-card rounded-2xl bg-white/95 px-3 py-3 shadow-lg border border-slate-100">
                        <div class="flex items-start gap-2">
                            <span
                                class="hero-icon inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-4 h-4"
                                    aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" />
                                    <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-[11px] text-slate-500 mb-1">سئو شرکتی</div>
                                <div class="text-sm font-extrabold text-teal-600 leading-6">رتبه اول گوگل</div>
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
                                <div class="text-sm font-extrabold text-emerald-600 leading-6">رتبه ۴۱ → ۲</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- چرا سئو؟ -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-teal-600 font-extrabold">چرا سئو شرکتی؟</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">سئو سایت شرکتی در گرگان چطور مشتری می‌آورد؟</h2>
                <p class="text-slate-500 leading-8">وقتی سایت شرکتی شما در گوگل بالا باشد، مشتری‌هایی جذب می‌کنید که
                    دقیقاً دنبال خدمات شما هستند — نه تبلیغ پولی، نه مزاحمت، بلکه مشتری آماده تصمیم‌گیری.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
$cards = [
    ['chart', 'مشتری هدفمند', 'کسی که «حسابداری گرگان» جستجو می‌کند دقیقاً دنبال شماست. سئو این مشتری را به سایتتان می‌رساند.', 'bg-teal-50 text-teal-600'],
    ['star', 'اعتبار بیشتر', 'بودن در نتایج اول گوگل اعتبار شما را در نگاه مشتری چند برابر می‌کند. رقیبی که پایین‌تر است کمتر دیده می‌شود.', 'bg-emerald-50 text-emerald-600'],
    ['speed', 'رشد پایدار', 'برخلاف تبلیغات که با اتمام بودجه تمام می‌شود، رتبه سئو ماه‌ها و سال‌ها می‌ماند.', 'bg-sky-50 text-sky-600'],
    ['search', 'کلمات کلیدی محلی', '«وکیل گرگان»، «کلینیک دندانپزشکی گرگان» — کلماتی که مشتری محلی واقعی جستجو می‌کند.', 'bg-violet-50 text-violet-600'],
    ['check', 'بدون هزینه کلیک', 'سئو برخلاف تبلیغات گوگل، به ازای هر کلیک هزینه ندارد. ترافیک رایگان و مستمر.', 'bg-amber-50 text-amber-600'],
    ['laptop', 'گزارش شفاف', 'هر ماه گزارش رتبه‌ها می‌بینید. می‌دانید سرمایه‌تان کجا خرج می‌شود و چه نتیجه‌ای داده.', 'bg-rose-50 text-rose-600'],
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

    <!-- بخش‌های سئو -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-14 items-center">
            <div>
                <span class="text-teal-600 font-extrabold">خدمات سئو شرکتی</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-8">سئو سایت شرکتی در گرگان شامل چه کارهایی است؟</h2>
                <div class="space-y-4">
                    <?php
$services = [
    ['تحقیق کلمات کلیدی', 'پیدا کردن کلماتی که مشتری گرگانی واقعاً جستجو می‌کند. پایه همه کارهای بعدی.'],
    ['سئو داخلی', 'بهینه‌سازی تایتل، متا، هدینگ‌ها، محتوا و لینک‌های داخلی صفحات سایت.'],
    ['سئو فنی', 'سرعت، موبایل، SSL، نقشه سایت و ساختار URL — فاکتورهای فنی که گوگل اهمیت می‌دهد.'],
    ['سئو محلی', 'گوگل مای‌بیزینس، نام شهر در محتوا و جمع‌آوری نظرات مثبت مشتریان.'],
    ['تولید محتوا', 'بلاگ هدفمند با موضوعات مرتبط با کسب‌وکار شما برای جذب ترافیک ارگانیک.'],
];
foreach ($services as $i => $svc): ?>
                    <div class="soft-card rounded-2xl p-5 flex gap-4 hover-lift">
                        <div
                            class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 font-black flex items-center justify-center shrink-0">
                            <?= fa_number($i + 1) ?></div>
                        <div>
                            <h3 class="font-black mb-1"><?= e($svc[0]) ?></h3>
                            <p class="text-slate-500 text-sm leading-7"><?= e($svc[1]) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <img src="<?= asset_url('images/process-illustration.svg') ?>" width="700" height="520" class="shadow-xl w-full"
                    alt="سئو سایت شرکتی گرگان" loading="lazy">
            </div>
        </div>
    </section>

    <!-- نتایج واقعی -->
    <section id="seo-results" class="py-20 bg-slate-950 text-white">
        <div class="absolute inset-0 opacity-20 pointer-events-none"
            style="background:radial-gradient(circle at 20% 50%,#10b981,transparent 40%),radial-gradient(circle at 80% 50%,#38bdf8,transparent 40%)">
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <div class="text-center mb-14">
                <span class="text-teal-300 font-extrabold">نتایج واقعی</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3">سئو سایت شرکتی در گرگان — ارقام واقعی</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php
$results = [
    ['طراحی سایت شرکتی', '۴۱', '۲'],
    ['شرکت طراحی سایت', '۳۳', '۹'],
    ['خدمات سئو سایت', '۴۴', '۷'],
];
foreach ($results as $r): ?>
                <div class="bg-white/10 border border-white/20 rounded-3xl p-7 text-center backdrop-blur-sm hover-lift">
                    <div class="text-white/60 text-sm mb-4 font-bold"><?= e($r[0]) ?></div>
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-3xl font-black text-slate-400"><?= e($r[1]) ?></div>
                            <div class="text-xs text-white/50 mt-1">قبل</div>
                        </div>
                        <svg class="w-6 h-6 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M5 12h14M13 5l7 7-7 7" />
                        </svg>
                        <div class="text-center">
                            <div class="text-3xl font-black text-teal-400"><?= e($r[2]) ?></div>
                            <div class="text-xs text-white/50 mt-1">الان</div>
                        </div>
                    </div>
                    <div
                        class="text-xs text-emerald-300 font-bold bg-emerald-500/20 rounded-full px-3 py-1 inline-block">
                        صفحه اول گوگل</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- صنف‌ها -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-teal-600 font-extrabold">صنف‌های هدف</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">سئو سایت شرکتی در گرگان برای کدام کسب‌وکارها؟</h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
$sectors = [
    ['کلینیک و مطب پزشکی', '«دندانپزشک گرگان»، «کلینیک زیبایی گرگان» — مشتری قبل از مراجعه گوگل می‌کند.'],
    ['دفاتر حقوقی و مشاوره', '«وکیل گرگان»، «مشاوره حقوقی گرگان» — اعتماد از طریق رتبه اول شروع می‌شود.'],
    ['شرکت‌های ساختمانی', '«پیمانکار گرگان»، «شرکت ساختمانی گرگان» — رقابت زیاد، مزیت زودشروع‌کردن.'],
    ['آموزشگاه‌ها', '«آموزشگاه زبان گرگان»، «کلاس موسیقی گرگان» — دانش‌آموز بیشتر با سئو هدفمند.'],
    ['رستوران و کافه', '«رستوران گرگان»، «کافه در گرگان» — گردشگران و مشتریان محلی.'],
    ['شرکت‌های خدماتی', 'هر خدمتی که مشتریانش در گرگان جستجو می‌کنند از سئو سود می‌برد.'],
];
foreach ($sectors as $s): ?>
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 card hover-lift">
                    <h3 class="font-black text-lg mb-3"><?= e($s[0]) ?></h3>
                    <p class="text-slate-500 text-sm leading-7"><?= e($s[1]) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- لینک کلاستر -->
    <section class="py-14 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-teal-600 font-extrabold">خدمات مرتبط</span>
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
                <a href="<?= site_url('seo-foroushgahi-gorgan') ?>" class="soft-card rounded-2xl p-6 hover-lift block">
                    <div
                        class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center mb-4">
                        <?= svg_icon('chart', 'w-5 h-5') ?></div>
                    <h3 class="font-black mb-2">سئو سایت فروشگاهی در گرگان</h3>
                    <p class="text-slate-500 text-sm">رتبه‌گیری برای کلمات محصولات</p>
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

                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">سئو سایت شرکتی در گرگان
                            — چرا سایتت دیده نمی‌شود و چطور این را تغییر بدهی</h2>

                        <p>اگر سایت شرکتی داری اما در گوگل دیده نمی‌شود، مشکل معمولاً یکی از سه چیز است: سئوی فنی ضعیف،
                            محتوای کم‌ارزش، یا نبود استراتژی محلی. سئو سایت شرکتی در گرگان فقط بالا آوردن چند کلمه نیست؛
                            ترکیبی است از <strong>سئوی فنی</strong>، <strong>تولید محتوای محلی</strong> و
                            <strong>بک‌لینک‌سازی هدفمند</strong>.</p>

                        <p>در بیشتر پروژه‌ها، زمان نتیجه‌گیری سئو بین <strong>۳ تا ۶ ماه</strong> است و هزینه ماهانه هم
                            معمولاً بین <strong>۲ تا ۸ میلیون تومان</strong> قرار می‌گیرد. این عددها به رقابت، وضعیت
                            فعلی سایت و تعداد صفحات هدف بستگی دارند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">سئوی محلی با سئوی عمومی چه فرقی دارد؟</h2>

                        <p>در سئوی محلی، هدف فقط دیده‌شدن در نتایج عمومی نیست؛ بلکه دیده‌شدن برای کاربرانی است که واقعاً
                            به خدمات تو در همان منطقه نیاز دارند. در گرگان، رقابت معمولاً از شهرهای بزرگ کمتر است و همین
                            باعث می‌شود با تمرکز روی کلمات محلی، بتوانی با هزینه کمتر و نرخ تبدیل بهتر نتیجه بگیری.</p>

                        <p>به همین دلیل، سئوی محلی برای سایت‌های شرکتی در گرگان معمولاً بازدهی بهتری نسبت به سئوی عمومی
                            دارد؛ چون مخاطب دقیق‌تر است و احتمال تماس یا ثبت درخواست بیشتر می‌شود.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">ارکان اصلی موفقیت سئو</h2>

                        <p><strong>سئوی فنی:</strong> سرعت، ساختار، ایندکس‌پذیری و سلامت فنی سایت</p>
                        <p><strong>سئوی محتوایی:</strong> تولید محتوای مفید، هدفمند و منظم</p>
                        <p><strong>سئوی محلی:</strong> تمرکز بر گرگان و عبارات منطقه‌ای</p>
                        <p><strong>بک‌لینک‌سازی:</strong> تقویت اعتبار دامنه با لینک‌های هدفمند و سالم</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">مراحل اجرای سئو سایت شرکتی در گرگان</h2>

                        <h3 class="text-xl font-black text-slate-800">مرحله اول: تحقیق کلمات کلیدی</h3>
                        <p>ابتدا باید مشخص شود کاربران گرگانی چه عباراتی را جستجو می‌کنند و کدام کلمات بیشترین ارزش
                            تجاری را دارند.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله دوم: بهینه‌سازی فنی</h3>
                        <p>سرعت سایت، ساختار URL، تگ‌ها، داده‌های ساختاریافته و خطاهای فنی باید اصلاح شوند.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله سوم: بهینه‌سازی صفحات موجود</h3>
                        <p>صفحات خدمات، درباره ما و تماس باید از نظر محتوا، تیترها و CTA بهینه شوند.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله چهارم: تولید محتوای منظم</h3>
                        <p>مقاله‌ها و صفحات هدف باید به‌صورت پیوسته منتشر شوند تا سایت در گوگل زنده و فعال بماند.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله پنجم: ثبت و تکمیل Google Business Profile
                        </h3>
                        <p>برای سئوی محلی، Google Business Profile یکی از پایه‌های اصلی است و نباید نادیده گرفته شود.
                        </p>

                        <h3 class="text-xl font-black text-slate-800">مرحله ششم: بک‌لینک‌سازی هدفمند</h3>
                        <p>لینک‌سازی باید طبیعی، مرتبط و از منابع معتبر باشد؛ نه خرید لینک‌های بی‌کیفیت.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله هفتم: پایش و بهینه‌سازی مستمر</h3>
                        <p>سئو یک کار یک‌باره نیست؛ باید نتایج بررسی شوند و استراتژی بر اساس داده‌ها اصلاح شود.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">هزینه سئو سایت شرکتی در گرگان</h2>

                        <p>بسته پایه معمولاً بین <strong>۲ تا ۳ میلیون تومان</strong>، بسته حرفه‌ای بین <strong>۴ تا ۶
                                میلیون تومان</strong> و بسته جامع بین <strong>۶ تا ۸+ میلیون تومان</strong> در ماه است.
                            این هزینه‌ها بسته به وضعیت سایت، رقابت و گستره کار متغیر هستند.</p>

                        <p>سئو را نباید هزینه‌ی کوتاه‌مدت دید؛ این یک سرمایه‌گذاری بلندمدت است که به مرور، ترافیک پایدار
                            و مشتری هدفمند ایجاد می‌کند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">مزایا و معایب سئو</h2>

                        <h3 class="text-xl font-black text-slate-800">مزایا</h3>
                        <p>ترافیک پایدار، اعتمادسازی، هدف‌گیری محلی، بازگشت سرمایه و تقویت برند از مهم‌ترین مزایای سئو
                            هستند.</p>

                        <h3 class="text-xl font-black text-slate-800">معایب</h3>
                        <p>سئو نتیجه فوری ندارد، به رقابت وابسته است، نیاز به محتوای مستمر دارد و تحت تأثیر تغییرات
                            الگوریتمی قرار می‌گیرد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">نکات تخصصی که باید جدی بگیری</h2>

                        <p><strong>روی Long-tail محلی تمرکز کن:</strong> عباراتی که هم شهر و هم خدمت را دارند، معمولاً
                            سریع‌تر نتیجه می‌دهند.</p>
                        <p><strong>هر خدمت را جداگانه بهینه کن:</strong> برای هر سرویس یک صفحه هدف داشته باش.</p>
                        <p><strong>نظرات گوگل مهم‌اند:</strong> Reviewها روی اعتماد و سئوی محلی اثر دارند.</p>
                        <p><strong>سرعت سایت را جدی بگیر:</strong> سرعت پایین، هم کاربر را فراری می‌دهد و هم رتبه را
                            ضعیف می‌کند.</p>
                        <p><strong>رقبا را تحلیل کن:</strong> بدون شناخت رقبا، استراتژی سئو ناقص می‌ماند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">اشتباهات رایج در سئو سایت شرکتی</h2>

                        <p>انتظار نتیجه فوری، تولید محتوای کپی یا سطحی، نادیده گرفتن Google Business Profile، خرید
                            بک‌لینک نامعتبر و عدم پایش نتایج از رایج‌ترین اشتباه‌ها هستند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">چطور متخصص سئو مناسب را انتخاب کنیم؟</h2>

                        <p>نمونه‌کار واقعی، شفافیت در گزارش ماهانه، پایبندی به White Hat، تجربه در سئوی محلی و اعلام
                            زمان واقع‌بینانه از معیارهای مهم انتخاب متخصص سئو هستند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">سوالات متداول</h2>

                        <p><strong>سئو چقدر زمان می‌برد؟</strong> معمولاً بین ۳ تا ۶ ماه، بسته به رقابت و وضعیت سایت.
                        </p>
                        <p><strong>هزینه سئو چقدر است؟</strong> بسته به سطح کار، از ۲ تا ۸+ میلیون تومان در ماه متغیر
                            است.</p>
                        <p><strong>آیا تبلیغات جای سئو را می‌گیرد؟</strong> نه، تبلیغات موقت است اما سئو اثر بلندمدت
                            دارد.</p>
                        <p><strong>وبلاگ برای سئو لازم است؟</strong> بله، وبلاگ یکی از ابزارهای مهم برای جذب ورودی و
                            اعتبار است.</p>
                        <p><strong>سئوی محلی برای شرکت کوچک هم مفید است؟</strong> بله، حتی بیشتر چون رقابت هدفمندتر و
                            نتیجه‌گیری سریع‌تر است.</p>
                        <p><strong>بک‌لینک چقدر مهم است؟</strong> مهم است، اما فقط اگر طبیعی و معتبر باشد.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">جمع‌بندی</h2>

                        <p>سئو سایت شرکتی در گرگان یک سرمایه‌گذاری بلندمدت است، نه یک اقدام سریع و مقطعی. اگر بخواهی در
                            نتایج گوگل دیده شوی، باید روی فنی، محتوا، محلی‌سازی و لینک‌سازی به‌صورت هم‌زمان کار کنی.</p>

                        <p class="font-black text-emerald-700">سایت دوز همراه توست تا سایت شرکتی‌ات در گرگان بهتر دیده
                            شود و مشتری هدفمند جذب کند.</p>

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

    <!-- CTA -->
    <section class="py-20 bg-white">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-black mb-5">سئو سایت شرکتی در گرگان را همین امروز شروع کنید</h2>
            <p class="text-slate-500 leading-8 mb-8">مشاوره اول کاملاً رایگان است. رتبه‌های رقیب منتظر نمی‌مانند.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="tel:<?= e($settings['phone'] ?? '') ?>"
                    class="btn-main bg-teal-500 hover:bg-teal-600 text-white px-8 py-4 rounded-2xl font-extrabold inline-flex items-center gap-2">
                    <?= svg_icon('phone', 'w-5 h-5') ?> مشاوره رایگان
                </a>
                <a href="<?= site_url() ?>"
                    class="btn-outline border border-slate-200 px-8 py-4 rounded-2xl font-bold inline-flex items-center gap-2">
                    <?= svg_icon('arrow', 'w-5 h-5') ?> صفحه اصلی
                </a>
            </div>
        </div>
    </section>

</main>
<?php include __DIR__ . '/partials/footer.php'; ?>