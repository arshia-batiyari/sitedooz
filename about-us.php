<?php
require_once __DIR__ . '/app/functions.php';

$settings = get_settings();
$phone = $settings['phone'] ?? '';
$faqs = [
    ['question' => 'هزینه طراحی سایت توسط شرکت طراحی سایت گرگان چقدر است؟', 'answer' => 'هزینه بستگی به نوع سایت دارد. یک سایت معرفی خدمات ساده هزینه کمتری دارد، در حالی که فروشگاه اینترنتی یا سایت با امکانات پیچیده، هزینه بیشتری می‌طلبد. بهتر است از چند **شرکت طراحی سایت گرگان** پیش‌فاکتور بگیرید و مقایسه کنید.'],
    ['question' => 'چقدر طول می‌کشد یک شرکت طراحی سایت گرگان پروژه را تحویل دهد؟', 'answer' => 'بسته به پیچیدگی پروژه، از ۲ هفته تا ۲ ماه متغیر است. سایت‌های ساده سریع‌تر و فروشگاه‌های بزرگ زمان بیشتری نیاز دارند.'],
    ['question' => 'آیا شرکت طراحی سایت گرگان باید سئو را هم انجام دهد؟', 'answer' => 'حداقل باید ساختار فنی سایت برای سئو آماده باشد. سئوی محتوایی و رقابتی معمولاً خدمت جداگانه‌ای است که می‌توانید از همان **شرکت طراحی سایت گرگان** یا یک تیم تخصصی سئو بگیرید.'],
    ['question' => 'بعد از تحویل سایت، پشتیبانی چطور انجام می‌شود؟', 'answer' => 'اکثر شرکت‌های حرفه‌ای بسته‌های پشتیبانی ماهانه یا سالانه ارائه می‌دهند. حتماً پیش از شروع همکاری با **شرکت طراحی سایت گرگان** درباره نوع و مدت پشتیبانی سؤال کنید.'],
    ['question' => 'چطور بفهمم یک شرکت طراحی سایت گرگان معتبر است؟', 'answer' => 'نمونه‌کارها را بررسی کنید، نظرات مشتریان قبلی را بخوانید و در صورت امکان با چند مشتری قبلی صحبت کنید.'],
    ['question' => 'آیا می‌توان سایت را خودم مدیریت کنم یا باید همیشه به شرکت مراجعه کنم؟', 'answer' => 'اگر سایت با سیستم مدیریت محتوا مثل وردپرس ساخته شود، معمولاً می‌توانید محتوا را خودتان مدیریت کنید. این موضوع را از ابتدا با **شرکت طراحی سایت گرگان** هماهنگ کنید.']
];
$alternativeHeadlines = ['شرکت طراحی سایت گرگان؛ چطور بهترین را انتخاب کنیم؟', 'معرفی معیارهای انتخاب شرکت طراحی سایت گرگان', 'شرکت طراحی سایت گرگان؛ راهنمای کامل برای کسب‌وکارها', 'بهترین شرکت طراحی سایت گرگان کدام است؟', 'همه چیز درباره انتخاب شرکت طراحی سایت گرگان'];
$keywords = ['طراحی وب‌سایت', 'سئو سایت', 'طراحی سایت شرکتی', 'طراحی فروشگاه اینترنتی', 'هاستینگ', 'دامنه', 'طراحی سایت واکنش‌گرا', 'بهینه‌سازی سایت', 'طراحی رابط کاربری', 'توسعه وب', 'برنامه‌نویسی سایت', 'طراحی سایت وردپرسی'];

render_public_head(
    'درباره ما',
    'به دنبال یک شرکت طراحی سایت گرگان معتبر و حرفه‌ای هستید؟ در این راهنما یاد می‌گیرید چطور بهترین شرکت طراحی سایت گرگان را برای کسب‌وکارتان انتخاب کنید.',
    site_url('about-us'),
    'images/IMG_6461.JPG',
    true
);

include __DIR__ . '/partials/header.php';

