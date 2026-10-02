<?php
include __DIR__ . '/_header.php';
$items = get_seo_items();
?>
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div><h1 class="text-3xl font-extrabold mb-2">مدیریت نتایج سئو</h1><p class="text-slate-500">این موارد در سکشن موفقیت‌های سئو نمایش داده می‌شوند.</p></div>
  <a href="<?= site_url('admin/seo-edit.php') ?>" class="bg-emerald-500 text-white px-5 py-3 rounded-xl font-bold text-center">+ نتیجه جدید</a>
</div>
<div class="grid md:grid-cols-3 gap-6">
<?php foreach ($items as $item): ?>
  <div class="admin-card p-5">
    <h2 class="font-extrabold text-lg mb-2"><?= e($item['keyword'] ?? '') ?></h2>
    <p class="text-slate-500 text-sm mb-3"><?= e($item['summary'] ?? '') ?></p>
    <div class="text-emerald-600 font-extrabold mb-4"><?= e($item['rank'] ?? '') ?></div>
    <img src="<?= asset_url($item['image'] ?? 'images/hero.png') ?>" class="h-36 w-full object-cover rounded-xl mb-4" alt="">
    <div class="flex justify-between text-sm font-bold"><a class="text-emerald-600" href="<?= site_url('admin/seo-edit.php?id=' . urlencode($item['id'])) ?>">ویرایش</a><a class="text-red-500" onclick="return confirm('حذف شود؟')" href="<?= site_url('admin/seo-delete.php?id=' . urlencode($item['id']) . '&csrf_token=' . csrf_token()) ?>">حذف</a></div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
