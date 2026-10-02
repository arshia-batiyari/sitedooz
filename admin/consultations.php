<?php
include __DIR__ . '/_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $status = (string)($_POST['status'] ?? 'new');
    if ($id > 0) update_consultation_status($id, $status);
    header('Location: ' . site_url('admin/consultations.php'));
    exit;
}

$requests = get_consultation_requests();
$statusLabels = ['new' => 'جدید', 'contacted' => 'تماس گرفته شد', 'done' => 'بسته شده'];
$statusClasses = ['new' => 'bg-emerald-100 text-emerald-700', 'contacted' => 'bg-sky-100 text-sky-700', 'done' => 'bg-slate-200 text-slate-600'];
?>
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div><h1 class="text-3xl font-extrabold mb-2">درخواست‌های مشاوره</h1><p class="text-slate-500">فرم‌های ثبت‌شده از صفحه تماس و مشاوره در این بخش نمایش داده می‌شوند.</p></div>
  <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">تعداد کل: <?= fa_number(count($requests)) ?></div>
</div>

<?php if (!$requests): ?>
  <div class="admin-card p-10 text-center text-slate-500">هنوز درخواستی ثبت نشده است.</div>
<?php else: ?>
  <div class="space-y-5">
    <?php foreach ($requests as $request): ?>
      <?php $status = $request['status'] ?? 'new'; ?>
      <article class="admin-card p-6">
        <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-5">
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-3 mb-5">
              <h2 class="text-xl font-extrabold"><?= e($request['full_name'] ?? '') ?></h2>
              <span class="text-xs font-bold px-3 py-1 rounded-full <?= e($statusClasses[$status] ?? $statusClasses['new']) ?>"><?= e($statusLabels[$status] ?? 'جدید') ?></span>
              <span class="text-xs text-slate-400"><?= e($request['created_at'] ?? '') ?></span>
            </div>
            <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4 text-sm mb-5">
              <div class="bg-slate-50 rounded-xl p-4"><div class="text-slate-400 mb-1">شماره تماس</div><a class="font-bold text-emerald-700" dir="ltr" href="tel:<?= e($request['phone'] ?? '') ?>"><?= e($request['phone'] ?? '') ?></a></div>
              <div class="bg-slate-50 rounded-xl p-4"><div class="text-slate-400 mb-1">نوع کسب‌وکار</div><div class="font-bold"><?= e($request['business_type'] ?: 'ثبت نشده') ?></div></div>
              <div class="bg-slate-50 rounded-xl p-4"><div class="text-slate-400 mb-1">نوع درخواست</div><div class="font-bold"><?= e($request['project_type'] ?: 'ثبت نشده') ?></div></div>
              <div class="bg-slate-50 rounded-xl p-4"><div class="text-slate-400 mb-1">روش ارتباط / بودجه</div><div class="font-bold"><?= e($request['preferred_contact'] ?: 'ثبت نشده') ?></div><div class="text-slate-500 mt-1"><?= e($request['budget'] ?: 'بودجه ثبت نشده') ?></div></div>
            </div>
            <?php if (!empty($request['message'])): ?><div class="bg-slate-50 rounded-xl p-4 text-slate-700 leading-8 whitespace-pre-line"><?= e($request['message']) ?></div><?php endif; ?>
          </div>
          <form method="post" class="xl:w-52 shrink-0 bg-slate-50 rounded-xl p-4">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$request['id'] ?>">
            <label class="label" for="status-<?= (int)$request['id'] ?>">وضعیت پیگیری</label>
            <select class="field mb-3" id="status-<?= (int)$request['id'] ?>" name="status">
              <?php foreach ($statusLabels as $value => $label): ?><option value="<?= e($value) ?>" <?= $status === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
            </select>
            <button class="w-full bg-slate-900 text-white rounded-xl px-4 py-3 font-bold">ذخیره وضعیت</button>
          </form>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/_footer.php'; ?>
