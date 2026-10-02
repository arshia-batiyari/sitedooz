<?php
include __DIR__ . '/_header.php';
$postsCount    = (int) db()->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$portfolioCount = (int) db()->query("SELECT COUNT(*) FROM portfolio_items")->fetchColumn();
$seoCount      = (int) db()->query("SELECT COUNT(*) FROM seo_items")->fetchColumn();
$consultationsCount = (int) db()->query("SELECT COUNT(*) FROM consultation_requests WHERE status = 'new'")->fetchColumn();
?>
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
    <h1 class="text-3xl font-extrabold mb-2">داشبورد مدیریت</h1>
    <p class="text-slate-500">از این بخش می‌توانید محتوای بلاگ و اطلاعات پویا را مدیریت کنید.</p>
  </div>
  <a href="<?= site_url('admin/post-edit.php') ?>" class="bg-emerald-500 text-white px-5 py-3 rounded-xl font-bold text-center">+ مقاله جدید</a>
</div>
<div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
  <div class="admin-card p-6"><div class="text-slate-400 text-sm mb-2">تعداد مقالات</div><div class="text-4xl font-extrabold"><?= fa_number($postsCount) ?></div></div>
  <div class="admin-card p-6"><div class="text-slate-400 text-sm mb-2">نمونه‌کارها</div><div class="text-4xl font-extrabold"><?= fa_number($portfolioCount) ?></div></div>
  <div class="admin-card p-6"><div class="text-slate-400 text-sm mb-2">نتایج سئو</div><div class="text-4xl font-extrabold"><?= fa_number($seoCount) ?></div></div>
  <div class="admin-card p-6"><div class="text-slate-400 text-sm mb-2">درخواست‌های جدید</div><div class="text-4xl font-extrabold text-emerald-600"><?= fa_number($consultationsCount) ?></div></div>
</div>
<div class="admin-card p-6">
  <h2 class="text-xl font-extrabold mb-4">کارهای سریع</h2>
  <div class="grid md:grid-cols-4 gap-3 text-center text-sm font-bold">
    <a class="bg-slate-100 rounded-xl p-4 hover:bg-emerald-50" href="<?= site_url('admin/posts.php') ?>">مدیریت بلاگ</a>
    <a class="bg-slate-100 rounded-xl p-4 hover:bg-emerald-50" href="<?= site_url('admin/portfolio.php') ?>">مدیریت نمونه‌کار</a>
    <a class="bg-slate-100 rounded-xl p-4 hover:bg-emerald-50" href="<?= site_url('admin/seo.php') ?>">مدیریت نتایج سئو</a>
    <a class="bg-slate-100 rounded-xl p-4 hover:bg-emerald-50" href="<?= site_url('admin/consultations.php') ?>">درخواست‌های مشاوره</a>
    <a class="bg-slate-100 rounded-xl p-4 hover:bg-emerald-50" href="<?= site_url('admin/settings.php') ?>">تنظیمات صفحه اصلی</a>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