$schema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Article',
            '@id' => absolute_url('about-us') . '#article',
            'mainEntityOfPage' => ['@id' => absolute_url('about-us') . '#webpage'],
            'headline' => 'شرکت طراحی سایت گرگان؛ راهنمای انتخاب بهترین شریک برای ساخت وب‌سایت',
            'alternativeHeadline' => $alternativeHeadlines,
            'description' => 'به دنبال یک شرکت طراحی سایت گرگان معتبر و حرفه‌ای هستید؟ در این راهنما یاد می‌گیرید چطور بهترین شرکت طراحی سایت گرگان را برای کسب‌وکارتان انتخاب کنید.',
            'keywords' => implode(', ', $keywords),
            'inLanguage' => 'fa-IR',
            'image' => absolute_url('images/IMG_6461.JPG'),
            'author' => ['@type' => 'Organization', 'name' => APP_NAME],
            'publisher' => [
                '@type' => 'Organization',
                'name' => APP_NAME,
                'logo' => ['@type' => 'ImageObject', 'url' => absolute_url('images/logo-optimized.webp')],
            ],
        ],
        [
            '@type' => 'AboutPage',
            '@id' => absolute_url('about-us') . '#webpage',
            'url' => absolute_url('about-us'),
            'name' => 'شرکت طراحی سایت گرگان؛ راهنمای انتخاب بهترین شریک برای ساخت وب‌سایت',
            'description' => 'به دنبال یک شرکت طراحی سایت گرگان معتبر و حرفه‌ای هستید؟ در این راهنما یاد می‌گیرید چطور بهترین شرکت طراحی سایت گرگان را برای کسب‌وکارتان انتخاب کنید.',
            'inLanguage' => 'fa-IR',
            'mainEntity' => ['@id' => absolute_url() . '#localbusiness'],
        ],
        [
            '@type' => 'LocalBusiness',
            '@id' => absolute_url() . '#localbusiness',
            'name' => APP_NAME,
            'url' => absolute_url(),
            'telephone' => $phone,
            'image' => absolute_url('images/logo-optimized.webp'),
            'areaServed' => [
                ['@type' => 'City', 'name' => 'گرگان'],
                ['@type' => 'AdministrativeArea', 'name' => 'گلستان'],
            ],
            'knowsAbout' => $keywords,
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(static function (array $faq): array {
                return [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => str_replace('**', '', $faq['answer'])],
                ];
            }, $faqs),
        ],
    ],
];
?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

<main style="padding-top:4rem;">
  <section class="hero-bg text-white pt-24 pb-16 lg:pt-32 lg:pb-20 relative overflow-hidden">
        <div class="hero-grid-pattern"></div>
        <div class="hero-orb hero-orb--1"></div>
        <div class="hero-orb hero-orb--2"></div>
        <div class="scroll-cue"><span>بیشتر ببین</span><span class="scroll-cue-icon"></span></div>
    <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-[1.15fr_.85fr] gap-12 items-center relative z-10">
      <div>
        <nav class="text-sm text-white/60 mb-6" aria-label="مسیر صفحه">
          <a href="<?= site_url() ?>" class="hover:text-white transition">خانه</a><span class="mx-2">/</span><span class="text-white/90">درباره ما</span>
        </nav>
        <span class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-200 border border-emerald-300/20 font-extrabold text-sm px-4 py-2 rounded-full mb-5"><?= svg_icon('sparkles', 'w-4 h-4') ?> شرکت طراحی سایت در گرگان</span>
        <h1 class="text-4xl md:text-5xl font-black leading-[1.5] mb-6">شرکت طراحی سایت گرگان؛ راهنمای انتخاب بهترین شریک برای ساخت وب‌سایت</h1>
        <p class="text-white/80 leading-9 text-lg mb-8 max-w-3xl">به دنبال یک شرکت طراحی سایت گرگان معتبر و حرفه‌ای هستید؟ در این راهنما یاد می‌گیرید چطور بهترین شرکت طراحی سایت گرگان را برای کسب‌وکارتان انتخاب کنید.</p>
        <div class="flex flex-wrap gap-4">
          <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>#consultation-form" class="btn-main bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-7 py-4 rounded-2xl font-extrabold inline-flex items-center gap-2"><?= svg_icon('phone', 'w-5 h-5') ?> مشاوره رایگان</a>
          <a href="<?= site_url('#portfolio') ?>" class="btn-outline border border-white/35 px-7 py-4 rounded-2xl inline-flex items-center gap-2 font-bold">مشاهده نمونه‌کارها <?= svg_icon('arrow', 'w-5 h-5') ?></a>
        </div>
      </div>
      <div class="hero-image-wrap relative">
        <div class="hero-visual-frame">
            <div class="hero-visual-chrome"><span></span><span></span><span></span><div class="hero-visual-url">sitedooz.ir</div></div>
            <picture>
                <source media="(max-width: 767px)" srcset="<?= asset_url('images/hero-illustration-mobile.svg') ?>">
                <img src="<?= asset_url('images/hero-illustration.svg') ?>" width="900" height="600" class="relative z-10 w-full" alt="شرکت طراحی سایت گرگان — سایت دوز" loading="eager">
            </picture>
        </div>
        <div class="absolute -bottom-6 -right-3 z-20 glass-card rounded-2xl px-5 py-4 hidden md:block">
          <div class="flex items-center gap-3"><span class="w-10 h-10 rounded-xl bg-emerald-400/15 text-emerald-200 flex items-center justify-center"><?= svg_icon('check', 'w-5 h-5') ?></span><strong>طراحی، سئو و پشتیبانی</strong></div>
        </div>
      </div>
    </div>
  </section>

