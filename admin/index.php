<?php
require_once __DIR__ . '/../app/functions.php';
header('Location: ' . site_url(is_logged_in() ? 'admin/dashboard.php' : 'admin/login.php'));
exit;
