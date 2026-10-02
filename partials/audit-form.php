<?php
$formId = $formId ?? 'audit';
$buttonLabel = $buttonLabel ?? 'تحلیل رایگان';
?>
<form class="audit-form" id="<?= e($formId) ?>" method="get" action="<?= e(insight_public_url() . '/') ?>" data-track="audit_started">
    <label class="sr-only" for="<?= e($formId) ?>-url">نشانی وب‌سایت</label>
    <input id="<?= e($formId) ?>-url" name="url" type="url" inputmode="url" autocomplete="url" placeholder="https://example.com" required>
    <input type="hidden" name="autostart" value="1">
    <button type="submit"><?= e($buttonLabel) ?></button>
</form>
<p class="audit-note">بدون نیاز به ثبت‌نام • تحلیل اولیه رایگان</p>