<section class="py-16 md:py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">شرکت طراحی سایت گرگان؛ چرا انتخاب درست اینقدر مهم است؟</h2>
    </div>
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-10"><p class="text-slate-600 leading-9 mb-5 last:mb-0">امروزه هر کسب‌وکاری، از یک فروشگاه کوچک تا یک شرکت بزرگ، برای دیده شدن نیاز به یک وب‌سایت حرفه‌ای دارد. اما پیدا کردن یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> که واقعاً بتواند نیازهای شما را برآورده کند، کار ساده‌ای نیست.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">خیلی از کسب‌وکارها با یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> کار می‌کنند و بعد از چند ماه متوجه می‌شوند سایتشان نه سئو دارد، نه سرعت مناسب و نه امکان توسعه در آینده.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">در این مقاله می‌خواهیم بگوییم چه معیارهایی باعث می‌شود یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> واقعاً حرفه‌ای باشد و چطور می‌توانید انتخاب درستی داشته باشید.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">اگر به دنبال یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> قابل اعتماد هستید، این راهنما را از دست ندهید.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">در سال‌های اخیر، تعداد افرادی که خودشان را طراح سایت معرفی می‌کنند به‌شدت افزایش یافته است؛ از فریلنسرهای انفرادی گرفته تا استودیوهای کوچک و شرکت‌های بزرگ‌تر. این تنوع از یک طرف خوب است، چون گزینه‌های بیشتری در اختیار کسب‌وکارها قرار می‌دهد، اما از طرف دیگر انتخاب را سخت‌تر می‌کند. بسیاری از صاحبان کسب‌وکار در گرگان تجربه تلخی از همکاری با یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> غیرحرفه‌ای داشته‌اند؛ سایتی که ظاهر قابل‌قبولی داشته، اما از نظر فنی آن‌قدر ضعیف بوده که در عمل هیچ کمکی به رشد کسب‌وکار نکرده است.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">مشکل اصلی معمولاً از همان ابتدای مسیر شروع می‌شود: انتخاب عجولانه و بدون بررسی کافی. وقتی کسب‌وکاری صرفاً بر اساس پایین‌ترین قیمت یا اولین پیشنهادی که دریافت می‌کند تصمیم بگیرد، ریسک مواجهه با یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> کم‌تجربه به‌شدت بالا می‌رود. نتیجه این تصمیم عجولانه، معمولاً چند ماه بعد خودش را نشان می‌دهد: سایتی که سرعت پایینی دارد، در گوگل دیده نمی‌شود، در موبایل به‌درستی نمایش داده نمی‌شود و برای هرگونه تغییر کوچک باید هزینه اضافی پرداخت کرد.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">اهمیت انتخاب درست تنها به جنبه فنی محدود نمی‌شود. یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> حرفه‌ای، در واقع نقش یک مشاور کسب‌وکار را هم ایفا می‌کند. چنین شرکتی پیش از شروع طراحی، از شما درباره مخاطب هدف، رقبا، هدف اصلی سایت و مسیر رشد آینده کسب‌وکار سؤال می‌پرسد. این نگاه مشاوره‌محور باعث می‌شود سایت نهایی نه‌فقط زیبا، بلکه هدفمند و اثرگذار باشد؛ چیزی که با صرفاً سفارش یک قالب آماده و رنگ‌آمیزی مجدد آن هرگز به دست نمی‌آید.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">نکته دیگری که اهمیت انتخاب درست را دوچندان می‌کند، ماهیت رقابتی فضای آنلاین امروز است. مشتریان شما، پیش از هر تصمیمی، احتمالاً چند سایت مشابه را با هم مقایسه می‌کنند. اگر سایت شما نسبت به رقبا کندتر بارگذاری شود، طراحی قدیمی‌تری داشته باشد یا اعتمادسازی کافی نکند، حتی با وجود کیفیت بالای محصول یا خدماتتان، ممکن است مشتری را از دست بدهید. یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> باتجربه، این نکات رقابتی را از ابتدا در طراحی لحاظ می‌کند.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">در نهایت، باید این واقعیت را پذیرفت که تغییر یک همکاری اشتباه، معمولاً هزینه‌بر‌تر از انتخاب درست از همان ابتدا است. بازطراحی کامل سایت، انتقال محتوا، از دست دادن رتبه‌های سئوی قبلی و صرف زمان دوباره، همگی هزینه‌هایی هستند که با کمی دقت بیشتر در مرحله انتخاب، کاملاً قابل پیشگیری‌اند. به همین دلیل، وقت گذاشتن برای بررسی دقیق یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> پیش از شروع همکاری، یکی از سودمندترین تصمیماتی است که می‌توانید برای آینده کسب‌وکار خود بگیرید.</p></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-slate-50">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">شرکت طراحی سایت گرگان چه خدماتی باید ارائه دهد؟</h2>
    </div>
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-10"><p class="text-slate-600 leading-9 mb-5 last:mb-0">یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> حرفه‌ای، صرفاً یک صفحه زیبا برای شما طراحی نمی‌کند. خدمات یک شرکت خوب شامل موارد زیر است:</p>
<ul class="space-y-3 my-5">
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>طراحی رابط کاربری (UI) و تجربه کاربری (UX) اصولی</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>بهینه‌سازی سایت برای موبایل و تبلت</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>پیاده‌سازی اصول اولیه سئو در ساختار سایت</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>سرعت بارگذاری بالا و بهینه</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>امنیت سایت و محافظت در برابر حملات</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>امکان توسعه و افزودن قابلیت‌های جدید در آینده</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>پشتیبانی فنی پس از تحویل پروژه</span></li>
</ul>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">هر <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> که این موارد را رعایت نکند، احتمالاً سایتی تحویل می‌دهد که در بلندمدت مشکل‌ساز خواهد بود.</p></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">انواع سایت‌هایی که یک شرکت طراحی سایت گرگان می‌سازد</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-6"><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">سایت‌های شرکتی و معرفی خدمات</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">اکثر کسب‌وکارهای گرگان، اولین درخواستشان از یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong>، ساخت یک سایت معرفی خدمات است. این نوع سایت باید:</p>
<ul class="space-y-3 my-5">
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>ساختار ساده و قابل فهم داشته باشد</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>اطلاعات تماس و آدرس به‌راحتی در دسترس باشد</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>گالری تصاویر یا نمونه‌کار داشته باشد</span></li>
</ul></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">فروشگاه‌های اینترنتی</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">اگر محصول فیزیکی می‌فروشید، یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> باتجربه در فروشگاه‌سازی باید موارد زیر را در نظر بگیرد:</p>
<ul class="space-y-3 my-5">
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>درگاه پرداخت امن</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>مدیریت موجودی و سبد خرید</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>امکان اتصال به پنل پیامکی برای اطلاع‌رسانی</span></li>
</ul></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">سایت‌های خدماتی و رزرو آنلاین</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">برخی کسب‌وکارها مثل کلینیک‌ها، آموزشگاه‌ها یا حتی خدمات نظافتی نیاز به سیستم رزرو آنلاین دارند. یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> حرفه‌ای باید بتواند این نوع سیستم را هم پیاده‌سازی کند.</p></article></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-slate-50">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">مزایا و معایب همکاری با شرکت‌های محلی طراحی سایت</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-6"><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">مزایا</h3>
<ul class="space-y-3 my-5">
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span><strong class="font-black text-slate-900">دسترسی راحت‌تر:</strong> برای جلسات حضوری و رفع مشکلات فوری</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span><strong class="font-black text-slate-900">آشنایی با بازار محلی:</strong> یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> معمولاً با نیازهای کسب‌وکارهای منطقه آشناتر است</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span><strong class="font-black text-slate-900">پشتیبانی سریع‌تر:</strong> در صورت بروز مشکل، دسترسی راحت‌تری به تیم پشتیبانی دارید</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span><strong class="font-black text-slate-900">هزینه معمولاً پایین‌تر:</strong> نسبت به شرکت‌های بزرگ تهران</span></li>
</ul></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">معایب</h3>
<ul class="space-y-3 my-5">
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span><strong class="font-black text-slate-900">تنوع کمتر نمونه‌کار:</strong> ممکن است تجربه کمتری در پروژه‌های بزرگ داشته باشند</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span><strong class="font-black text-slate-900">محدودیت در برخی تخصص‌ها:</strong> برخی فناوری‌های خاص ممکن است در دسترس نباشد</span></li>
</ul></article></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">معیارهای انتخاب بهترین شرکت طراحی سایت گرگان</h2>
    </div>
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 mb-6"><p class="text-slate-600 leading-9 mb-5 last:mb-0">وقتی می‌خواهید یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> را انتخاب کنید، این معیارها را بررسی کنید:</p></div><div class="grid md:grid-cols-2 gap-6"><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">۱. نمونه‌کارهای قبلی را ببینید</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">هر <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> باید نمونه‌کارهای قبلی خود را نشان دهد. به این موارد توجه کنید:</p>
<ul class="space-y-3 my-5">
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>سرعت بارگذاری نمونه‌کارها</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>طراحی واکنش‌گرا (Responsive) در موبایل</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>ساختار سئو نمونه‌کارها</span></li>
</ul></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">۲. درباره سئو سؤال کنید</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">بسیاری از افراد فکر می‌کنند سئو کاری جداست، اما یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> خوب باید از ابتدا ساختار سایت را با در نظر گرفتن اصول سئو طراحی کند.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">۳. قرارداد و زمان‌بندی شفاف</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> حرفه‌ای، قبل از شروع کار، قرارداد مشخص با زمان‌بندی دقیق ارائه می‌دهد. از شرکت‌هایی که وعده‌های مبهم می‌دهند دوری کنید.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">۴. پشتیبانی پس از تحویل</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">سایت بعد از تحویل هم نیاز به به‌روزرسانی، رفع باگ و پشتیبانی دارد. حتماً از <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> مورد نظرتان بپرسید چه نوع پشتیبانی بعد از تحویل ارائه می‌دهند.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">۵. قیمت منطقی، نه صرفاً ارزان</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">پایین‌ترین قیمت همیشه بهترین انتخاب نیست. یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> که قیمت خیلی پایینی پیشنهاد می‌دهد، ممکن است در کیفیت کد، امنیت یا پشتیبانی کوتاهی کند.</p></article></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-slate-50">
  <div class="max-w-5xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">جدول مقایسه انتخاب شرکت طراحی سایت</h2>
    </div>
    <div class="overflow-x-auto rounded-3xl border border-slate-200 shadow-sm bg-white">
