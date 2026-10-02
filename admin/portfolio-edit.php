<?php
require_once __DIR__ . '/../app/functions.php';
require_login();
$id     = $_GET['id'] ?? '';
$isEdit = $id !== '';
$item   = $isEdit ? (get_portfolio_item($id) ?? null) : null;
if ($isEdit && !$item) {
    header('Location: ' . site_url('admin/portfolio.php'));
    exit;
}
$item = $item ?? ['id' => make_id('pf'), 'title' => '', 'subtitle' => '', 'image' => '', 'url' => '#', 'status' => 'published'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $uploaded = upload_image('image_file');
    $item = [
        'id'       => $_POST['id'] ?? make_id('pf'),
        'title'    => trim($_POST['title'] ?? ''),
        'subtitle' => trim($_POST['subtitle'] ?? ''),
        'image'    => $uploaded ?: trim($_POST['image'] ?? ''),
        'url'      => trim($_POST['url'] ?? '#'),
        'status'   => $_POST['status'] ?? 'published',
    ];
    save_portfolio_item($item);
    header('Location: ' . site_url('admin/portfolio.php'));
    exit;
}
include __DIR__ . '/_header.php';
?>
<div class="mb-8"><h1 class="text-3xl font-extrabold mb-2"><?= $isEdit ? 'ویرایش نمونه‌کار' : 'نمونه‌کار جدید' ?></h1></div>
<form method="post" enctype="multipart/form-data" class="admin-card p-6 space-y-6">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= e($item['id']) ?>">
  <div class="grid md:grid-cols-2 gap-5"><div><label class="label">عنوان</label><input class="field" name="title" value="<?= e($item['title']) ?>" required></div><div><label class="label">وضعیت</label><select class="field" name="status"><option value="published" <?= ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>منتشر شده</option><option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>پیش‌نویس</option></select></div></div>
  <div><label class="label">توضیح کوتاه</label><textarea class="field" rows="3" name="subtitle"><?= e($item['subtitle']) ?></textarea></div>
  <div class="grid md:grid-cols-2 gap-5"><div><label class="label">آدرس تصویر</label><input class="field" name="image" value="<?= e($item['image']) ?>"></div><div><label class="label">آپلود تصویر</label><input class="field" type="file" name="image_file" accept="image/*"></div></div>
  <div><label class="label">لینک پروژه</label><input class="field" name="url" value="<?= e($item['url']) ?>"></div>
  <div class="flex gap-3"><button class="bg-emerald-500 text-white px-7 py-3 rounded-xl font-extrabold">ذخیره</button><a href="<?= site_url('admin/portfolio.php') ?>" class="bg-slate-100 px-7 py-3 rounded-xl font-bold">انصراف</a></div>
</form>
<?php include __DIR__ . '/_footer.php'; ?>
