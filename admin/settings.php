<?php
require_once __DIR__ . '/../app/functions.php';
require_login();
$settings = get_settings();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fields = ['site_title','site_description','phone','whatsapp','hero_title','hero_description','footer_title','footer_description'];
    $updated = [];
    foreach ($fields as $field) { $updated[$field] = trim($_POST[$field] ?? ''); }
    save_settings($updated);
    header('Location: ' . site_url('admin/settings.php?saved=1'));
    exit;
}
include __DIR__ . '/_header.php';
?>
<div class="mb-8"><h1 class="text-3xl font-extrabold mb-2">تنظیمات صفحه اصلی</h1><p class="text-slate-500">عنوان‌ها، توضیحات متا، شماره تماس و متن‌های اصلی صفحه اصلی از اینجا تغییر می‌کند.</p></div>
<?php if (!empty($_GET['saved'])): ?><div class="bg-emerald-50 text-emerald-700 rounded-xl p-4 mb-5 font-bold">تغییرات ذخیره شد.</div><?php endif; ?>
<form method="post" class="admin-card p-6 space-y-6">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
  <div><label class="label">Title اصلی سایت</label><input class="field" name="site_title" value="<?= e($settings['site_title'] ?? '') ?>"></div>
  <div><label class="label">Meta Description اصلی سایت</label><textarea class="field" rows="3" name="site_description"><?= e($settings['site_description'] ?? '') ?></textarea></div>
  <div class="grid md:grid-cols-2 gap-5"><div><label class="label">شماره تماس</label><input class="field" name="phone" value="<?= e($settings['phone'] ?? '') ?>"></div><div><label class="label">شماره واتساپ</label><input class="field" name="whatsapp" value="<?= e($settings['whatsapp'] ?? '') ?>"></div></div>
  <div><label class="label">تیتر هیرو</label><input class="field" name="hero_title" value="<?= e($settings['hero_title'] ?? '') ?>"></div>
  <div><label class="label">توضیح هیرو</label><textarea class="field" rows="4" name="hero_description"><?= e($settings['hero_description'] ?? '') ?></textarea></div>
  <div><label class="label">تیتر فوتر / CTA</label><input class="field" name="footer_title" value="<?= e($settings['footer_title'] ?? '') ?>"></div>
  <div><label class="label">توضیح فوتر</label><textarea class="field" rows="3" name="footer_description"><?= e($settings['footer_description'] ?? '') ?></textarea></div>
  <button class="bg-emerald-500 text-white px-7 py-3 rounded-xl font-extrabold">ذخیره تنظیمات</button>
</form>
<?php include __DIR__ . '/_footer.php'; ?>