<table class="w-full min-w-[720px] text-right">
<thead class="bg-slate-900 text-white"><tr><th class="p-5 font-black">معیار</th><th class="p-5 font-black">شرکت مناسب</th><th class="p-5 font-black">شرکت نامناسب</th></tr></thead>
<tbody class="divide-y divide-slate-100 text-sm"><tr class="hover:bg-slate-50 transition"><td class="p-5 text-slate-600 leading-7">نمونه‌کار</td><td class="p-5 text-slate-600 leading-7">متنوع و باکیفیت</td><td class="p-5 text-slate-600 leading-7">محدود یا قدیمی</td></tr><tr class="hover:bg-slate-50 transition"><td class="p-5 text-slate-600 leading-7">سئو</td><td class="p-5 text-slate-600 leading-7">از ابتدا در نظر گرفته شده</td><td class="p-5 text-slate-600 leading-7">نادیده گرفته شده</td></tr><tr class="hover:bg-slate-50 transition"><td class="p-5 text-slate-600 leading-7">قرارداد</td><td class="p-5 text-slate-600 leading-7">شفاف و مکتوب</td><td class="p-5 text-slate-600 leading-7">شفاهی و مبهم</td></tr><tr class="hover:bg-slate-50 transition"><td class="p-5 text-slate-600 leading-7">پشتیبانی</td><td class="p-5 text-slate-600 leading-7">مستمر و پاسخگو</td><td class="p-5 text-slate-600 leading-7">فقط تا تحویل پروژه</td></tr><tr class="hover:bg-slate-50 transition"><td class="p-5 text-slate-600 leading-7">قیمت</td><td class="p-5 text-slate-600 leading-7">منطقی و متناسب با کیفیت</td><td class="p-5 text-slate-600 leading-7">خیلی پایین یا خیلی بالا بدون توجیه</td></tr><tr class="hover:bg-slate-50 transition"><td class="p-5 text-slate-600 leading-7">سرعت سایت</td><td class="p-5 text-slate-600 leading-7">بهینه و سریع</td><td class="p-5 text-slate-600 leading-7">کند و سنگین</td></tr></tbody>
</table></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">اشتباهات رایج در انتخاب شرکت طراحی سایت گرگان</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-6"><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">اشتباه اول: توجه فقط به ظاهر سایت</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">خیلی از افراد فقط به زیبایی ظاهری توجه می‌کنند و از یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> فقط طرح گرافیکی می‌خواهند، بدون توجه به عملکرد فنی سایت.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">اشتباه دوم: نپرسیدن درباره سئو</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">سایتی که سئو نداشته باشد، حتی اگر زیبا هم باشد، در گوگل دیده نمی‌شود. حتماً از <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> بپرسید سئوی پایه چطور پیاده‌سازی می‌شود.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">اشتباه سوم: نداشتن قرارداد مکتوب</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">بدون قرارداد شفاف، در صورت بروز اختلاف هیچ مرجعی برای پیگیری نخواهید داشت.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">اشتباه چهارم: انتخاب بر اساس قیمت صرف</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">انتخاب ارزان‌ترین <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> بدون بررسی کیفیت، معمولاً هزینه‌های پنهان بعدی به همراه دارد.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">اشتباه پنجم: نادیده گرفتن پشتیبانی بعد از تحویل</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">سایت شما بعد از تحویل هم نیاز به نگهداری دارد. عدم توجه به این موضوع، مشکلات بلندمدت ایجاد می‌کند.</p></article></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-slate-50">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">راهنمای کامل مراحل همکاری با شرکت طراحی سایت گرگان</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-6"><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">مرحله ۱: مشخص کردن نیاز</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">قبل از مراجعه به هر <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong>، دقیقاً بدانید چه نوع سایتی نیاز دارید: شرکتی، فروشگاهی یا خدماتی.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">مرحله ۲: جمع‌آوری چند پیشنهاد</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">از حداقل ۲ تا ۳ شرکت مختلف مشاوره و پیش‌فاکتور بگیرید تا بتوانید مقایسه کنید.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">مرحله ۳: بررسی نمونه‌کار و رزومه</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">نمونه‌کارهای هر <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> را از نظر سرعت، طراحی و کاربری بررسی کنید.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">مرحله ۴: بستن قرارداد شفاف</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">مطمئن شوید همه جزئیات پروژه، زمان تحویل و هزینه‌ها در قرارداد ذکر شده است.</p></article><article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-8 hover-lift"><h3 class="text-xl font-black text-slate-900 mb-4 leading-8">مرحله ۵: پیگیری پس از تحویل</h3>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">بعد از تحویل سایت، از خدمات پشتیبانی و به‌روزرسانی‌های دوره‌ای غافل نشوید.</p></article></div>
  </div>
