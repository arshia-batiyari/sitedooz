<?php
require_once __DIR__ . '/../app/functions.php';
require_login();
$token = $_GET['csrf_token'] ?? '';
if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) { http_response_code(403); exit('درخواست نامعتبر است.'); }
delete_seo_item($_GET['id'] ?? '');
header('Location: ' . site_url('admin/seo.php'));
exit;
