<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));

// ─── اتصال به دیتابیس ────────────────────────────────────────────────────────

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . $charset;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}

// ─── راه‌اندازی اسکیما ───────────────────────────────────────────────────────

function ensure_schema(): void
{
    static $done = false;
    if ($done) return;
    $done = true;

    $pdo = db();

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            setting_key   VARCHAR(120) NOT NULL PRIMARY KEY,
            setting_value TEXT         NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS posts (
            id               VARCHAR(40)  NOT NULL PRIMARY KEY,
            title            VARCHAR(255) NOT NULL,
            slug             VARCHAR(255) NOT NULL UNIQUE,
            category         VARCHAR(120) NULL,
            author           VARCHAR(120) NULL,
            status           ENUM('published','draft') NOT NULL DEFAULT 'published',
            published_at     DATE         NULL,
            cover            VARCHAR(255) NULL,
            image_alt        VARCHAR(255) NULL,
            meta_title       VARCHAR(255) NULL,
            meta_description TEXT         NULL,
            excerpt          TEXT         NULL,
            content          LONGTEXT     NULL,
            created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_posts_status_date (status, published_at),
            INDEX idx_posts_slug        (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS portfolio_items (
            id         VARCHAR(40)  NOT NULL PRIMARY KEY,
            title      VARCHAR(255) NOT NULL,
            subtitle   TEXT         NULL,
            image      VARCHAR(255) NULL,
            url        VARCHAR(255) NULL,
            status     ENUM('published','draft') NOT NULL DEFAULT 'published',
            sort_order INT          NOT NULL DEFAULT 0,
            created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_portfolio_status_sort (status, sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS seo_items (
            id         VARCHAR(40)  NOT NULL PRIMARY KEY,
            keyword    VARCHAR(255) NOT NULL,
            summary    TEXT         NULL,
            rank       VARCHAR(120) NULL,
            image      VARCHAR(255) NULL,
            status     ENUM('published','draft') NOT NULL DEFAULT 'published',
            sort_order INT          NOT NULL DEFAULT 0,
            created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_seo_status_sort (status, sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS consultation_requests (
            id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            full_name         VARCHAR(160) NOT NULL,
            phone             VARCHAR(40)  NOT NULL,
            business_type     VARCHAR(120) NULL,
            project_type      VARCHAR(120) NULL,
            preferred_contact VARCHAR(80)  NULL,
            budget            VARCHAR(120) NULL,
            message           TEXT         NULL,
            status            ENUM('new','contacted','done') NOT NULL DEFAULT 'new',
            ip_hash           CHAR(64)     NULL,
            user_agent        VARCHAR(500) NULL,
            created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_consultation_status_date (status, created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // اضافه کردن ستون‌های جدید برای دیتابیس‌های قدیمی
    $cols = array_column($pdo->query("SHOW COLUMNS FROM posts")->fetchAll(), 'Field');
    if (!in_array('image_alt', $cols)) {
        $pdo->exec("ALTER TABLE posts ADD COLUMN image_alt VARCHAR(255) NULL AFTER cover");
    }

    // اگر settings خالی است، مقادیر پیش‌فرض را درج کن
    $count = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
    if ((int)$count === 0) {
        $s = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (:k, :v)");
        foreach (default_settings() as $k => $v) {
            $s->execute([':k' => $k, ':v' => $v]);
        }
    }
}

// ─── مقادیر پیش‌فرض تنظیمات ──────────────────────────────────────────────────

function default_settings(): array
{
    return [
        'site_title'         => 'سایت دوز | طراحی سایت حرفه‌ای',
        'site_description'   => 'طراحی سایت سریع، سبک و قابل مدیریت برای کسب‌وکارهایی که می‌خواهند حضور آنلاین حرفه‌ای داشته باشند.',
        'phone'              => '09334496439',
        'whatsapp'           => '09334496439',
        'hero_title'         => 'طراحی سایت حرفه‌ای، سریع و قابل مدیریت',
        'hero_description'   => 'سایت دوز وب‌سایتی تمیز، واکنش‌گرا و قابل مدیریت می‌سازد؛ با پنل ساده برای محتوا، نمونه‌کار، تصاویر و مقالات.',
        'footer_title'       => 'برای شروع طراحی سایت آماده‌اید؟',
        'footer_description' => 'برای بررسی نیازهای سایت و انتخاب ساختار مناسب، با سایت دوز تماس بگیرید.',
    ];
}

// ─── توابع خواندن داده‌ها ─────────────────────────────────────────────────────

function get_settings(): array
{
    ensure_schema();
    $rows = db()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
    $settings = [];
    foreach ($rows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings ?: default_settings();
}

function get_posts(bool $publishedOnly = false): array
{
    ensure_schema();
    $sql = 'SELECT * FROM posts' . ($publishedOnly ? " WHERE status='published'" : '') . ' ORDER BY published_at DESC, id DESC';
    return array_map('_map_post', db()->query($sql)->fetchAll());
}

function get_post_by_id(string $id): ?array
{
    ensure_schema();
    $stmt = db()->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row ? _map_post($row) : null;
}

function get_post_by_slug(string $slug): ?array
{
    ensure_schema();
    $stmt = db()->prepare("SELECT * FROM posts WHERE slug = :slug AND status = 'published' LIMIT 1");
    $stmt->execute([':slug' => $slug]);
    $row = $stmt->fetch();
    return $row ? _map_post($row) : null;
}

function _map_post(array $row): array
{
    return [
        'id'               => $row['id'],
        'title'            => $row['title']            ?? '',
        'slug'             => $row['slug']             ?? '',
        'category'         => $row['category']         ?? '',
        'author'           => $row['author']           ?? '',
        'status'           => $row['status']           ?? 'published',
        'published_at'     => $row['published_at']     ?? '',
        'cover'            => $row['cover']            ?? '',
        'image_alt'        => $row['image_alt']        ?? '',
        'meta_title'       => $row['meta_title']       ?? '',
        'meta_description' => $row['meta_description'] ?? '',
        'excerpt'          => $row['excerpt']          ?? '',
        'content'          => $row['content']          ?? '',
    ];
}

function get_portfolio(bool $publishedOnly = false): array
{
    ensure_schema();
    $sql = 'SELECT * FROM portfolio_items' . ($publishedOnly ? " WHERE status='published'" : '') . ' ORDER BY sort_order ASC, id DESC';
    return array_map(fn($row) => [
        'id'       => $row['id'],
        'title'    => $row['title']    ?? '',
        'subtitle' => $row['subtitle'] ?? '',
        'image'    => $row['image']    ?? '',
        'url'      => $row['url']      ?? '#',
        'status'   => $row['status']   ?? 'published',
    ], db()->query($sql)->fetchAll());
}

function get_portfolio_item(string $id): ?array
{
    ensure_schema();
    $stmt = db()->prepare('SELECT * FROM portfolio_items WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    if (!$row) return null;
    return ['id' => $row['id'], 'title' => $row['title'] ?? '', 'subtitle' => $row['subtitle'] ?? '', 'image' => $row['image'] ?? '', 'url' => $row['url'] ?? '#', 'status' => $row['status'] ?? 'published'];
}

function get_seo_items(bool $publishedOnly = false): array
{
    ensure_schema();
    $sql = 'SELECT * FROM seo_items' . ($publishedOnly ? " WHERE status='published'" : '') . ' ORDER BY sort_order ASC, id DESC';
    return array_map(fn($row) => [
        'id'      => $row['id'],
        'keyword' => $row['keyword'] ?? '',
        'summary' => $row['summary'] ?? '',
        'rank'    => $row['rank']    ?? '',
        'image'   => $row['image']   ?? '',
        'status'  => $row['status']  ?? 'published',
    ], db()->query($sql)->fetchAll());
}

function get_seo_item(string $id): ?array
{
    ensure_schema();
    $stmt = db()->prepare('SELECT * FROM seo_items WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    if (!$row) return null;
    return ['id' => $row['id'], 'keyword' => $row['keyword'] ?? '', 'summary' => $row['summary'] ?? '', 'rank' => $row['rank'] ?? '', 'image' => $row['image'] ?? '', 'status' => $row['status'] ?? 'published'];
}

// ─── توابع ذخیره داده‌ها ──────────────────────────────────────────────────────

function save_post(array $p): void
{
    ensure_schema();
    db()->prepare("
        INSERT INTO posts (id,title,slug,category,author,status,published_at,cover,image_alt,meta_title,meta_description,excerpt,content)
        VALUES (:id,:title,:slug,:category,:author,:status,:published_at,:cover,:image_alt,:meta_title,:meta_description,:excerpt,:content)
        ON DUPLICATE KEY UPDATE
            title=VALUES(title), slug=VALUES(slug), category=VALUES(category), author=VALUES(author),
            status=VALUES(status), published_at=VALUES(published_at), cover=VALUES(cover),
            image_alt=VALUES(image_alt), meta_title=VALUES(meta_title),
            meta_description=VALUES(meta_description), excerpt=VALUES(excerpt), content=VALUES(content)
    ")->execute([
        ':id'               => $p['id'],
        ':title'            => $p['title']            ?? '',
        ':slug'             => $p['slug']             ?? slugify($p['title'] ?? ''),
        ':category'         => $p['category']         ?? '',
        ':author'           => $p['author']           ?? '',
        ':status'           => $p['status']           ?? 'published',
        ':published_at'     => $p['published_at']     ?: now_date(),
        ':cover'            => $p['cover']            ?? '',
        ':image_alt'        => $p['image_alt']        ?? '',
        ':meta_title'       => $p['meta_title']       ?? '',
        ':meta_description' => $p['meta_description'] ?? '',
        ':excerpt'          => $p['excerpt']          ?? '',
        ':content'          => $p['content']          ?? '',
    ]);
}

function delete_post(string $id): void
{
    ensure_schema();
    $stmt = db()->prepare('DELETE FROM posts WHERE id = :id');
    $stmt->execute([':id' => $id]);
}

function save_portfolio_item(array $item, int $sortOrder = 0): void
{
    ensure_schema();
    db()->prepare("
        INSERT INTO portfolio_items (id,title,subtitle,image,url,status,sort_order)
        VALUES (:id,:title,:subtitle,:image,:url,:status,:sort_order)
        ON DUPLICATE KEY UPDATE
            title=VALUES(title), subtitle=VALUES(subtitle), image=VALUES(image),
            url=VALUES(url), status=VALUES(status), sort_order=VALUES(sort_order)
    ")->execute([
        ':id'         => $item['id'],
        ':title'      => $item['title']    ?? '',
        ':subtitle'   => $item['subtitle'] ?? '',
        ':image'      => $item['image']    ?? '',
        ':url'        => $item['url']      ?? '#',
        ':status'     => $item['status']   ?? 'published',
        ':sort_order' => $sortOrder,
    ]);
}

function delete_portfolio_item(string $id): void
{
    ensure_schema();
    db()->prepare('DELETE FROM portfolio_items WHERE id = :id')->execute([':id' => $id]);
}

function save_seo_item(array $item, int $sortOrder = 0): void
{
    ensure_schema();
    db()->prepare("
        INSERT INTO seo_items (id,keyword,summary,rank,image,status,sort_order)
        VALUES (:id,:keyword,:summary,:rank,:image,:status,:sort_order)
        ON DUPLICATE KEY UPDATE
            keyword=VALUES(keyword), summary=VALUES(summary), rank=VALUES(rank),
            image=VALUES(image), status=VALUES(status), sort_order=VALUES(sort_order)
    ")->execute([
        ':id'         => $item['id'],
        ':keyword'    => $item['keyword'] ?? '',
        ':summary'    => $item['summary'] ?? '',
        ':rank'       => $item['rank']    ?? '',
        ':image'      => $item['image']   ?? '',
        ':status'     => $item['status']  ?? 'published',
        ':sort_order' => $sortOrder,
    ]);
}

function delete_seo_item(string $id): void
{
    ensure_schema();
    db()->prepare('DELETE FROM seo_items WHERE id = :id')->execute([':id' => $id]);
}

function save_consultation_request(array $request): int
{
    ensure_schema();
    $stmt = db()->prepare("
        INSERT INTO consultation_requests
            (full_name, phone, business_type, project_type, preferred_contact, budget, message, ip_hash, user_agent)
        VALUES
            (:full_name, :phone, :business_type, :project_type, :preferred_contact, :budget, :message, :ip_hash, :user_agent)
    ");
    $stmt->execute([
        ':full_name'         => $request['full_name'] ?? '',
        ':phone'             => $request['phone'] ?? '',
        ':business_type'     => $request['business_type'] ?? '',
        ':project_type'      => $request['project_type'] ?? '',
        ':preferred_contact' => $request['preferred_contact'] ?? '',
        ':budget'            => $request['budget'] ?? '',
        ':message'           => $request['message'] ?? '',
        ':ip_hash'           => $request['ip_hash'] ?? '',
        ':user_agent'        => app_substr((string)($request['user_agent'] ?? ''), 0, 500),
    ]);
    return (int) db()->lastInsertId();
}

function get_consultation_requests(): array
{
    ensure_schema();
    return db()->query('SELECT * FROM consultation_requests ORDER BY created_at DESC, id DESC')->fetchAll();
}

function update_consultation_status(int $id, string $status): void
{
    ensure_schema();
    $allowed = ['new', 'contacted', 'done'];
    if (!in_array($status, $allowed, true)) return;
    $stmt = db()->prepare('UPDATE consultation_requests SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $id]);
}

function save_settings(array $settings): void
{
    ensure_schema();
    $pdo = db();
    $s = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)
        ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");
    foreach ($settings as $k => $v) {
        $s->execute([':k' => $k, ':v' => (string)$v]);
    }
}

// ─── توابع کمکی URL ──────────────────────────────────────────────────────────

function detected_base_url(): string
{
    $configured = trim((string) APP_BASE_URL);
    if ($configured !== '') return rtrim($configured, '/');
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if ($scriptName === '') return '';
    $adminPos = strpos($scriptName, '/admin/');
    $base = $adminPos !== false ? substr($scriptName, 0, $adminPos) : dirname($scriptName);
    $base = rtrim(str_replace('\\', '/', $base), '/');
    return ($base === '' || $base === '.') ? '' : $base;
}

function site_url(string $path = ''): string
{
    $base = detected_base_url();
    $path = ltrim($path, '/');
    if ($path === '') return $base === '' ? '/' : $base . '/';
    return ($base === '' ? '' : $base) . '/' . $path;
}

function insight_public_url(): string
{
    $configured = getenv('INSIGHT_PUBLIC_URL');
    if (is_string($configured) && $configured !== '') {
        return rtrim($configured, '/');
    }
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    if (str_starts_with($host, '127.0.0.1') || str_starts_with($host, 'localhost')) {
        return 'http://127.0.0.1:8000';
    }

    return 'https://insight.sitedooz.ir';
}

function asset_url(string $path): string { return site_url($path); }

function absolute_url(string $path = ''): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $local  = site_url($path);
    if (str_starts_with($local, 'http://') || str_starts_with($local, 'https://')) return $local;
    return $scheme . '://' . $host . $local;
}

function post_url(array $post): string
{
    return site_url('blog/' . rawurlencode($post['slug'] ?? ''));
}

// ─── توابع کمکی متن ──────────────────────────────────────────────────────────

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function hero_title_highlighted(string $title): string
{
    $escaped = e($title);
    $candidates = ['طراحی سایت', 'سئو سایت', 'سئو', 'طراحی'];
    foreach ($candidates as $phrase) {
        $needle = e($phrase);
        $pos = mb_strpos($escaped, $needle);
        if ($pos !== false) {
            $before = mb_substr($escaped, 0, $pos);
            $after = mb_substr($escaped, $pos + mb_strlen($needle));
            return $before . '<span class="hero-title-accent">' . $needle . '</span>' . $after;
        }
    }
    return $escaped;
}

function app_strtolower(string $text): string
{
    return function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
}

function app_strlen(string $text): int
{
    return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
}

function app_substr(string $text, int $start, int $length): string
{
    return function_exists('mb_substr') ? mb_substr($text, $start, $length, 'UTF-8') : substr($text, $start, $length);
}

function app_stripos(string $haystack, string $needle): int|false
{
    return function_exists('mb_stripos') ? mb_stripos($haystack, $needle, 0, 'UTF-8') : stripos($haystack, $needle);
}

function fa_number(string|int|float|null $value): string
{
    return str_replace(['0','1','2','3','4','5','6','7','8','9'], ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], (string) $value);
}

function now_date(): string { return date('Y-m-d'); }

function format_date(?string $date): string
{
    if (!$date) return '';
    $time = strtotime($date);
    return $time ? fa_number(date('Y/m/d', $time)) : e($date);
}

function make_id(string $prefix = 'item'): string
{
    return $prefix . '_' . bin2hex(random_bytes(6));
}

function slugify(string $text): string
{
    $text = trim(app_strtolower($text));
    $text = preg_replace('/[\s_]+/u', '-', $text);
    $text = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $text);
    $text = preg_replace('/-+/u', '-', $text);
    $text = trim($text, '-');
    return $text !== '' ? $text : 'post-' . date('YmdHis');
}

function excerpt(string $text, int $limit = 160): string
{
    $plain = trim(strip_tags($text));
    if (app_strlen($plain) <= $limit) return $plain;
    return app_substr($plain, 0, $limit) . '...';
}

function render_rich_text(string $content): string
{
    $allowed = '<p><h2><h3><h4><ul><ol><li><strong><b><em><i><u><s><a><br><blockquote><img><figure><figcaption><span><table><thead><tbody><tr><th><td><hr><code><pre>';
    if ($content !== strip_tags($content)) {
        return strip_tags($content, $allowed);
    }
    return nl2br(e($content));
}

/**
 * از داخل HTML مقاله، تیترهای h2/h3 را پیدا می‌کند، برای هرکدام یک id یکتا
 * می‌سازد و آرایه فهرست مطالب را همراه با محتوای اصلاح‌شده برمی‌گرداند.
 */
function build_article_toc(string $html): array
{
    $toc = [];
    $counter = 0;
    $used = [];
    $processed = preg_replace_callback('/<h([23])(\s[^>]*)?>(.*?)<\/h\1>/isu', function ($m) use (&$toc, &$counter, &$used) {
        $level = (int) $m[1];
        $text = trim(strip_tags($m[3]));
        if ($text === '') return $m[0];
        $counter++;
        $base = slugify($text);
        if ($base === '' || strlen($base) > 60) $base = 'بخش-' . $counter;
        $id = 'toc-' . $counter . '-' . $base;
        $i = 2;
        while (isset($used[$id])) { $id = 'toc-' . $counter . '-' . $base . '-' . $i; $i++; }
        $used[$id] = true;
        $toc[] = ['id' => $id, 'text' => $text, 'level' => $level];
        return '<h' . $level . ' id="' . $id . '">' . $m[3] . '</h' . $level . '>';
    }, $html);
    return ['content' => $processed, 'toc' => $toc];
}

/**
 * مقالات مشابه را برای نمایش در سایدبار جزئیات بلاگ برمی‌گرداند.
 * ابتدا مقالات هم‌دسته و در ادامه سایر مقالات جدید را انتخاب می‌کند.
 */
function get_related_posts(array $currentPost, int $limit = 6): array
{
    $all = array_values(array_filter(get_posts(true), function ($p) use ($currentPost) {
        return ($p['id'] ?? null) !== ($currentPost['id'] ?? null);
    }));

    $sameCategory = array_values(array_filter($all, function ($p) use ($currentPost) {
        return !empty($currentPost['category']) && ($p['category'] ?? '') === $currentPost['category'];
    }));
    $others = array_values(array_filter($all, function ($p) use ($currentPost) {
        return !(!empty($currentPost['category']) && ($p['category'] ?? '') === $currentPost['category']);
    }));

    $ordered = array_merge($sameCategory, $others);
    return array_slice($ordered, 0, $limit);
}

function published_items(array $items): array
{
    $items = array_values(array_filter($items, fn($item) => ($item['status'] ?? 'published') === 'published'));
    usort($items, fn($a, $b) => strcmp($b['published_at'] ?? '', $a['published_at'] ?? ''));
    return $items;
}

// ─── آپلود تصویر ─────────────────────────────────────────────────────────────

function upload_image(string $fieldName): ?string
{
    if (empty($_FILES[$fieldName]) || ($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    $file = $_FILES[$fieldName];
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) return null;
    if (($file['size'] ?? 0) > UPLOAD_MAX_SIZE) return null;
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $map  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!isset($map[$mime])) return null;
    if (!is_dir(ROOT_PATH . '/uploads')) mkdir(ROOT_PATH . '/uploads', 0755, true);
    $name = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $map[$mime];
    $dest = ROOT_PATH . '/uploads/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
    return 'uploads/' . $name;
}

// ─── احراز هویت و امنیت ──────────────────────────────────────────────────────

function is_logged_in(): bool { return !empty($_SESSION['admin_logged_in']); }

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: ' . site_url('admin/login.php'));
        exit;
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('درخواست نامعتبر است. صفحه را رفرش کنید و دوباره تلاش کنید.');
    }
}

// ─── آیکون‌های SVG ───────────────────────────────────────────────────────────

function svg_icon(string $name, string $class = 'w-5 h-5'): string
{
    $icons = [
        'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.08 5.18 2 2 0 0 1 5.06 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.63 2.62a2 2 0 0 1-.45 2.11L9 10.7a16 16 0 0 0 4.3 4.3l1.25-1.24a2 2 0 0 1 2.11-.45c.84.3 1.72.51 2.62.63A2 2 0 0 1 22 16.92z"/>',
        'laptop'    => '<path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9H4V5z"/><path d="M2 18h20"/><path d="M8 18h8"/>',
        'cart'      => '<path d="M6 6h15l-1.5 8.5a2 2 0 0 1-2 1.5H9a2 2 0 0 1-2-1.6L5 3H2"/><circle cx="9" cy="21" r="1"/><circle cx="18" cy="21" r="1"/>',
        'chart'     => '<path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 15l3-4 3 2 5-7"/>',
        'search'    => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
        'rocket'    => '<path d="M5 15c-1 1.4-1.5 3-1.5 5 2 0 3.6-.5 5-1.5"/><path d="M15 4c3-1 5-1 5-1s0 2-1 5c-.9 2.7-2.7 5.7-6 9l-6-6c3.3-3.3 6.3-5.1 8-7z"/><path d="M9 15l-1 4 4-1"/><circle cx="15" cy="9" r="1.5"/>',
        'check'     => '<path d="M20 6L9 17l-5-5"/>',
        'sparkles'  => '<path d="M12 3l1.8 4.4L18 9l-4.2 1.6L12 15l-1.8-4.4L6 9l4.2-1.6L12 3z"/><path d="M19 14l.9 2.1L22 17l-2.1.9L19 20l-.9-2.1L16 17l2.1-.9L19 14z"/><path d="M5 15l.7 1.6L7 17l-1.3.4L5 19l-.7-1.6L3 17l1.3-.4L5 15z"/>',
        'speed'     => '<path d="M4 14a8 8 0 1 1 16 0"/><path d="M12 14l4-4"/><path d="M5 19h14"/>',
        'palette'   => '<path d="M12 3a9 9 0 0 0 0 18h1.2a2 2 0 0 0 1.4-3.4 1.7 1.7 0 0 1 1.2-2.9H17a4 4 0 0 0 4-4C21 6.4 17 3 12 3z"/><circle cx="7.5" cy="10" r="1"/><circle cx="10" cy="7" r="1"/><circle cx="14" cy="7" r="1"/><circle cx="16.5" cy="10" r="1"/>',
        'chevron'   => '<path d="M6 9l6 6 6-6"/>',
        'arrow'     => '<path d="M5 12h14"/><path d="M13 5l7 7-7 7"/>',
        'gauge'     => '<path d="M4 14a8 8 0 1 1 16 0"/><path d="M12 14l3-3"/><path d="M5 19h14"/>',
        'newspaper' => '<path d="M4 5h14a2 2 0 0 1 2 2v12H6a2 2 0 0 1-2-2V5z"/><path d="M8 9h8M8 13h8M8 17h5"/>',
        'briefcase' => '<path d="M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1"/><rect x="3" y="6" width="18" height="14" rx="2"/><path d="M3 12h18"/>',
        'gear'      => '<path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/><path d="M4 12h2M18 12h2M12 4v2M12 18v2M6.3 6.3l1.4 1.4M16.3 16.3l1.4 1.4M17.7 6.3l-1.4 1.4M7.7 16.3l-1.4 1.4"/>',
        'external'  => '<path d="M14 3h7v7"/><path d="M10 14L21 3"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/>',
        'logout'    => '<path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 19V5a2 2 0 0 0-2-2h-5"/>',
        'star'      => '<path d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 16.9 6.6 19.8l1-6.1-4.4-4.3 6.1-.9L12 3z"/>',
        'list'      => '<path d="M9 6h11M9 12h11M9 18h11"/><circle cx="4.2" cy="6" r="1.2"/><circle cx="4.2" cy="12" r="1.2"/><circle cx="4.2" cy="18" r="1.2"/>',
    ];
    $path = $icons[$name] ?? $icons['sparkles'];
    return '<svg class="svg-icon ' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}

// ─── رندر head صفحات عمومی ───────────────────────────────────────────────────

function render_public_head(string $title, string $description, string $canonical = '', string $image = '', bool $preloadHero = false, array $extraStyles = []): void
{
    $canonical = $canonical ?: site_url(ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/'));
    // prefer existing optimized assets
    $defaultImage = 'images/logo-optimized.webp';
    $imageUrl  = $image ? absolute_url($image) : absolute_url($defaultImage);
    $siteName = defined('APP_NAME') ? APP_NAME : 'سایت دوز';
    $ogType = (str_contains($canonical, '/blog/') ? 'article' : 'website');
    ?>
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
      <meta name="theme-color" content="#0f172a" />
      <meta name="author" content="<?= e($siteName) ?>" />
      <title><?= e($title) ?></title>
      <meta name="description" content="<?= e($description) ?>" />
      <link rel="canonical" href="<?= e($canonical) ?>" />
      <!-- Open Graph -->
      <meta property="og:locale" content="fa_IR" />
      <meta property="og:site_name" content="<?= e($siteName) ?>" />
      <meta property="og:title" content="<?= e($title) ?>" />
      <meta property="og:description" content="<?= e($description) ?>" />
      <meta property="og:type" content="<?= e($ogType) ?>" />
      <meta property="og:url" content="<?= e($canonical) ?>" />
      <meta property="og:image" content="<?= e($imageUrl) ?>" />
      <meta property="og:image:alt" content="<?= e($title) ?>" />
      <!-- Twitter -->
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content="<?= e($title) ?>" />
      <meta name="twitter:description" content="<?= e($description) ?>" />
      <meta name="twitter:image" content="<?= e($imageUrl) ?>" />
      <link rel="icon" href="<?= asset_url('images/favicon.ico') ?>" type="image/x-icon" />
      <link rel="apple-touch-icon" href="<?= asset_url('images/logo-optimized.webp') ?>" />
      <!--
        ⚠️ عملکرد: این خط از Tailwind CDN JIT استفاده می‌کند که رسمی Tailwind فقط برای dev/demo توصیه شده
        و چون کل کامپایلر را به مرورگر می‌فرستد و runtime کامپایل می‌کند، بزرگ‌ترین مانع رسیدن Lighthouse به بالای ۹۰ است.

        قبل از دیپلوی نهایی، این ۳ قدم را یک‌بار روی سیستم خودتان اجرا کنید (نیاز به Node.js دارد):
          1) npm install
          2) npm run build:css   → فایل css/tailwind-build.css را می‌سازد
          3) خط <script src="https://cdn.tailwindcss.com"> زیر را کامنت کنید و خط
             <link href="...css/tailwind-build.css" rel="stylesheet"> را از کامنت خارج کنید.

        این پروژه از کلاس‌های arbitrary زیاد استفاده می‌کند (مثل rounded-[2rem])، پس tailwind.config.js
        از قبل content: ["./**/*.php"] دارد و همه را اسکن می‌کند؛ فقط کافیست build را اجرا کنید.
      -->
      <!-- <link href="<?= asset_url('css/tailwind-build.css') ?>" rel="stylesheet" /> -->
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
      <link rel="preload" href="<?= asset_url('fonts/webfonts/Vazirmatn[wght].woff2') ?>" as="font" type="font/woff2" crossorigin>
<?php if ($preloadHero): ?>
      <link rel="preload" href="<?= asset_url('images/hero-illustration.svg') ?>" as="image" fetchpriority="high" media="(min-width: 768px)">
      <link rel="preload" href="<?= asset_url('images/hero-illustration-mobile.svg') ?>" as="image" fetchpriority="high" media="(max-width: 767px)">
      <?php endif; ?>
      <link href="<?= asset_url('css/Vazirmatn-font-face.css') ?>" rel="stylesheet" />
      <!-- JSON-LD Structured Data for better SEO & rich results -->
      <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@graph": [
          {
            "@type": "LocalBusiness",
            "@id": "<?= e(absolute_url()) ?>#organization",
            "name": "<?= e($siteName) ?>",
            "url": "<?= e(absolute_url()) ?>",
            "image": "<?= e(absolute_url('images/logo-optimized.webp')) ?>",
            "priceRange": "$$",
            "areaServed": {
              "@type": "City",
              "name": "گرگان"
            },
            "logo": {
              "@type": "ImageObject",
              "url": "<?= e(absolute_url('images/logo-optimized.webp')) ?>"
            },
            "description": "طراحی سایت و سئو در گرگان با کیفیت بالا و قیمت مناسب",
            "address": {
              "@type": "PostalAddress",
              "addressLocality": "گرگان",
              "addressRegion": "گلستان",
              "addressCountry": "IR"
            },
            "contactPoint": {
              "@type": "ContactPoint",
              "telephone": "+98-933-449-6439",
              "contactType": "customer service",
              "availableLanguage": "Persian"
            },
            "sameAs": []
          },
          {
            "@type": "WebSite",
            "@id": "<?= e(absolute_url()) ?>#website",
            "url": "<?= e(absolute_url()) ?>",
            "name": "<?= e($siteName) ?>",
            "description": "<?= e($description) ?>",
            "publisher": { "@id": "<?= e(absolute_url()) ?>#organization" },
            "inLanguage": "fa-IR",
            "potentialAction": {
              "@type": "SearchAction",
              "target": "<?= e(absolute_url('blog')) ?>?q={search_term_string}",
              "query-input": "required name=search_term_string"
            }
          },
          {
            "@type": "WebPage",
            "@id": "<?= e($canonical) ?>#webpage",
            "url": "<?= e($canonical) ?>",
            "name": "<?= e($title) ?>",
            "description": "<?= e($description) ?>",
            "isPartOf": { "@id": "<?= e(absolute_url()) ?>#website" },
            "about": { "@id": "<?= e(absolute_url()) ?>#organization" },
            "inLanguage": "fa-IR"
          }
        ]
      }
      </script>
      <style>

          *, *::before, *::after { box-sizing: border-box; }
        .scroll-progress-bar { position:fixed; top:50%; left:18px; transform:translateY(-50%); width:4px; height:120px; background:rgba(148,163,184,.22); border-radius:999px; z-index:9998; pointer-events:none; box-shadow:inset 0 0 0 1px rgba(255,255,255,.4); backdrop-filter:blur(2px); opacity:0; transition:opacity .35s ease; }
        .scroll-progress-bar.is-active { opacity:1; }
        .scroll-progress-fill { position:relative; display:block; width:100%; height:0%; border-radius:999px; background:linear-gradient(180deg,#5eead4,#10b981 55%,#0d9488); transition:height .12s linear; box-shadow:0 0 10px rgba(16,185,129,.45); }
        .scroll-progress-fill:after { content:""; position:absolute; bottom:-4px; left:50%; transform:translateX(-50%); width:9px; height:9px; border-radius:50%; background:radial-gradient(circle at 35% 30%, #d1fae5, #10b981 60%, #059669); box-shadow:0 0 10px rgba(16,185,129,.75); z-index:2; }
        .scroll-progress-fill:before { content:""; position:absolute; bottom:-4px; left:50%; width:9px; height:9px; margin-left:-4.5px; border-radius:50%; background:transparent; animation:scrollHaloPulse 1.8s ease-out infinite; }
        @keyframes scrollHaloPulse { 0% { box-shadow:0 0 0 0 rgba(16,185,129,.5); } 70% { box-shadow:0 0 0 10px rgba(16,185,129,0); } 100% { box-shadow:0 0 0 0 rgba(16,185,129,0); } }
        @media (max-width:767px) { .scroll-progress-bar { left:10px; height:90px; } .scroll-progress-fill:after, .scroll-progress-fill:before { width:8px; height:8px; } }
        @media (prefers-reduced-motion: reduce) { .scroll-progress-fill { transition:none; } .scroll-progress-fill:before { animation:none; } }
        html { scroll-behavior: smooth; max-width: 100%; overflow-x: clip; }
        body { font-family: "Vazirmatn", sans-serif; max-width: 100%; overflow-x: clip; text-rendering: optimizeLegibility; }
        img, svg, video, canvas { max-width: 100%; }
        main, header, footer, section { max-width: 100%; }
        .svg-icon { display:inline-block; vertical-align:-0.18em; flex-shrink:0; }
        .logo-box { width: 119px; height: 60px; border-radius: 18px; overflow: hidden;display: flex; align-items: center; justify-content: center; }
        a:focus-visible, button:focus-visible, input:focus-visible { outline: 2px solid #0f766e; outline-offset: 3px; }
        .nav-audit { display:inline-flex; align-items:center; justify-content:center; background:#0f766e; color:#fff; font-weight:800; border-radius:999px; padding:.55rem .9rem; white-space:nowrap; }
        .nav-audit:hover { background:#115e59; color:#fff; }
        .hero-bg { position:relative; overflow:hidden; background: radial-gradient(circle at 82% 18%, rgba(45,212,191,.28), transparent 32%), radial-gradient(circle at 18% 82%, rgba(56,189,248,.18), transparent 35%), linear-gradient(135deg, #071321 0%, #0f172a 42%, #0f766e 100%); }
        .hero-bg:before { content:""; position:absolute; inset:-25%; background: linear-gradient(120deg, transparent 34%, rgba(255,255,255,.055) 50%, transparent 66%); animation: shine 12s linear infinite; pointer-events:none; }
        .hero-bg .hero-grid-pattern { position:absolute; inset:0; background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px); background-size:44px 44px; -webkit-mask-image:radial-gradient(ellipse 70% 60% at 60% 30%, #000 40%, transparent 85%); mask-image:radial-gradient(ellipse 70% 60% at 60% 30%, #000 40%, transparent 85%); pointer-events:none; }
        .hero-bg .hero-orb { position:absolute; border-radius:50%; filter:blur(60px); pointer-events:none; opacity:.55; animation: heroOrbDrift 14s ease-in-out infinite; }
        .hero-bg .hero-orb--1 { width:280px; height:280px; top:-60px; left:8%; background:radial-gradient(circle, rgba(45,212,191,.55), transparent 70%); }
        .hero-bg .hero-orb--2 { width:340px; height:340px; bottom:-100px; right:6%; background:radial-gradient(circle, rgba(56,189,248,.45), transparent 70%); animation-delay:2.5s; animation-duration:17s; }
        .hero-title-accent { background:linear-gradient(90deg,#5eead4,#38bdf8 60%,#818cf8); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .hero-visual-frame { position:relative; border-radius:1.5rem; overflow:hidden; box-shadow:0 30px 60px -20px rgba(2,6,23,.6), 0 0 0 1px rgba(255,255,255,.08), 0 25px 50px -20px rgba(16,185,129,.35); transform-style:preserve-3d; transition:transform .25s ease; background:#0b1220; border:1px solid rgba(255,255,255,.08); }
        .hero-visual-frame img { display:block; width:100%; height:auto; aspect-ratio:auto; object-fit:contain; }
        .hero-visual-chrome { display:flex; align-items:center; gap:.4rem; padding:.65rem .9rem; background:#111827; position:relative; z-index:5; }
        .hero-visual-chrome span { width:9px; height:9px; border-radius:50%; background:#475569; }
        .hero-visual-chrome span:nth-child(1) { background:#f87171; }
        .hero-visual-chrome span:nth-child(2) { background:#fbbf24; }
        .hero-visual-chrome span:nth-child(3) { background:#34d399; }
        .hero-visual-url { margin-inline-start:.75rem; background:#1e293b; color:rgba(255,255,255,.55); font-size:.72rem; padding:.2rem .8rem; border-radius:999px; direction:ltr; }
        @media (max-width:767px) { .hero-visual-frame { transform:none !important; } }
        @media (prefers-reduced-motion: reduce) { .hero-visual-frame { transition:none; } }
        @keyframes heroOrbDrift { 0%,100% { transform:translate(0,0) scale(1); } 50% { transform:translate(18px,-22px) scale(1.08); } }
        @media (prefers-reduced-motion: reduce), (max-width: 767px) { .hero-bg .hero-orb { animation:none; } }
        @media (max-width: 767px) { .hero-bg .hero-grid-pattern, .hero-bg .hero-orb { display:none; } }
        .hero-bg-mobile-glow { display:none; }
        @media (max-width: 767px) { .hero-bg-mobile-glow { display:block; position:absolute; top:-10%; right:-20%; width:70%; padding-bottom:70%; border-radius:50%; background:radial-gradient(circle, rgba(45,212,191,.22), transparent 70%); pointer-events:none; } }
        .site-gradient-text { background:linear-gradient(90deg,#14b8a6,#38bdf8); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .glass-card { background:rgba(255,255,255,.11); border:1px solid rgba(255,255,255,.18); box-shadow:0 18px 46px rgba(2,6,23,.18); backdrop-filter: blur(14px); }
        .soft-card { background:rgba(255,255,255,.86); border:1px solid rgba(226,232,240,.8); box-shadow:0 20px 50px rgba(15,23,42,.08); }
        .card, .hover-lift { transition: transform .5s cubic-bezier(.16,1,.3,1), box-shadow .5s cubic-bezier(.16,1,.3,1), border-color .4s ease, filter .4s ease; }
		.hero-image-wrap { position: relative; overflow: visible; }
.hero-image-wrap img { position: relative; z-index: 10; }
.hero-mobile-cards .hero-mobile-card {animation: cardUp .7s cubic-bezier(.16,1,.3,1) both;}
.hero-mobile-cards .hero-mobile-card:nth-child(1) { animation-delay: .08s; }
.hero-mobile-cards .hero-mobile-card:nth-child(2) { animation-delay: .18s; }
.hero-mobile-cards .hero-icon {animation: iconPop 1.6s ease-in-out infinite;}
@keyframes cardUp {from{ opacity: 0; transform: translateY(16px) scale(.97); filter: blur(6px); }to{ opacity: 1; transform: translateY(0) scale(1) filter: blur(0); }}
@keyframes iconPop {0%, 100% { transform: scale(1); }50% { transform: scale(1.08); }}
@media (max-width: 767px) {.soft-float { animation: none; }}
@media (prefers-reduced-motion: reduce), (max-width: 767px) { .soft-float, .pulse-glow, .hero-bg:before { animation:none!important; } }
.hero-image-badge { position: absolute; z-index: 20; }
        .card:hover, .hover-lift:hover { transform: translateY(-4px); box-shadow:0 16px 38px rgba(15,23,42,.09); border-color:rgba(16,185,129,.18); }
        .card img, .hover-lift img { transition: transform .65s cubic-bezier(.16,1,.3,1), filter .55s ease; }
        .card:hover img, .hover-lift:hover img { transform: scale(1.025); filter:saturate(1.04); }
        .service-card { position:relative; overflow:hidden; }
        .service-card:before { content:""; position:absolute; inset:auto -30% -55% -30%; height:170px; background:radial-gradient(circle, rgba(16,185,129,.16), transparent 62%); transition:.4s; }
        .service-card:hover:before { transform:translateY(-22px) scale(1.03); }
        .btn-main { transition: transform .45s cubic-bezier(.16,1,.3,1), box-shadow .45s ease, background-color .35s ease, color .35s ease; }
        .btn-main:hover { transform: translateY(-2px); box-shadow: 0 16px 34px rgba(16,185,129,.20); }
        .btn-outline { transition: transform .45s cubic-bezier(.16,1,.3,1), background-color .35s ease, color .35s ease; }
        .btn-outline:hover { background: white; color: black; transform: translateY(-2px); }
        .feature-pill { border:1px solid rgba(255,255,255,.18); background:rgba(255,255,255,.1); }
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height .45s cubic-bezier(.4,0,.2,1); }
        @keyframes shine { from{transform:translateX(-30%)} to{transform:translateX(30%)} }
        @keyframes ring { 0%{transform:rotate(0)}15%{transform:rotate(15deg)}30%{transform:rotate(-15deg)}45%{transform:rotate(10deg)}60%{transform:rotate(-10deg)}75%{transform:rotate(5deg)}100%{transform:rotate(0)} }
        @keyframes floaty { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        @keyframes pulseGlow { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.24)} 50%{box-shadow:0 0 0 18px rgba(16,185,129,0)} }
        .soft-float { animation: floaty 8s ease-in-out infinite; }
        .pulse-glow { animation: pulseGlow 2.4s ease-in-out infinite; }
        .call-btn:hover .call-icon { animation: ring .7s ease; }
        .call-btn:active { transform: scale(.96); }
        .faq-item.active .faq-icon { transform: rotate(180deg); }
        .stat-bar-wrap { padding: 0 0; }
        .stat-bar { background:#0f172a; box-shadow:0 30px 60px -20px rgba(15,23,42,.4); border:1px solid rgba(255,255,255,.07); }
        .stat-cell { padding:1.6rem 1.1rem; display:flex; align-items:center; gap:.85rem; border-inline-start:1px solid rgba(255,255,255,.09); }
        .stat-cell:first-child { border-inline-start:none; }
        .stat-icon { flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; width:2.75rem; height:2.75rem; border-radius:.9rem; background:rgba(52,211,153,.12); color:#5eead4; }
        .stat-num { display:block; font-size:1.55rem; font-weight:900; line-height:1.15; background:linear-gradient(90deg,#5eead4,#38bdf8); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .stat-label { display:block; font-size:.74rem; color:rgba(255,255,255,.6); font-weight:600; margin-top:.15rem; }
        @media (max-width:640px){ .stat-cell:nth-child(3){ border-inline-start:none; } .stat-cell{ padding:1.15rem .75rem; gap:.6rem; } .stat-icon{ width:2.25rem; height:2.25rem; } .stat-num{ font-size:1.2rem; } .stat-label{ font-size:.66rem; } }
        .portfolio-card-overlay { background:linear-gradient(0deg, rgba(2,6,23,.92) 0%, rgba(2,6,23,.55) 45%, rgba(2,6,23,.05) 75%); transition:opacity .4s ease; }
        .portfolio-card-sub { max-height:0; opacity:0; overflow:hidden; transition:max-height .45s cubic-bezier(.16,1,.3,1), opacity .35s ease; }
        .group:hover .portfolio-card-sub { max-height:80px; opacity:1; margin-top:.25rem; }
        .portfolio-card-link { opacity:0; transform:translateY(6px); transition:opacity .35s ease, transform .35s ease; }
        .group:hover .portfolio-card-link { opacity:1; transform:translateY(0); }
        .premium-service-card { position:relative; background:#fff; border-radius:1.75rem; overflow:hidden; border:1px solid #f1f5f9; box-shadow:0 10px 30px -12px rgba(15,23,42,.08); transition:box-shadow .35s ease, border-color .35s ease, transform .35s ease; }
        .premium-service-card:hover { border-color:transparent; transform:translateY(-6px); }
        .premium-service-glow { position:absolute; top:-40px; left:-40px; width:150px; height:150px; border-radius:50%; filter:blur(30px); opacity:.5; pointer-events:none; transition:opacity .4s ease, transform .4s ease; }
        .premium-service-card:hover .premium-service-glow { opacity:.85; transform:scale(1.15); }
        .premium-service-glow--emerald { background:radial-gradient(circle, #34d399, transparent 70%); }
        .premium-service-glow--sky { background:radial-gradient(circle, #38bdf8, transparent 70%); }
        .premium-service-glow--teal { background:radial-gradient(circle, #2dd4bf, transparent 70%); }
        .premium-service-card:nth-child(1):hover { box-shadow:0 24px 48px -18px rgba(16,185,129,.35); }
        .premium-service-card:nth-child(2):hover { box-shadow:0 24px 48px -18px rgba(14,165,233,.35); }
        .premium-service-card:nth-child(3):hover { box-shadow:0 24px 48px -18px rgba(13,148,136,.35); }
        .premium-service-body { padding:2.25rem 2rem; position:relative; }
        .premium-service-icon { width:4.25rem; height:4.25rem; border-radius:1.35rem; display:flex; align-items:center; justify-content:center; transition:transform .4s cubic-bezier(.16,1,.3,1); box-shadow:0 12px 24px -10px rgba(15,23,42,.18); }
        .premium-service-card:hover .premium-service-icon { transform:translateY(-4px) rotate(-4deg); }
        .premium-service-icon--emerald { background:linear-gradient(135deg,#34d399,#059669); color:#fff; }
        .premium-service-icon--sky { background:linear-gradient(135deg,#38bdf8,#0284c7); color:#fff; }
        .premium-service-icon--teal { background:linear-gradient(135deg,#2dd4bf,#0d9488); color:#fff; }
        .premium-service-link { display:inline-flex; align-items:center; gap:.4rem; font-weight:800; font-size:.86rem; }
        .premium-service-link svg { transition:transform .3s cubic-bezier(.16,1,.3,1); }
        .premium-service-card:hover .premium-service-link svg { transform:translateX(-5px); }
        .premium-service-link--emerald { color:#059669; }
        .premium-service-link--sky { color:#0284c7; }
        .premium-service-link--teal { color:#0d9488; }
        .scroll-cue { position:absolute; bottom:1.5rem; left:50%; transform:translateX(-50%); display:flex; flex-direction:column; align-items:center; gap:.4rem; z-index:10; opacity:.75; }
        .scroll-cue span { font-size:.7rem; color:rgba(255,255,255,.6); font-weight:700; }
        .scroll-cue-icon { width:22px; height:34px; border:2px solid rgba(255,255,255,.4); border-radius:12px; position:relative; }
        .scroll-cue-icon:before { content:""; position:absolute; top:6px; left:50%; width:4px; height:8px; margin-left:-2px; border-radius:2px; background:#5eead4; animation:scrollCueDrop 1.8s ease-in-out infinite; }
        @keyframes scrollCueDrop { 0% { opacity:1; transform:translateY(0); } 70% { opacity:0; transform:translateY(10px); } 100% { opacity:0; transform:translateY(10px); } }
        @media (max-width:767px), (prefers-reduced-motion: reduce) { .scroll-cue { display:none; } }
        .hero-status-badge { display:inline-flex; align-items:center; gap:.55rem; padding:.5rem 1rem; border-radius:999px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); font-size:.82rem; font-weight:800; color:#d1fae5; }
        .hero-status-dot { width:8px; height:8px; border-radius:50%; background:#34d399; box-shadow:0 0 0 0 rgba(52,211,153,.6); animation:heroStatusPulse 2s ease-out infinite; }
        @keyframes heroStatusPulse { 0% { box-shadow:0 0 0 0 rgba(52,211,153,.6); } 70% { box-shadow:0 0 0 7px rgba(52,211,153,0); } 100% { box-shadow:0 0 0 0 rgba(52,211,153,0); } }
        @media (prefers-reduced-motion: reduce) { .hero-status-dot { animation:none; } }
        .hero-tilt-wrap { transition:transform .25s ease-out; transform-style:preserve-3d; }
        @media (max-width:1023px) { .hero-tilt-wrap { transform:none !important; } }
        .process-step-num { background:linear-gradient(135deg,#10b981,#0ea5e9); box-shadow:0 8px 18px -6px rgba(16,185,129,.5); }
        .process-timeline:before { content:""; position:absolute; top:2.5rem; bottom:2.5rem; right:1.75rem; width:2px; background-image:linear-gradient(rgba(148,163,184,.4) 50%, transparent 50%); background-size:2px 10px; z-index:0; }
        @media (prefers-reduced-motion: no-preference) { .process-step-num { transition:transform .3s ease; } .hover-lift:hover .process-step-num { transform:scale(1.08) rotate(-4deg); } }
        .seo-showcase { isolation:isolate; }
        .seo-report-grid { align-items:stretch; }
        .seo-report-card { position:relative; min-width:0; overflow:hidden; background:linear-gradient(180deg,#ffffff 0%,#f8fafc 100%); box-shadow:0 26px 70px rgba(2,6,23,.28); transition:transform .45s cubic-bezier(.16,1,.3,1), box-shadow .45s ease, border-color .35s ease; }
        .seo-report-card:before { content:""; position:absolute; inset:0 0 auto 0; height:7px; background:linear-gradient(90deg,#10b981,#38bdf8,#a7f3d0); }
        .seo-report-card:after { content:""; position:absolute; width:210px; height:210px; left:-88px; top:-88px; background:radial-gradient(circle, rgba(16,185,129,.15), transparent 70%); pointer-events:none; }
        .seo-report-card:hover { transform:translateY(-6px); box-shadow:0 34px 86px rgba(2,6,23,.34); border-color:rgba(16,185,129,.45); }
        .seo-report-topbar { display:flex; align-items:center; justify-content:space-between; gap:.75rem; position:relative; z-index:1; }
        .seo-report-label { display:inline-flex; align-items:center; gap:.4rem; border-radius:999px; padding:.45rem .75rem; background:#ecfdf5; color:#047857; font-size:.75rem; font-weight:900; white-space:nowrap; }
        .seo-report-date { color:#64748b; font-size:.75rem; font-weight:800; white-space:nowrap; }
        .seo-keyword-list { position:relative; z-index:1; display:flex; flex-direction:column; gap:.65rem; margin-top:1.15rem; }
        .seo-rank-row { display:grid; grid-template-columns:minmax(0,1fr) 58px 58px; gap:.55rem; align-items:center; border:1px solid #e2e8f0; background:white; border-radius:1.15rem; padding:.7rem; }
        .seo-rank-row.is-head { background:#0f172a; color:white; border-color:#0f172a; font-size:.74rem; font-weight:900; padding:.62rem .7rem; }
        .seo-rank-row.is-head span:not(:first-child) { text-align:center; color:rgba(255,255,255,.72); }
        .seo-keyword-text { min-width:0; overflow-wrap:anywhere; line-height:1.75; font-size:.88rem; font-weight:900; color:#0f172a; }
        .seo-rank-before, .seo-rank-after { display:flex; align-items:center; justify-content:center; min-height:40px; border-radius:.9rem; font-size:.86rem; font-weight:1000; direction:ltr; }
        .seo-rank-before { background:#f1f5f9; color:#64748b; }
        .seo-rank-after { background:#dcfce7; color:#047857; box-shadow:inset 0 0 0 1px rgba(16,185,129,.18); }
        .seo-rank-after.top-one { background:linear-gradient(135deg,#064e3b,#10b981); color:white; }
        .seo-report-result { position:relative; z-index:1; display:flex; justify-content:space-between; gap:.85rem; align-items:center; margin-top:1rem; padding:1rem; border-radius:1.25rem; background:linear-gradient(135deg,#ecfdf5,#f0f9ff); border:1px solid rgba(16,185,129,.16); }
        .seo-report-result b { display:block; color:#0f172a; font-size:.92rem; line-height:1.8; }
        .seo-report-result span { color:#64748b; font-size:.78rem; font-weight:800; }
        .seo-report-badge { flex:0 0 auto; min-width:76px; border-radius:1rem; padding:.65rem .75rem; text-align:center; background:#0f172a; color:white; font-weight:1000; direction:ltr; }
        .seo-report-note { position:relative; z-index:1; margin-top:1rem; color:#64748b; line-height:1.9; font-size:.86rem; }
        .seo-shot-frame { border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.06); box-shadow:0 26px 80px rgba(0,0,0,.2); backdrop-filter:blur(16px); }
        .seo-shot-dot { width:.55rem; height:.55rem; border-radius:999px; background:rgba(255,255,255,.35); display:inline-block; }
        .seo-shot-dot:first-child { background:#34d399; }
        .seo-shot-dot:nth-child(2) { background:#38bdf8; }
        .seo-shot-dot:nth-child(3) { background:#f59e0b; }
        .brand-slider { direction:ltr; mask-image:linear-gradient(to right, transparent 0, #000 9%, #000 91%, transparent 100%); -webkit-mask-image:linear-gradient(to right, transparent 0, #000 9%, #000 91%, transparent 100%); }
        .brand-track { display:flex; align-items:center; gap:1rem; width:max-content; animation:brandMarquee 24s linear infinite; }
        .brand-slider:hover .brand-track { animation-play-state:paused; }
        .brand-slide { width:145px; height:74px; flex:0 0 auto; display:flex; align-items:center; justify-content:center; border-radius:1.4rem; background:white; border:1px solid #e2e8f0; box-shadow:0 10px 24px rgba(15,23,42,.04); }
        .brand-slide img { max-width:92px; max-height:46px; object-fit:contain; opacity:.78; filter:grayscale(1); transition:opacity .35s ease, transform .35s ease, filter .35s ease; }
        .brand-slide:hover img { opacity:1; filter:grayscale(0); transform:translateY(-2px) scale(1.04); }
        @keyframes brandMarquee { from{transform:translateX(0)} to{transform:translateX(-33.333%)} }
        .reveal-section, .reveal-item { opacity:0; transform:translateY(22px); transition:opacity .5s cubic-bezier(.16,1,.3,1), transform .5s cubic-bezier(.16,1,.3,1); will-change:opacity, transform; }
        .reveal-section.is-visible, .reveal-item.is-visible { opacity:1; transform:translateY(0); }
        .reveal-section[data-reveal="scale"] { transform:translateY(18px) scale(.99); }
        .reveal-section[data-reveal="scale"].is-visible { transform:translateY(0) scale(1); }
        .reveal-section[data-reveal="right"], .reveal-item[data-reveal="right"] { transform:translateX(24px); }
        .reveal-section[data-reveal="left"], .reveal-item[data-reveal="left"] { transform:translateX(-24px); }
        .reveal-section[data-reveal="right"].is-visible, .reveal-section[data-reveal="left"].is-visible, .reveal-item[data-reveal="right"].is-visible, .reveal-item[data-reveal="left"].is-visible { transform:translateX(0); }
        .stagger-ready > * { opacity:0; transform:translateY(14px); transition:opacity .45s cubic-bezier(.16,1,.3,1), transform .45s cubic-bezier(.16,1,.3,1); transition-delay:calc(var(--stagger-index, 0) * 50ms); }
        .stagger-ready.is-visible > * { opacity:1; transform:translateY(0); }
        .reveal-instant, .reveal-instant .stagger-ready > * { transition:none !important; }
        .hero-bg .glass-card, .hero-bg h1, .hero-bg p, .hero-bg .btn-main, .hero-bg .btn-outline { animation:heroFadeUp .9s cubic-bezier(.16,1,.3,1) both; }
        .hero-bg h1 { animation-delay:.06s; }
        .hero-bg p { animation-delay:.15s; }
        .hero-bg .btn-main, .hero-bg .btn-outline { animation-delay:.25s; }
        .hero-bg .glass-card { animation-delay:.34s; }
        @keyframes heroFadeUp { from{opacity:0; transform:translateY(28px)} to{opacity:1; transform:translateY(0)} }
        .article-content h2 { font-size: 1.55rem; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; color: #0f172a; }
        .article-content h3 { font-size: 1.25rem; font-weight: 800; margin-top: 1.5rem; margin-bottom: .75rem; color: #0f172a; }
        .article-content p, .article-content li { line-height: 2.2; color: #475569; }
        .article-content ul { list-style: disc; padding-right: 1.5rem; }
        .article-content ol { list-style: decimal; padding-right: 1.5rem; }
        .article-content a { color: #059669; font-weight: 700; }
        .article-content blockquote { border-right: 4px solid #10b981; background: #ecfdf5; padding: 1rem; border-radius: 1rem; margin: 1.5rem 0; }
        .article-content img { max-width: 100%; height: auto; border-radius: 1.25rem; margin: 1.5rem auto; display: block; box-shadow: 0 14px 34px rgba(15,23,42,.1); }
        .article-content figure { margin: 1.75rem 0; }
        .article-content figure img { margin: 0 auto; }
        .article-content figcaption { text-align: center; color: #64748b; font-size: .85rem; margin-top: .6rem; }
        .article-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; border-radius: 1rem; overflow: hidden; }
        .article-content th { background: #0f172a; color: #fff; padding: .85rem 1rem; text-align: right; font-weight: 800; }
        .article-content td { padding: .8rem 1rem; border-top: 1px solid #e2e8f0; }
        .article-content tr:nth-child(even) td { background: #f8fafc; }
        .article-content pre, .article-content code { background: #0f172a; color: #e2e8f0; border-radius: .6rem; }
        .article-content pre { padding: 1.1rem 1.3rem; overflow-x: auto; margin: 1.5rem 0; }
        .article-content code { padding: .15rem .45rem; font-size: .85em; }
        .article-content pre code { background: none; padding: 0; }
        .article-content hr { border: none; border-top: 1px solid #e2e8f0; margin: 2rem 0; }
        @media (max-width: 767px) {
          html, body { width:100%; max-width:100%; overflow-x:clip; }
          main, header, footer, section, .max-w-6xl, .max-w-4xl, .seo-showcase { max-width:100vw; overflow-x:clip; }
          header .logo-box { width: 48px; height: 48px; border-radius: 16px; }
          .hero-bg { padding-top: 7rem; padding-bottom: 3.5rem; }
          .hero-bg h1 { font-size: 2.15rem; line-height: 1.45; letter-spacing: -.02em; }
          .hero-bg p { font-size: .98rem; line-height: 2.05; }
          .hero-bg .grid.grid-cols-3 { grid-template-columns: repeat(3,minmax(0,1fr)); max-width: 100%; }
          .hero-bg .grid.grid-cols-3 strong { font-size:1.05rem; }
          .hero-bg .grid.grid-cols-3 span { font-size:.68rem; }
          .hero-bg .glass-card { padding: .9rem 1rem; }
          section { scroll-margin-top: 86px; }
          .card:hover, .hover-lift:hover, .btn-main:hover, .btn-outline:hover { transform: none; }
          .service-card { border-radius: 1.35rem; padding: 1.4rem; }
          .py-20 { padding-top:3.4rem; padding-bottom:3.4rem; }
          .gap-8 { gap:1.25rem; }
          #mobileMenu { left:0; right:0; width:100%; max-width:100vw; background: rgba(255,255,255,.96); backdrop-filter: blur(18px); overflow-x:clip; }
          #mobileMenu a { padding: .85rem 1rem; border-radius: 1rem; font-weight: 800; color:#0f172a; background:#f8fafc; border:1px solid #e2e8f0; }
          #mobileMenu a:last-child { background:#10b981; color:white; border-color:#10b981; }
          .seo-showcase { overflow:hidden; }
          .seo-report-card { border-radius:1.45rem; padding:1rem; }
          .seo-report-card:hover { transform:none; }
          .seo-report-topbar { align-items:flex-start; }
          .seo-report-label, .seo-report-date { white-space:normal; line-height:1.7; }
          .seo-rank-row { grid-template-columns:minmax(0,1fr) 48px 48px; gap:.42rem; padding:.55rem; border-radius:1rem; }
          .seo-rank-row.is-head { font-size:.68rem; }
          .seo-keyword-text { font-size:.8rem; line-height:1.65; }
          .seo-rank-before, .seo-rank-after { min-height:36px; border-radius:.75rem; font-size:.76rem; }
          .seo-report-result { align-items:stretch; flex-direction:column; padding:.9rem; }
          .seo-report-badge { width:100%; min-width:0; }
          .seo-shot-frame { border-radius:1.35rem; padding:1rem; }
          .brand-slider { border-radius:1.35rem; }
          .brand-track { animation-duration:18s; gap:.75rem; }
          .brand-slide { width:118px; height:64px; border-radius:1rem; }
          .brand-slide img { max-width:78px; max-height:38px; }
          .article-content { border-radius: 1.25rem; padding: 1.25rem; }
          .article-content h2 { font-size: 1.25rem; line-height: 1.8; }
          .article-content p, .article-content li { line-height: 2.05; }
          .reveal-section[data-reveal="right"], .reveal-section[data-reveal="left"], .reveal-item[data-reveal="right"], .reveal-item[data-reveal="left"] { transform:translateY(24px); }
          .reveal-section[data-reveal="right"].is-visible, .reveal-section[data-reveal="left"].is-visible, .reveal-item[data-reveal="right"].is-visible, .reveal-item[data-reveal="left"].is-visible { transform:translateY(0); }
        }
        .guide-hero{position:relative;overflow:hidden;background:linear-gradient(135deg,#020617 0%,#0f172a 48%,#064e3b 100%);color:#fff;padding:9rem 0 5rem}
        .guide-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 15% 18%,rgba(56,189,248,.2),transparent 28%),radial-gradient(circle at 88% 72%,rgba(16,185,129,.3),transparent 30%)}
        .guide-hero:after{content:"";position:absolute;inset:-25%;background:linear-gradient(120deg,transparent 34%,rgba(255,255,255,.05) 50%,transparent 66%);animation:shine 12s linear infinite;pointer-events:none}
        @media (prefers-reduced-motion: reduce){ .guide-hero:after{ animation:none; } }
        .guide-badge{display:inline-flex;align-items:center;gap:.55rem;padding:.6rem 1rem;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.08);border-radius:999px;font-weight:900;color:#a7f3d0;margin-bottom:1.25rem}
        .guide-shell{max-width:72rem;margin:auto;padding:0 1rem;width:100%}
        .guide-layout{display:grid;grid-template-columns:minmax(0,1fr) 17rem;gap:2rem;align-items:start;width:100%}
        .guide-prose{background:#fff;border:1px solid #e2e8f0;border-radius:2rem;padding:clamp(1.25rem,3vw,2.5rem);box-shadow:0 20px 60px rgba(15,23,42,.07);min-width:0;max-width:100%;overflow-wrap:break-word;word-break:break-word}
        .guide-prose h2{font-size:clamp(1.35rem,2.3vw,1.75rem);line-height:1.7;font-weight:1000;color:#0f172a;margin:2.5rem 0 1rem;padding-right:1rem;border-right:4px solid #10b981}
        .guide-prose h2:first-child{margin-top:0}.guide-prose h3{font-size:1.2rem;line-height:1.8;font-weight:1000;color:#0f172a;margin:2rem 0 .7rem}
        .guide-prose h4{font-size:1.03rem;line-height:1.9;font-weight:1000;color:#0f172a;margin:1.4rem 0 .45rem;padding:1rem 1.1rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:1rem}
        .guide-prose p,.guide-prose li{color:#475569;line-height:2.2;font-size:1rem}.guide-prose p{margin:.7rem 0}.guide-prose strong{color:#0f172a;font-weight:1000}
        .guide-prose ul,.guide-prose ol{padding-right:1.45rem;margin:1rem 0 1.5rem}.guide-prose ul{list-style:disc}.guide-prose ol{list-style:decimal}.guide-prose li{padding:.25rem .15rem}
        .table-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;margin:1.4rem 0 1.8rem;border:1px solid #e2e8f0;border-radius:1.35rem;max-width:100%}.guide-prose table{width:100%;min-width:650px;border-collapse:collapse;background:white}
        .guide-prose th{background:#0f172a;color:white;text-align:right;padding:1rem;font-weight:1000}.guide-prose td{padding:1rem;border-top:1px solid #e2e8f0;line-height:2;color:#475569}.guide-prose tr:nth-child(even) td{background:#f8fafc}
        .guide-side{position:sticky;top:6.5rem;display:flex;flex-direction:column;gap:1rem;min-width:0;max-width:100%}.guide-side-card{background:#0f172a;color:white;border-radius:1.5rem;padding:1.4rem;box-shadow:0 18px 50px rgba(15,23,42,.15);min-width:0}
        .guide-side-card p{color:rgba(255,255,255,.7);line-height:2;font-size:.9rem;margin:.65rem 0 1rem}.guide-side-card a{display:flex;justify-content:center;align-items:center;gap:.5rem;padding:.85rem 1rem;border-radius:1rem;font-weight:1000}
        .guide-links{background:white;border:1px solid #e2e8f0;border-radius:1.5rem;padding:1.1rem;min-width:0}.guide-links a{display:block;padding:.8rem;border-radius:.85rem;color:#334155;font-weight:900;font-size:.88rem}.guide-links a:hover{background:#ecfdf5;color:#047857}
        .price-contact-box{margin:1.5rem 0;padding:1.35rem;border-radius:1.5rem;background:linear-gradient(135deg,#ecfdf5,#f0f9ff);border:1px solid rgba(16,185,129,.2);max-width:100%}.price-contact-box strong{display:block;font-size:1.1rem;margin-bottom:.4rem}
        .portfolio-grid article{transition:transform .3s ease,box-shadow .3s ease}.portfolio-grid article:hover{transform:translateY(-6px);box-shadow:0 24px 55px rgba(15,23,42,.12)}
        @media (max-width: 900px) {
          .guide-layout{grid-template-columns:1fr}
          .guide-side{position:static;display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:.5rem}
          .guide-hero{padding-top:7.5rem}
        }
        @media (max-width: 767px) {
          .guide-shell{padding:0 .85rem}
          .guide-hero{padding-top:6.5rem;padding-bottom:3rem}
          .guide-hero h1{font-size:1.7rem!important;line-height:1.55!important}
          .guide-hero p{font-size:.92rem!important;line-height:1.9!important}
          .guide-badge{font-size:.78rem;padding:.5rem .85rem}
          .guide-prose{padding:1.15rem;border-radius:1.25rem}
          .guide-prose h2{font-size:1.15rem;margin:1.8rem 0 .8rem}
          .guide-prose h3{font-size:1.02rem;margin:1.5rem 0 .55rem}
          .guide-prose h4{font-size:.94rem}
          .guide-prose p,.guide-prose li{font-size:.92rem;line-height:2}
          .price-contact-box{padding:1rem;border-radius:1.1rem}
          .table-scroll{border-radius:1rem}
        }
        @media (max-width: 640px) {
          .guide-side{grid-template-columns:1fr}
          .guide-prose{border-radius:1.3rem}
          .guide-hero{padding-bottom:3.7rem}
        }
        @media (prefers-reduced-motion: reduce) {
          *, *::before, *::after { animation-duration:.001ms !important; animation-iteration-count:1 !important; scroll-behavior:auto !important; transition-duration:.001ms !important; }
          .reveal-section, .reveal-item, .stagger-ready > * { opacity:1 !important; transform:none !important; filter:none !important; }
        }
        @media (prefers-reduced-motion: reduce), (max-width: 767px) { .soft-float, .pulse-glow, .hero-bg:before { animation:none!important; } .card:hover,.hover-lift:hover,.btn-main:hover{ transform:none; } }
        /* ── چیدمان صفحه جزئیات بلاگ: فهرست مطالب + محتوا + مقالات مشابه ── */
        /* ── هدر (هیرو) صفحه جزئیات بلاگ ── */
        .blog-hero{position:relative;overflow:hidden;background:linear-gradient(135deg,#020617 0%,#0f172a 55%,#064e3b 100%);color:#fff;padding:6.5rem 0 3.5rem}
        .blog-hero:after{content:"";position:absolute;inset:-25%;background:linear-gradient(120deg,transparent 34%,rgba(255,255,255,.05) 50%,transparent 66%);animation:shine 12s linear infinite;pointer-events:none}
        @media (prefers-reduced-motion: reduce){ .blog-hero:after{ animation:none; } }
        .blog-hero-glow{position:absolute;inset:0;background:radial-gradient(circle at 12% 20%,rgba(56,189,248,.22),transparent 30%),radial-gradient(circle at 90% 80%,rgba(16,185,129,.28),transparent 32%);pointer-events:none}
        .blog-breadcrumb{display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:rgba(255,255,255,.55);margin-bottom:1.5rem;flex-wrap:wrap}
        .blog-breadcrumb a{color:rgba(255,255,255,.7);font-weight:700;transition:color .2s ease}
        .blog-breadcrumb a:hover{color:#6ee7b7}
        .blog-breadcrumb span.is-current{color:rgba(255,255,255,.92);font-weight:800}
        .blog-cat-badge{position:relative;z-index:1;display:inline-flex;align-items:center;gap:.5rem;padding:.5rem .95rem;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.08);border-radius:999px;font-weight:900;font-size:.82rem;color:#a7f3d0;margin-bottom:1.15rem}
        .blog-hero-title{position:relative;z-index:1;font-size:clamp(1.7rem,3.4vw,2.85rem);line-height:1.5;font-weight:1000;max-width:52rem;margin-bottom:1.1rem}
        .blog-hero-excerpt{position:relative;z-index:1;color:rgba(255,255,255,.72);font-size:1.05rem;line-height:2;max-width:44rem;margin-bottom:1.6rem}
        .blog-hero-meta{position:relative;z-index:1;display:flex;flex-wrap:wrap;align-items:center;gap:.65rem;font-size:.85rem;color:rgba(255,255,255,.7);font-weight:700}
        .blog-hero-meta svg{display:inline-block;vertical-align:-2px}
        .blog-author{display:inline-flex;align-items:center;gap:.6rem}
        .blog-author-avatar{display:inline-flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:999px;background:linear-gradient(135deg,#10b981,#38bdf8);color:#04231a;font-weight:1000;font-size:.85rem}
        .blog-meta-sep{color:rgba(255,255,255,.3)}
        /* ── دکمه مشاوره کنار فهرست مطالب ── */
        .blog-cta-card{display:flex;align-items:center;gap:.85rem;background:linear-gradient(135deg,#064e3b,#0f172a);color:#fff;border-radius:1.4rem;padding:1.1rem;box-shadow:0 16px 40px rgba(6,78,59,.25);transition:transform .3s ease,box-shadow .3s ease}
        .blog-cta-card:hover{transform:translateY(-3px);box-shadow:0 20px 48px rgba(6,78,59,.32)}
        .blog-cta-icon{display:flex;align-items:center;justify-content:center;width:2.6rem;height:2.6rem;border-radius:1rem;background:rgba(16,185,129,.2);color:#6ee7b7;flex-shrink:0}
        .blog-cta-card strong{display:block;font-size:.92rem;font-weight:1000;margin-bottom:.15rem}
        .blog-cta-card small{display:block;font-size:.76rem;color:rgba(255,255,255,.6);font-weight:600}
        /* ── شماره‌گذاری فهرست مطالب ── */
        .blog-toc-list a{display:flex;align-items:flex-start;gap:.55rem}
        .toc-num{flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;width:1.35rem;height:1.35rem;border-radius:.5rem;background:#f1f5f9;color:#64748b;font-size:.72rem;font-weight:900;margin-top:.05rem}
        .blog-toc-list a.is-active .toc-num{background:#10b981;color:#fff}
        /* ── نوار اشتراک‌گذاری زیر مقاله ── */
        .blog-share-bar{margin-top:1.5rem;background:#fff;border:1px solid #e2e8f0;border-radius:1.4rem;padding:1.1rem 1.3rem;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;box-shadow:0 12px 34px rgba(15,23,42,.05)}
        .blog-share-label{display:flex;align-items:center;gap:.5rem;font-weight:900;color:#0f172a;font-size:.9rem}
        .blog-share-links{display:flex;flex-wrap:wrap;align-items:center;gap:.6rem}
        .blog-share-links a,.blog-copy-link{display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1rem;border-radius:.85rem;background:#f1f5f9;color:#0f172a;font-weight:800;font-size:.82rem;border:1px solid #e2e8f0;cursor:pointer;transition:background .2s ease,color .2s ease,border-color .2s ease}
        .blog-share-links a:hover{background:#ecfdf5;color:#047857;border-color:rgba(16,185,129,.3)}
        .blog-copy-link:hover{background:#eff6ff;color:#1d4ed8;border-color:rgba(59,130,246,.3)}
        @media (max-width:767px){
          .blog-hero{padding:5.5rem 0 2.5rem}
          .blog-hero-title{font-size:1.5rem;line-height:1.6}
          .blog-hero-excerpt{font-size:.92rem;line-height:1.9}
          .blog-hero-meta{font-size:.78rem;gap:.5rem}
          .blog-share-bar{flex-direction:column;align-items:stretch;text-align:center}
          .blog-share-links{justify-content:center}
        }
        .blog-shell{max-width:86rem;margin:auto;padding:0 1rem;width:100%}
        .blog-layout{display:grid;grid-template-columns:19rem minmax(0,1fr) 16rem;gap:2rem;align-items:start;width:100%}
        .blog-rail{position:sticky;top:6.5rem;display:flex;flex-direction:column;gap:1.25rem;min-width:0;max-width:100%}
        .blog-toc-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.5rem;padding:1.5rem 1.4rem;box-shadow:0 16px 40px rgba(15,23,42,.06);min-width:0}
        .blog-toc-title{display:flex;align-items:center;gap:.5rem;font-weight:1000;color:#0f172a;font-size:1rem;margin-bottom:1.1rem}
        .blog-toc-list{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:.25rem;max-height:68vh;overflow-y:auto}
        .blog-toc-list a{display:block;padding:.7rem .85rem;border-radius:.85rem;color:#475569;font-size:.92rem;line-height:1.75;font-weight:700;border-right:2px solid transparent;transition:background .25s ease,color .25s ease,border-color .25s ease}
        .blog-toc-list a.toc-level-3{padding-right:1.6rem;font-size:.85rem;font-weight:600}
        .blog-toc-list a:hover{background:#f1f5f9;color:#0f172a}
        .blog-toc-list a.is-active{background:#ecfdf5;color:#047857;border-color:#10b981;font-weight:900}
        .blog-related-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.5rem;padding:1.1rem;box-shadow:0 16px 40px rgba(15,23,42,.06);min-width:0}
        .blog-related-title{display:flex;align-items:center;gap:.5rem;font-weight:1000;color:#0f172a;font-size:.95rem;margin-bottom:.9rem}
        .blog-related-item{display:flex;gap:.75rem;align-items:flex-start;padding:.6rem;border-radius:1rem;transition:background .25s ease}
        .blog-related-item:hover{background:#f8fafc}
        .blog-related-item img{width:64px;height:64px;object-fit:cover;border-radius:.85rem;flex-shrink:0}
        .blog-related-item .r-cat{color:#059669;font-size:.68rem;font-weight:900;margin-bottom:.15rem;display:block}
        .blog-related-item .r-title{color:#0f172a;font-size:.83rem;font-weight:800;line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .blog-related-item .r-icon{width:2.6rem;height:2.6rem;border-radius:.9rem;background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#059669;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .blog-related-item.is-plain{align-items:center}
        .blog-toc-mobile-toggle{display:none}
        .blog-main-col{min-width:0}
        .article-content{font-size:1.04rem}
        .article-content p{margin-bottom:1.15rem}
        @media (max-width: 1024px) {
          .blog-layout{grid-template-columns:16rem minmax(0,1fr)}
          .blog-layout .blog-related-col{grid-column:1 / -1;order:5}
          .blog-layout .blog-related-col .blog-rail{position:static;display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
        }
        @media (max-width: 860px) {
          .blog-layout{grid-template-columns:1fr}
          .blog-layout .blog-toc-col{order:1}
          .blog-layout .blog-main-col{order:2}
          .blog-layout .blog-related-col{order:3}
          .blog-rail{position:static}
          .blog-layout .blog-related-col .blog-rail{grid-template-columns:1fr 1fr}
          .blog-toc-mobile-toggle{display:flex;align-items:center;justify-content:space-between;gap:.75rem;width:100%;background:#0f172a;color:#fff;font-weight:900;font-size:.9rem;padding:.9rem 1.1rem;border-radius:1.1rem;margin-bottom:.75rem}
          .blog-toc-mobile-toggle svg{transition:transform .3s ease}
          .blog-toc-card{max-height:0;overflow:hidden;padding:0 1.25rem;border-width:0;box-shadow:none;transition:max-height .4s ease,padding .3s ease}
          .blog-toc-card.is-open{max-height:70vh;overflow-y:auto;padding:1.25rem;border-width:1px}
          .blog-toc-mobile-toggle.is-open svg{transform:rotate(180deg)}
        }
        @media (max-width: 560px) {
          .blog-layout .blog-related-col .blog-rail{grid-template-columns:1fr}
        }
      </style>
      <?php foreach ($extraStyles as $styleHref): ?>
      <link rel="stylesheet" href="<?= e(asset_url((string) $styleHref)) ?>" />
      <?php endforeach; ?>
    </head>
    <?php
}