</section>
<section class="py-16 md:py-20 bg-white">
  <div class="max-w-5xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">سوالات متداول درباره شرکت طراحی سایت گرگان</h2>
    </div>
    <div class="space-y-4">
<article class="faq-item bg-white rounded-2xl border border-slate-200/80 shadow-sm cursor-pointer overflow-hidden">
  <div class="p-6 flex items-center justify-between gap-5">
    <h3 class="font-black text-lg leading-8">هزینه طراحی سایت توسط شرکت طراحی سایت گرگان چقدر است؟</h3>
    <span class="faq-icon transition-transform text-emerald-600 shrink-0"><?= svg_icon('chevron', 'w-5 h-5') ?></span>
  </div>
  <div class="faq-answer"><p class="px-6 pb-6 text-slate-600 leading-9 border-t border-slate-100 pt-5">هزینه بستگی به نوع سایت دارد. یک سایت معرفی خدمات ساده هزینه کمتری دارد، در حالی که فروشگاه اینترنتی یا سایت با امکانات پیچیده، هزینه بیشتری می‌طلبد. بهتر است از چند <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> پیش‌فاکتور بگیرید و مقایسه کنید.</p></div>
</article>
<article class="faq-item bg-white rounded-2xl border border-slate-200/80 shadow-sm cursor-pointer overflow-hidden">
  <div class="p-6 flex items-center justify-between gap-5">
    <h3 class="font-black text-lg leading-8">چقدر طول می‌کشد یک شرکت طراحی سایت گرگان پروژه را تحویل دهد؟</h3>
    <span class="faq-icon transition-transform text-emerald-600 shrink-0"><?= svg_icon('chevron', 'w-5 h-5') ?></span>
  </div>
  <div class="faq-answer"><p class="px-6 pb-6 text-slate-600 leading-9 border-t border-slate-100 pt-5">بسته به پیچیدگی پروژه، از ۲ هفته تا ۲ ماه متغیر است. سایت‌های ساده سریع‌تر و فروشگاه‌های بزرگ زمان بیشتری نیاز دارند.</p></div>
