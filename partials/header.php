<?php
require_once dirname(__DIR__) . '/app/functions.php';
$settings = get_settings();
?>
<body class="bg-slate-50 text-gray-800">
  <div id="scrollProgressBar" class="scroll-progress-bar" role="progressbar" aria-label="میزان پیشرفت مطالعه صفحه" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><span id="scrollProgressFill" class="scroll-progress-fill"></span></div>
  <header id="siteHeader" class="fixed top-0 inset-x-0 bg-white/85 backdrop-blur-xl border-b border-slate-200/70 z-50">
    <div class="max-w-6xl mx-auto px-4 py-2 md:py-1 flex items-center justify-between gap-4">
      <a href="<?= site_url() ?>" class="logo-box shrink-0" aria-label="<?= e(APP_NAME) ?>">
        <img src="<?= asset_url('images/logo-optimized.webp') ?>" width="300" height="300" class="w-full h-full object-cover" alt="لوگوی سایت دوز" />
      </a>
      <nav class="hidden xl:flex items-center gap-3 lg:gap-4 text-xs lg:text-sm font-medium" aria-label="ناوبری اصلی">
        <a href="<?= site_url('#services') ?>" class="hover:text-emerald-600 transition">خدمات</a>
        <a href="<?= site_url('nemoone-kar-tarahi-site-gorgan') ?>" class="hover:text-emerald-600 transition">نمونه‌کار</a>
        <a href="<?= site_url('#seo') ?>" class="hover:text-emerald-600 transition">نتایج</a>
        <a href="<?= site_url('#insight') ?>" class="hover:text-emerald-600 transition">Insight</a>
        <a href="<?= site_url('blog') ?>" class="hover:text-emerald-600 transition">وبلاگ</a>
        <a href="<?= site_url('about-us') ?>" class="hover:text-emerald-600 transition">درباره ما</a>
        <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>" class="hover:text-emerald-600 transition">تماس</a>
        <a href="<?= site_url('#audit') ?>" class="btn-main bg-slate-900 text-white px-4 py-2 rounded-xl inline-flex items-center gap-2">تحلیل رایگان سایت</a>
      </nav>
      <div class="flex items-center gap-2 xl:hidden">
        <a href="<?= site_url('#audit') ?>" class="btn-main bg-slate-900 text-white px-3 py-2 rounded-xl text-sm font-extrabold whitespace-nowrap">تحلیل رایگان</a>
        <button id="menuBtn" class="text-slate-900 p-2 rounded-xl bg-slate-100" aria-label="باز کردن منو" aria-expanded="false" aria-controls="mobileMenu"><?= svg_icon('menu', 'w-6 h-6') ?></button>
      </div>
    </div>
  </header>

<div id="mobileMenu" class="transition-all duration-300 ease-in-out opacity-0 pointer-events-none -translate-y-5 xl:hidden bg-white/95 backdrop-blur-xl border-b pt-24 fixed top-0 inset-x-0 z-40 shadow-lg">
    <div class="flex flex-col p-5 gap-5">
      <div>
        <p class="mobile-nav-label">صفحات</p>
        <div class="mobile-nav-grid">
          <a href="<?= site_url('#services') ?>">خدمات</a>
          <a href="<?= site_url('nemoone-kar-tarahi-site-gorgan') ?>">نمونه‌کار</a>
          <a href="<?= site_url('#seo') ?>">نتایج</a>
          <a href="<?= site_url('#insight') ?>">Insight</a>
          <a href="<?= site_url('blog') ?>">وبلاگ</a>
          <a href="<?= site_url('about-us') ?>">درباره ما</a>
          <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>">تماس</a>
        </div>
      </div>
      <div>
        <p class="mobile-nav-label">هزینه و راهنما</p>
        <div class="mobile-nav-list">
          <a href="<?= site_url('sozalat-motadavel-tarahi-site') ?>">سوالات متداول</a>
          <a href="<?= site_url('hazine-seo-site-gorgan') ?>">هزینه سئو</a>
          <a href="<?= site_url('hazine-tarahi-site-gorgan') ?>">هزینه طراحی سایت</a>
        </div>
      </div>
      <a href="<?= site_url('#audit') ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-4 rounded-2xl text-center font-black text-lg">تحلیل رایگان سایت</a>
    </div>
  </div>
