<?php
// آپلود تصویر داخل محتوای ادیتور (TinyMCE) برای بخش مدیریت بلاگ
require_once __DIR__ . '/../app/functions.php';

header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'ابتدا وارد پنل مدیریت شوید.']);
    exit;
}

$token = $_POST['csrf_token'] ?? '';
if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
    http_response_code(403);
    echo json_encode(['error' => 'درخواست نامعتبر است. صفحه را رفرش کنید و دوباره تلاش کنید.']);
    exit;
}

$path = upload_image('file');
if (!$path) {
    http_response_code(400);
    echo json_encode(['error' => 'آپلود تصویر ناموفق بود. فرمت مجاز: jpg, png, webp, gif و حداکثر حجم ۴ مگابایت.']);
    exit;
}

echo json_encode(['location' => asset_url($path)]);