</article>
<article class="faq-item bg-white rounded-2xl border border-slate-200/80 shadow-sm cursor-pointer overflow-hidden">
  <div class="p-6 flex items-center justify-between gap-5">
    <h3 class="font-black text-lg leading-8">آیا شرکت طراحی سایت گرگان باید سئو را هم انجام دهد؟</h3>
    <span class="faq-icon transition-transform text-emerald-600 shrink-0"><?= svg_icon('chevron', 'w-5 h-5') ?></span>
  </div>
  <div class="faq-answer"><p class="px-6 pb-6 text-slate-600 leading-9 border-t border-slate-100 pt-5">حداقل باید ساختار فنی سایت برای سئو آماده باشد. سئوی محتوایی و رقابتی معمولاً خدمت جداگانه‌ای است که می‌توانید از همان <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> یا یک تیم تخصصی سئو بگیرید.</p></div>
</article>
<article class="faq-item bg-white rounded-2xl border border-slate-200/80 shadow-sm cursor-pointer overflow-hidden">
  <div class="p-6 flex items-center justify-between gap-5">
    <h3 class="font-black text-lg leading-8">بعد از تحویل سایت، پشتیبانی چطور انجام می‌شود؟</h3>
    <span class="faq-icon transition-transform text-emerald-600 shrink-0"><?= svg_icon('chevron', 'w-5 h-5') ?></span>
  </div>
  <div class="faq-answer"><p class="px-6 pb-6 text-slate-600 leading-9 border-t border-slate-100 pt-5">اکثر شرکت‌های حرفه‌ای بسته‌های پشتیبانی ماهانه یا سالانه ارائه می‌دهند. حتماً پیش از شروع همکاری با <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> درباره نوع و مدت پشتیبانی سؤال کنید.</p></div>
