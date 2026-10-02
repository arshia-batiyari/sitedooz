<?php
// مسیر پایه سایت. اگر پروژه را داخل پوشه نصب کردید، مثلا '/sitedooz' قرار دهید.
define('APP_BASE_URL', 'https://sitedooz.ir/');

// اطلاعات ورود پیش‌فرض ادمین: admin / siteDooz@1405
// بعد از نصب، حتما رمز را تغییر دهید و هش جدید را جایگزین کنید.
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$12$wFV8CYHPdiooIsMF8QlBVOB3tC897xlx1.NiPTcHHickkSZrHQHXm');

define('APP_NAME', 'سایت دوز');
define('UPLOAD_MAX_SIZE', 4 * 1024 * 1024); // 4MB


// =========================
// تنظیمات دیتابیس MySQL
// =========================
// برای استفاده از دیتابیس، مقدار DB_USE_MYSQL را true بگذارید و اطلاعات هاست را وارد کنید.
define('DB_USE_MYSQL', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'noshmakm_sitedooz_db');
define('DB_USER', 'noshmakm_sitedooz_db');
define('DB_PASS', 'r4Yo5afS57MTVmn9');
define('DB_CHARSET', 'utf8mb4');
