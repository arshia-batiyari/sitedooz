<?php
require_once dirname(__DIR__) . '/app/functions.php';
$settings = get_settings();
?>
<body class="bg-slate-50 text-gray-800">
  <div id="scrollProgressBar" class="scroll-progress-bar" role="progressbar" aria-label="میزان پیشرفت مطالعه صفحه" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><span id="scrollProgressFill" class="scroll-progress-fill"></span></div>
  <header class="fixed top-0 inset-x-0 bg-white/85 backdrop-blur-xl border-b border-slate-200/70 z-50">
    <div class="max-w-6xl mx-auto px-4 py-2 md:py-1 flex items-center justify-between gap-6">
      <a href="<?= site_url() ?>" class="logo-box shrink-0" aria-label="<?= e(APP_NAME) ?>">
        <img src="<?= asset_url('images/logo-optimized.webp') ?>" width="300" height="300" class="w-full h-full object-cover" alt="لوگوی سایت دوز" />
      </a>
      <nav class="hidden xl:flex items-center gap-3 lg:gap-4 text-xs lg:text-sm font-medium">
        <a href="<?= site_url('#services') ?>" class="hover:text-emerald-600 transition">خدمات</a>
        <a href="<?= site_url('nemoone-kar-tarahi-site-gorgan') ?>" class="hover:text-emerald-600 transition">نمونه کار</a>
        <a href="<?= site_url('sozalat-motadavel-tarahi-site') ?>" class="hover:text-emerald-600 transition">سوالات متداول</a>
        <a href="<?= site_url('hazine-seo-site-gorgan') ?>" class="hover:text-emerald-600 transition">هزینه سئو</a>
        <a href="<?= site_url('hazine-tarahi-site-gorgan') ?>" class="hover:text-emerald-600 transition">هزینه طراحی سایت</a>
        <a href="<?= site_url('blog') ?>" class="hover:text-emerald-600 transition">وبلاگ</a>
        <a href="<?= site_url('about-us') ?>" class="hover:text-emerald-600 transition">درباره ما</a>
        <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>#consultation-form" class="btn-main bg-slate-900 text-white px-4 py-2 rounded-xl inline-flex items-center gap-2">درخواست مشاوره</a>
      </nav>
      <button id="menuBtn" class="xl:hidden text-slate-900 p-2 rounded-xl bg-slate-100" aria-label="باز کردن منو"><?= svg_icon('menu', 'w-6 h-6') ?></button>
    </div>
  </header>

<div id="mobileMenu" class="transition-all duration-300 ease-in-out opacity-0 pointer-events-none -translate-y-5 xl:hidden bg-white/95 backdrop-blur-xl border-b pt-16 fixed top-0 inset-x-0 z-40 shadow-lg">
    <div class="flex flex-col p-5 gap-4">
      <a href="<?= site_url('about-us') ?>">درباره ما</a>
      <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>">تماس و مشاوره</a>
      <a href="<?= site_url('#services') ?>">خدمات</a>
      <a href="<?= site_url('nemoone-kar-tarahi-site-gorgan') ?>">نمونه کار</a>
      <a href="<?= site_url('sozalat-motadavel-tarahi-site') ?>">سوالات متداول</a>
      <a href="<?= site_url('hazine-seo-site-gorgan') ?>">هزینه سئو</a>
      <a href="<?= site_url('hazine-tarahi-site-gorgan') ?>">هزینه طراحی سایت</a>
      <a href="<?= site_url('blog') ?>">وبلاگ</a>
      <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>#consultation-form" class="bg-emerald-500 text-white px-4 py-3 rounded-xl text-center font-bold">درخواست مشاوره</a>
    </div>
  </div>
