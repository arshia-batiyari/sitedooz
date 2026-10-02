<?php
require_once __DIR__ . '/app/functions.php';

$posts = published_items(get_posts(true) ?? []);
$today = date('Y-m-d');
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc><?= e(absolute_url()) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('nemoone-kar-tarahi-site-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('sozalat-motadavel-tarahi-site')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('hazine-seo-site-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('hazine-tarahi-site-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('blog')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('about-us')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('moshavere-tarahi-site-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('tarahi-site-sherkati-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('tarahi-site-foroushgahi-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('seo-sherkati-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= e(absolute_url('seo-foroushgahi-gorgan')) ?></loc>
    <lastmod><?= e($today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <?php foreach ($posts as $post): ?>
  <url>
    <loc><?= e(absolute_url('blog/' . rawurlencode($post['slug'] ?? ''))) ?></loc>
    <lastmod><?= e($post['published_at'] ?? $today) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  <?php endforeach; ?>
</urlset>
