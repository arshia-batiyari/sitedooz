<?php
require_once __DIR__ . '/../app/functions.php';
$_SESSION = [];
session_destroy();
header('Location: ' . site_url('admin/login.php'));
exit;
