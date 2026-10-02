<?php
include __DIR__ . '/_header.php';
$items = get_portfolio();
?>
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div><h1 class="text-3xl font-extrabold mb-2">مدیریت نمونه‌کارها</h1><p class="text-slate-500">این موارد در صفحه اصلی نمایش داده می‌شوند.</p></div>
  <a href="<?= site_url('admin/portfolio-edit.php') ?>" class="bg-emerald-500 text-white px-5 py-3 rounded-xl font-bold text-center">+ نمونه‌کار جدید</a>
</div>
<div class="grid md:grid-cols-3 gap-6">
<?php foreach ($items as $item): ?>
  <div class="admin-card overflow-hidden">
    <img src="<?= asset_url($item['image'] ?? 'images/hero.png') ?>" class="h-40 w-full object-cover" alt="">
    <div class="p-5">
      <h2 class="font-extrabold text-lg mb-2"><?= e($item['title'] ?? '') ?></h2>
      <p class="text-slate-500 text-sm leading-7 mb-4"><?= e($item['subtitle'] ?? '') ?></p>
      <div class="flex justify-between text-sm font-bold"><a class="text-emerald-600" href="<?= site_url('admin/portfolio-edit.php?id=' . urlencode($item['id'])) ?>">ویرایش</a><a class="text-red-500" onclick="return confirm('حذف شود؟')" href="<?= site_url('admin/portfolio-delete.php?id=' . urlencode($item['id']) . '&csrf_token=' . csrf_token()) ?>">حذف</a></div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