</article>
<article class="faq-item bg-white rounded-2xl border border-slate-200/80 shadow-sm cursor-pointer overflow-hidden">
  <div class="p-6 flex items-center justify-between gap-5">
    <h3 class="font-black text-lg leading-8">چطور بفهمم یک شرکت طراحی سایت گرگان معتبر است؟</h3>
    <span class="faq-icon transition-transform text-emerald-600 shrink-0"><?= svg_icon('chevron', 'w-5 h-5') ?></span>
  </div>
  <div class="faq-answer"><p class="px-6 pb-6 text-slate-600 leading-9 border-t border-slate-100 pt-5">نمونه‌کارها را بررسی کنید، نظرات مشتریان قبلی را بخوانید و در صورت امکان با چند مشتری قبلی صحبت کنید.</p></div>
</article>
<article class="faq-item bg-white rounded-2xl border border-slate-200/80 shadow-sm cursor-pointer overflow-hidden">
  <div class="p-6 flex items-center justify-between gap-5">
    <h3 class="font-black text-lg leading-8">آیا می‌توان سایت را خودم مدیریت کنم یا باید همیشه به شرکت مراجعه کنم؟</h3>
    <span class="faq-icon transition-transform text-emerald-600 shrink-0"><?= svg_icon('chevron', 'w-5 h-5') ?></span>
  </div>
  <div class="faq-answer"><p class="px-6 pb-6 text-slate-600 leading-9 border-t border-slate-100 pt-5">اگر سایت با سیستم مدیریت محتوا مثل وردپرس ساخته شود، معمولاً می‌توانید محتوا را خودتان مدیریت کنید. این موضوع را از ابتدا با <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> هماهنگ کنید.</p></div>
