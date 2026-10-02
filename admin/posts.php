<?php
include __DIR__ . '/_header.php';
$posts = get_posts();
usort($posts, fn($a, $b) => strcmp($b['published_at'] ?? '', $a['published_at'] ?? ''));
?>
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div><h1 class="text-3xl font-extrabold mb-2">مدیریت بلاگ</h1><p class="text-slate-500">لیست، ویرایش و انتشار مقالات سایت</p></div>
  <a href="<?= site_url('admin/post-edit.php') ?>" class="bg-emerald-500 text-white px-5 py-3 rounded-xl font-bold text-center">+ مقاله جدید</a>
</div>
<div class="admin-card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-500"><tr><th class="text-right p-4">عنوان</th><th class="p-4">دسته</th><th class="p-4">تاریخ</th><th class="p-4">وضعیت</th><th class="p-4">عملیات</th></tr></thead>
      <tbody>
        <?php foreach ($posts as $post): ?>
          <tr class="border-t">
            <td class="p-4 font-bold"><?= e($post['title'] ?? '') ?><div class="text-xs text-slate-400 mt-1">/blog/<?= e($post['slug'] ?? '') ?></div></td>
            <td class="p-4 text-center"><?= e($post['category'] ?? '') ?></td>
            <td class="p-4 text-center"><?= format_date($post['published_at'] ?? '') ?></td>
            <td class="p-4 text-center"><span class="px-3 py-1 rounded-full text-xs <?= ($post['status'] ?? '') === 'published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' ?>"><?= ($post['status'] ?? '') === 'published' ? 'منتشر شده' : 'پیش‌نویس' ?></span></td>
            <td class="p-4 text-center whitespace-nowrap">
              <a class="text-emerald-600 font-bold ml-3" href="<?= site_url('admin/post-edit.php?id=' . urlencode($post['id'])) ?>">ویرایش</a>
              <a class="text-red-500 font-bold" onclick="return confirm('حذف شود؟')" href="<?= site_url('admin/post-delete.php?id=' . urlencode($post['id']) . '&csrf_token=' . csrf_token()) ?>">حذف</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
