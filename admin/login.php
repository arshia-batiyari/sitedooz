<?php
require_once __DIR__ . '/../app/functions.php';
$error = '';
if (is_logged_in()) {
    header('Location: ' . site_url('admin/dashboard.php'));
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        header('Location: ' . site_url('admin/dashboard.php'));
        exit;
    }
    $error = 'نام کاربری یا رمز عبور اشتباه است.';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ورود به پنل مدیریت</title>
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
  <style>body{font-family:"Vazirmatn",sans-serif}.field{width:100%;border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;outline:none}.field:focus{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.12)}</style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
  <form method="post" class="bg-white w-full max-w-md rounded-3xl shadow-xl p-8 border border-slate-200">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <h1 class="text-3xl font-extrabold mb-2">ورود به پنل</h1>
    <?php if ($error): ?><div class="bg-red-50 text-red-600 rounded-xl p-3 mb-4 text-sm"><?= e($error) ?></div><?php endif; ?>
    <label class="block font-bold text-sm mb-2">نام کاربری</label>
    <input class="field mb-4" name="username" autocomplete="username" required>
    <label class="block font-bold text-sm mb-2">رمز عبور</label>
    <input class="field mb-6" name="password" type="password" autocomplete="current-password" required>
    <button class="w-full bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl py-3 font-extrabold">ورود</button>
  </form>
</body>
</html>
