<?php require_once __DIR__ . '/../app/functions.php'; require_login(); ensure_schema(); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>پنل مدیریت سایت دوز</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Vazirmatn', 'sans-serif'] }
        }
      }
    }
  </script>
  <link href="<?= asset_url('css/Vazirmatn-font-face.css') ?>" rel="stylesheet" />
  <style>body{font-family:"Vazirmatn",sans-serif}.field{width:100%;border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;background:#fff;outline:none}.field:focus{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.12)}.label{font-weight:800;font-size:13px;color:#334155;margin-bottom:8px;display:block}.admin-card{background:#fff;border-radius:22px;box-shadow:0 10px 30px rgba(15,23,42,.06);border:1px solid #eef2f7}.svg-icon{display:inline-block;vertical-align:-.18em;flex-shrink:0}</style>
</head>
<body class="bg-slate-100 text-slate-800">
<div class="min-h-screen flex">
  <aside class="hidden lg:block w-72 bg-slate-900 text-white p-6">
    <a href="<?= site_url('admin/dashboard.php') ?>" class="text-2xl font-extrabold block mb-8">پنل سایت دوز</a>
    <nav class="space-y-2 text-sm">
      <a class="block px-4 py-3 rounded-xl hover:bg-white/10" href="<?= site_url('admin/dashboard.php') ?>"><?= svg_icon('gauge', 'w-5 h-5 ml-2') ?>داشبورد</a>
      <a class="block px-4 py-3 rounded-xl hover:bg-white/10" href="<?= site_url('admin/posts.php') ?>"><?= svg_icon('newspaper', 'w-5 h-5 ml-2') ?>بلاگ</a>
      <a class="block px-4 py-3 rounded-xl hover:bg-white/10" href="<?= site_url('admin/portfolio.php') ?>"><?= svg_icon('briefcase', 'w-5 h-5 ml-2') ?>نمونه‌کارها</a>
      <a class="block px-4 py-3 rounded-xl hover:bg-white/10" href="<?= site_url('admin/seo.php') ?>"><?= svg_icon('chart', 'w-5 h-5 ml-2') ?>نتایج سئو</a>
      <a class="block px-4 py-3 rounded-xl hover:bg-white/10" href="<?= site_url('admin/consultations.php') ?>"><?= svg_icon('phone', 'w-5 h-5 ml-2') ?>درخواست‌های مشاوره</a>
      <a class="block px-4 py-3 rounded-xl hover:bg-white/10" href="<?= site_url('admin/settings.php') ?>"><?= svg_icon('gear', 'w-5 h-5 ml-2') ?>تنظیمات صفحه اصلی</a>
      <a class="block px-4 py-3 rounded-xl hover:bg-white/10" href="<?= site_url() ?>" target="_blank"><?= svg_icon('external', 'w-5 h-5 ml-2') ?>مشاهده سایت</a>
      <a class="block px-4 py-3 rounded-xl hover:bg-red-500/20 text-red-100" href="<?= site_url('admin/logout.php') ?>"><?= svg_icon('logout', 'w-5 h-5 ml-2') ?>خروج</a>
    </nav>
  </aside>
  <div class="flex-1">
    <header class="lg:hidden bg-slate-900 text-white p-4 flex justify-between items-center">
      <span class="font-extrabold">پنل سایت دوز</span>
      <a href="<?= site_url('admin/logout.php') ?>" class="text-sm">خروج</a>
    </header>
    <main class="p-4 md:p-8">
