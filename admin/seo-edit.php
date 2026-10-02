<?php
require_once __DIR__ . '/../app/functions.php';
require_login();
$id     = $_GET['id'] ?? '';
$isEdit = $id !== '';
$item   = $isEdit ? (get_seo_item($id) ?? null) : null;
if ($isEdit && !$item) {
    header('Location: ' . site_url('admin/seo.php'));
    exit;
}
$item = $item ?? ['id' => make_id('seo'), 'keyword' => '', 'summary' => '', 'rank' => '', 'image' => '', 'status' => 'published'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $uploaded = upload_image('image_file');
    $item = [
        'id'      => $_POST['id'] ?? make_id('seo'),
        'keyword' => trim($_POST['keyword'] ?? ''),
        'summary' => trim($_POST['summary'] ?? ''),
        'rank'    => trim($_POST['rank'] ?? ''),
        'image'   => $uploaded ?: trim($_POST['image'] ?? ''),
        'status'  => $_POST['status'] ?? 'published',
    ];
    save_seo_item($item);
    header('Location: ' . site_url('admin/seo.php'));
    exit;
}
include __DIR__ . '/_header.php';
?>
<div class="mb-8"><h1 class="text-3xl font-extrabold mb-2"><?= $isEdit ? 'ویرایش نتیجه سئو' : 'نتیجه سئو جدید' ?></h1></div>
<form method="post" enctype="multipart/form-data" class="admin-card p-6 space-y-6">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= e($item['id']) ?>">
  <div class="grid md:grid-cols-2 gap-5"><div><label class="label">کلمه کلیدی / عنوان</label><input class="field" name="keyword" value="<?= e($item['keyword']) ?>" required></div><div><label class="label">رتبه یا نتیجه</label><input class="field" name="rank" value="<?= e($item['rank']) ?>" placeholder="رتبه ۱"></div></div>
  <div><label class="label">توضیح کوتاه</label><input class="field" name="summary" value="<?= e($item['summary']) ?>"></div>
  <div class="grid md:grid-cols-2 gap-5"><div><label class="label">آدرس تصویر</label><input class="field" name="image" value="<?= e($item['image']) ?>"></div><div><label class="label">آپلود تصویر</label><input class="field" type="file" name="image_file" accept="image/*"></div></div>
  <div><label class="label">وضعیت</label><select class="field" name="status"><option value="published" <?= ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>منتشر شده</option><option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>پیش‌نویس</option></select></div>
  <div class="flex gap-3"><button class="bg-emerald-500 text-white px-7 py-3 rounded-xl font-extrabold">ذخیره</button><a href="<?= site_url('admin/seo.php') ?>" class="bg-slate-100 px-7 py-3 rounded-xl font-bold">انصراف</a></div>
</form>
<?php include __DIR__ . '/_footer.php'; ?>
