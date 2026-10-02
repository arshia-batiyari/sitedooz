<?php
require_once __DIR__ . '/app/functions.php';
$settings = get_settings();
$posts = array_slice(published_items(get_posts()), 0, 3);
render_public_head(
    'طراحی سایت شرکتی در گرگان | سایت دوز',
    'طراحی سایت شرکتی در گرگان با ظاهر حرفه‌ای، سرعت بالا و پنل مدیریت ساده. سایت دوز متخصص طراحی سایت شرکتی برای کسب‌وکارهای گرگانی.',
    site_url('tarahi-site-sherkati-gorgan'),
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
                    class="inline-block bg-emerald-500/20 text-emerald-300 font-extrabold text-sm px-4 py-2 rounded-full mb-5">خدمات
                    سایت دوز · گرگان</span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-6">طراحی سایت شرکتی در گرگان</h1>
                <p class="text-white/80 leading-9 text-lg mb-8 max-w-xl">سایتی که اعتماد بسازد، خدمات شما را حرفه‌ای
                    معرفی کند و مشتری را به تماس تشویق کند. طراحی سایت شرکتی در گرگان با سایت دوز، از همان روز اول آماده
                    رتبه‌گیری در گوگل.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="tel:<?= e($settings['phone'] ?? '') ?>"
                        class="btn-main bg-emerald-500 hover:bg-emerald-400 text-white px-7 py-4 rounded-2xl font-extrabold inline-flex items-center gap-2">
                        <?= svg_icon('phone', 'w-5 h-5') ?> مشاوره رایگان
                    </a>
                    <a href="<?= site_url('#portfolio-sherkati') ?>"
                        class="btn-outline border border-white/40 px-7 py-4 rounded-2xl inline-flex items-center gap-2 font-bold">
                        نمونه کارها <?= svg_icon('arrow', 'w-5 h-5') ?>
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-8 max-w-sm">
                    <div class="glass-card rounded-2xl p-4 text-center"><strong class="block text-xl">۷–۱۴</strong><span
                            class="text-xs text-white/70">روز تحویل</span></div>
                    <div class="glass-card rounded-2xl p-4 text-center"><strong class="block text-xl">SSL</strong><span
                            class="text-xs text-white/70">امنیت کامل</span></div>
                    <div class="glass-card rounded-2xl p-4 text-center"><strong class="block text-xl">سئو</strong><span
                            class="text-xs text-white/70">از روز اول</span></div>
                </div>
            </div>
            <div class="relative overflow-visible">
                <div class="absolute z-20 -top-5 -left-3 glass-card rounded-2xl px-4 py-3 soft-float hidden md:block">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white/15 text-emerald-300">
                            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 5h16v10H4z" />
                                <path d="M8 19h8" />
                                <path d="M12 15v4" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs text-white/65 mb-1">طراحی سایت</div>
                            <div class="text-sm font-extrabold text-emerald-300 whitespace-nowrap">حرفه‌ای و اختصاصی
                            </div>
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
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs text-slate-500 mb-1">تحویل پروژه</div>
                            <div class="text-sm font-extrabold whitespace-nowrap">۷ تا ۱۴ روز کاری</div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual-frame" data-tilt>
                    <div class="hero-visual-chrome"><span></span><span></span><span></span><div class="hero-visual-url">sitedooz.ir</div></div>
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?= asset_url('images/hero-illustration-mobile.svg') ?>">
                        <img src="<?= asset_url('images/hero-illustration.svg') ?>"
                            width="900" height="600"
                            class="relative z-10 w-full soft-float" alt="طراحی سایت شرکتی در گرگان — سایت دوز"
                            loading="eager" />
                    </picture>
                </div>

                <div class="hero-mobile-cards mt-4 grid grid-cols-2 gap-3 md:hidden">
                    <div class="hero-mobile-card rounded-2xl bg-white/95 px-3 py-3 shadow-lg border border-slate-100">
                        <div class="flex items-start gap-2">
                            <span
                                class="hero-icon inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-4 h-4"
                                    aria-hidden="true">
                                    <path d="M4 5h16v10H4z" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8 19h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12 15v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-[11px] text-slate-500 mb-1">طراحی سایت</div>
                                <div class="text-sm font-extrabold text-emerald-600 leading-6">حرفه‌ای و اختصاصی</div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-mobile-card rounded-2xl bg-white/95 px-3 py-3 shadow-lg border border-slate-100">
                        <div class="flex items-start gap-2">
                            <span
                                class="hero-icon inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-4 h-4"
                                    aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                                    <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-[11px] text-slate-500 mb-1">تحویل پروژه</div>
                                <div class="text-sm font-extrabold text-sky-600 leading-6">۷ تا ۱۴ روز</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- چرا سایت شرکتی؟ -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-emerald-600 font-extrabold">چرا سایت شرکتی؟</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-4">طراحی سایت شرکتی در گرگان چه تفاوتی با بقیه دارد؟
                </h2>
                <p class="text-slate-500 leading-8">سایت شرکتی ویترین دیجیتال کسب‌وکار شماست. اولین چیزی که مشتری بالقوه
                    می‌بیند و اولین جایی که درباره کار با شما تصمیم می‌گیرد.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
$features = [
    ['laptop', 'اعتمادسازی فوری', 'طراحی حرفه‌ای و ظاهر شرکتی در نگاه اول اطمینان ایجاد می‌کند. مشتری می‌فهمد با یک کسب‌وکار جدی طرف است.', 'bg-emerald-50 text-emerald-600'],
    ['search', 'دیده شدن در گوگل', 'طراحی سایت شرکتی در گرگان با سایت دوز از همان ابتدا برای سئو بهینه است. ساختار هدینگ، URL و سرعت همه درست هستند.', 'bg-sky-50 text-sky-600'],
    ['phone', 'جذب مشتری ۲۴ ساعته', 'سایت شرکتی شما بعد از ساعات کاری هم کار می‌کند. مشتری هر زمان بتواند اطلاعات بگیرد و تماس بگیرد.', 'bg-teal-50 text-teal-600'],
    ['chart', 'نمایش نمونه‌کارها', 'بخش پورتفولیو حرفه‌ای، کیفیت کار شما را نشان می‌دهد و مشتری را به تصمیم‌گیری نزدیک می‌کند.', 'bg-violet-50 text-violet-600'],
    ['check', 'مدیریت آسان', 'پنل مدیریت ساده — بدون دانش فنی محتوا، تصاویر و اطلاعات را تغییر دهید.', 'bg-amber-50 text-amber-600'],
    ['speed', 'سرعت بالا', 'کد سبک و تصاویر فشرده. سایت در کمتر از ۳ ثانیه بارگذاری می‌شود.', 'bg-rose-50 text-rose-600'],
];
foreach ($features as $f): ?>
                <div class="service-card bg-white p-8 rounded-3xl shadow-sm border border-slate-100 card">
                    <div class="w-14 h-14 rounded-2xl <?= $f[3] ?> flex items-center justify-center mb-6">
                        <?= svg_icon($f[0], 'w-8 h-8') ?></div>
                    <h3 class="font-black text-xl mb-3"><?= e($f[1]) ?></h3>
                    <p class="text-sm text-gray-600 leading-8"><?= e($f[2]) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- چه صنف‌هایی؟ -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-14 items-center">
            <div>
                <span class="text-emerald-600 font-extrabold">صنف‌های هدف</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-8">طراحی سایت شرکتی در گرگان برای کدام کسب‌وکارها؟
                </h2>
                <div class="space-y-4">
                    <?php
$industries = [
    ['briefcase', 'کلینیک و مطب پزشکی', 'اعتمادسازی آنلاین، نمایش تخصص و سابقه، رزرو آنلاین نوبت.'],
    ['briefcase', 'دفاتر حقوقی و مشاوره', 'معرفی تخصص، نمایش پرونده‌های موفق و دریافت مشاوره اولیه.'],
    ['briefcase', 'شرکت‌های ساختمانی', 'نمونه‌کارهای تصویری، معرفی پروژه‌ها و ایجاد اعتماد در مشتری.'],
    ['briefcase', 'آموزشگاه‌ها و مراکز آموزشی', 'معرفی دوره‌ها، ثبت‌نام آنلاین و نمایش مدرسین.'],
    ['briefcase', 'شرکت‌های فناوری و خدمات', 'معرفی محصولات، نمایش تیم و ایجاد ارتباط با مشتریان B2B.'],
];
foreach ($industries as $ind): ?>
                    <div class="soft-card rounded-2xl p-5 flex gap-4 hover-lift">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <?= svg_icon($ind[0], 'w-5 h-5') ?></div>
                        <div>
                            <h3 class="font-black mb-1"><?= e($ind[1]) ?></h3>
                            <p class="text-slate-500 text-sm leading-7"><?= e($ind[2]) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <img src="<?= asset_url('images/process-illustration.svg') ?>" width="700" height="520" class="shadow-xl w-full"
                    alt="طراحی سایت شرکتی گرگان" loading="lazy">
            </div>
        </div>
    </section>

    <!-- فرایند -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <span class="text-emerald-600 font-extrabold">فرایند کار</span>
            <h2 class="text-3xl md:text-4xl font-black mt-3 mb-14">از سفارش تا تحویل طراحی سایت شرکتی در گرگان</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach ([['مشاوره رایگان', 'کسب‌وکار، رقبا و هدف را بررسی می‌کنیم. ساختار پیشنهادی می‌دهیم.'], ['طراحی و توسعه', 'طراحی بصری، ساختار صفحات، سرعت و سئو همه با هم.'], ['تحویل و پشتیبانی', 'سایت منتشر می‌شود. پنل مدیریت آموزش داده می‌شود. پشتیبانی ادامه دارد.']] as $i => $step): ?>
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 card">
                    <div
                        class="w-12 h-12 rounded-2xl bg-slate-900 text-white text-xl font-black flex items-center justify-center mx-auto mb-5">
                        <?= fa_number($i + 1) ?></div>
                    <h3 class="font-black text-lg mb-3"><?= e($step[0]) ?></h3>
                    <p class="text-slate-500 text-sm leading-8"><?= e($step[1]) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- لینک کلاستر -->
    <section class="py-14 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-emerald-600 font-extrabold">خدمات مرتبط</span>
                <h2 class="text-2xl font-black mt-2">سایر خدمات سایت دوز در گرگان</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-5">
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
                <a href="<?= site_url('seo-foroushgahi-gorgan') ?>" class="soft-card rounded-2xl p-6 hover-lift block">
                    <div
                        class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center mb-4">
                        <?= svg_icon('search', 'w-5 h-5') ?></div>
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
                    style="max-height:320px; transition:max-height .8s cubic-bezier(.16,1,.3,1);">

                    <div class="prose-gorgan text-slate-700 leading-9 space-y-8">

                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">طراحی سایت شرکتی در
                            گرگان — اعتبار دیجیتال شرکتت از اینجا شروع می‌شود</h2>

                        <p>اگر صاحب یک شرکت در گرگان هستی و هنوز سایت شرکتی نداری، در واقع بخش مهمی از اعتبار دیجیتال
                            خودت را از دست داده‌ای. امروز مشتری قبل از تماس، قبل از استعلام و حتی قبل از اعتماد، اول
                            سایت شرکت را بررسی می‌کند. سایت شرکتی همان جایی است که هویت، تخصص و اعتبار برندت دیده
                            می‌شود.</p>

                        <p>سایت دوز در <strong>طراحی سایت شرکتی در گرگان</strong> تخصص دارد و سایت‌هایی می‌سازد که فقط
                            زیبا نیستند؛ بلکه اعتماد می‌سازند، لید تولید می‌کنند و برای سئو محلی هم آماده‌اند. اگر هدفت
                            این است که شرکتت در گوگل جدی‌تر دیده شود، سایت شرکتی اولین قدم درست است.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">چرا هر شرکت در گرگان به سایت شرکتی نیاز
                            دارد؟</h2>

                        <p>در بازاری مثل گرگان، رقابت فقط روی قیمت نیست؛ روی اعتماد و اعتبار هم هست. وقتی کاربر نام
                            شرکتت را جستجو می‌کند، نبودن سایت یعنی یک علامت سوال بزرگ. سایت شرکتی باعث می‌شود شرکتت
                            حرفه‌ای‌تر دیده شود، خدماتت شفاف‌تر معرفی شود و مسیر ارتباط با مشتری راحت‌تر شود.</p>

                        <p>برای خیلی از کسب‌وکارها، سایت شرکتی فقط یک صفحه اینترنتی نیست؛ یک ابزار جدی برای معرفی برند،
                            جذب مشتری، نمایش نمونه‌کارها و دریافت تماس‌های هدفمند است.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">سایت شرکتی دقیقاً چه چیزهایی باید داشته
                            باشد؟</h2>

                        <p><strong>صفحه اصلی:</strong> معرفی سریع و حرفه‌ای شرکت</p>
                        <p><strong>درباره ما:</strong> روایت اعتمادساز از سابقه، تیم و مسیر رشد</p>
                        <p><strong>خدمات:</strong> توضیح شفاف خدمات شرکت</p>
                        <p><strong>نمونه‌کارها یا پروژه‌ها:</strong> نمایش تجربه و کیفیت واقعی</p>
                        <p><strong>تماس با ما:</strong> راه ارتباط ساده و سریع</p>
                        <p><strong>بلاگ یا مقالات:</strong> برای سئو و جذب ورودی از گوگل</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">طراحی سایت شرکتی در گرگان چه مزایایی دارد؟
                        </h2>

                        <h3 class="text-xl font-black text-slate-800">اعتمادسازی</h3>
                        <p>اولین مزیت سایت شرکتی این است که اعتبار می‌سازد. مشتری وقتی با یک وب‌سایت حرفه‌ای روبه‌رو
                            می‌شود، راحت‌تر به شرکتت اعتماد می‌کند.</p>

                        <h3 class="text-xl font-black text-slate-800">دسترسی ۲۴ ساعته</h3>
                        <p>سایت شرکتی همیشه فعال است؛ حتی وقتی دفتر بسته است، مشتری می‌تواند با شرکت آشنا شود، خدمات را
                            بررسی کند و تماس بگیرد.</p>

                        <h3 class="text-xl font-black text-slate-800">افزایش لید و تماس</h3>
                        <p>یک سایت شرکتی خوب، بازدیدکننده را به مشتری بالقوه تبدیل می‌کند؛ مخصوصاً وقتی فرم تماس،
                            دکمه‌های CTA و مسیر ارتباطی واضح داشته باشد.</p>

                        <h3 class="text-xl font-black text-slate-800">سئو محلی در گرگان</h3>
                        <p>وقتی سایت برای عبارت‌هایی مثل «طراحی سایت شرکتی در گرگان» بهینه شود، شانس دیده‌شدن در
                            جستجوهای محلی چند برابر می‌شود.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">مراحل طراحی سایت شرکتی در گرگان</h2>

                        <h3 class="text-xl font-black text-slate-800">مرحله اول: شناخت کسب‌وکار</h3>
                        <p>در ابتدا نوع شرکت، مخاطب هدف، خدمات اصلی و اهداف سایت بررسی می‌شود.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله دوم: طراحی ساختار</h3>
                        <p>نقشه صفحات و مسیر حرکت کاربر مشخص می‌شود تا سایت هم برای کاربر و هم برای گوگل قابل‌فهم باشد.
                        </p>

                        <h3 class="text-xl font-black text-slate-800">مرحله سوم: طراحی رابط کاربری</h3>
                        <p>ظاهر سایت باید مدرن، تمیز، قابل‌اعتماد و متناسب با هویت برند باشد.</p>

                        <h3 class="text-xl font-black text-slate-800">مرحله چهارم: توسعه و بهینه‌سازی</h3>
                        <p>کدنویسی، سرعت، امنیت، موبایل‌فرست بودن و سئو فنی در این مرحله اجرا می‌شود.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">هزینه طراحی سایت شرکتی در گرگان</h2>

                        <p>هزینه طراحی سایت شرکتی در گرگان به امکانات، تعداد صفحات، سطح طراحی و نوع توسعه بستگی دارد.
                            اگر بخواهی فقط یک سایت معرفی ساده داشته باشی، هزینه کمتر است؛ اما اگر طراحی اختصاصی، سئو
                            اولیه، چند زبانگی، فرم‌های پیشرفته یا امکانات ویژه بخواهی، قیمت بالاتر می‌رود.</p>

                        <p>برای اینکه هزینه دقیق مشخص شود، باید نیازهای واقعی شرکت بررسی شود. سایت دوز بعد از بررسی
                            کسب‌وکار، بهترین پیشنهاد را متناسب با بودجه و هدف ارائه می‌کند.</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">چرا سایت دوز برای طراحی سایت شرکتی در گرگان؟
                        </h2>

                        <p><strong>طراحی اختصاصی:</strong> بدون قالب تکراری و ضعیف</p>
                        <p><strong>سایت سئوپذیر:</strong> از ابتدا آماده برای رتبه گرفتن</p>
                        <p><strong>تجربه کاربری خوب:</strong> مسیر ساده برای ارتباط و تبدیل کاربر</p>
                        <p><strong>مناسب موبایل:</strong> نمایش درست در همه دستگاه‌ها</p>
                        <p><strong>پشتیبانی واقعی:</strong> بعد از تحویل هم کنار شما هستیم</p>

                        <h2 class="text-2xl font-black text-slate-900 mt-8">جمع‌بندی</h2>

                        <p>اگر می‌خواهی شرکتت در گرگان حرفه‌ای‌تر دیده شود، سایت شرکتی نقطه شروع توست. یک سایت درست، فقط
                            اطلاعات نمی‌دهد؛ اعتماد می‌سازد، برند را تقویت می‌کند و مسیر جذب مشتری را هموار می‌کند.</p>

                        <p class="font-black text-emerald-700">سایت دوز، همراه مطمئن شما برای طراحی سایت شرکتی در گرگان.
                        </p>

                    </div>

                    <!-- fade overlay هنگام بسته بودن -->
                    <div id="seo-fade" class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none"
                        style="background:linear-gradient(to bottom,transparent,#fff);transition:opacity .5s ease;">
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
    </section>

    <!-- CTA -->
    <section class="py-20 bg-white">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-black mb-5">طراحی سایت شرکتی در گرگان را همین امروز شروع کنید</h2>
            <p class="text-slate-500 leading-8 mb-8">مشاوره اول کاملاً رایگان است. کافی است با ما تماس بگیرید.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="tel:<?= e($settings['phone'] ?? '') ?>"
                    class="btn-main bg-emerald-500 hover:bg-emerald-600 text-white px-8 py-4 rounded-2xl font-extrabold inline-flex items-center gap-2">
                    <?= svg_icon('phone', 'w-5 h-5') ?> مشاوره رایگان
                </a>
                <a href="<?= site_url() ?>"
                    class="btn-outline border border-slate-200 px-8 py-4 rounded-2xl font-bold inline-flex items-center gap-2">
                    <?= svg_icon('arrow', 'w-5 h-5') ?> بازگشت به صفحه اصلی
                </a>
            </div>
        </div>
    </section>

</main>
<?php include __DIR__ . '/partials/footer.php'; ?>