</article>
</div>
  </div>
</section>
<section class="py-16 md:py-20 bg-slate-50">
  <div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mb-9">
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-[1.55]">جمع‌بندی؛ انتخاب درست شرکت طراحی سایت گرگان</h2>
    </div>
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 md:p-10"><p class="text-slate-600 leading-9 mb-5 last:mb-0">انتخاب یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> مناسب، تأثیر مستقیمی روی موفقیت آنلاین کسب‌وکار شما دارد. سایتی که سریع، امن، سئو شده و کاربرپسند باشد، می‌تواند به رشد واقعی کسب‌وکارتان کمک کند.</p>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">نکات کلیدی که باید به خاطر بسپارید:</p>
<ul class="space-y-3 my-5">
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>نمونه‌کارهای قبلی را با دقت بررسی کنید</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>درباره سئو و بهینه‌سازی سؤال بپرسید</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>قرارداد شفاف داشته باشید</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>پشتیبانی پس از تحویل را نادیده نگیرید</span></li>
<li class="flex items-start gap-3 text-slate-600 leading-8"><span class="mt-2 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span><span>قیمت را با کیفیت مقایسه کنید، نه به‌تنهایی</span></li>
</ul>
<p class="text-slate-600 leading-9 mb-5 last:mb-0">اگر به دنبال یک <strong class="font-black text-slate-900">شرکت طراحی سایت گرگان</strong> قابل اعتماد و حرفه‌ای هستید، همین حالا برای مشاوره رایگان با تیم متخصص تماس بگیرید.</p></div>
  </div>
</section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
