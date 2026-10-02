<?php
require_once __DIR__ . '/app/functions.php';
$slug = $_GET['slug'] ?? '';
$post = get_post_by_slug($slug);
if (!$post) {
    http_response_code(404);
    render_public_head('مقاله پیدا نشد | سایت دوز', 'مقاله موردنظر پیدا نشد.', site_url('blog'));
    include __DIR__ . '/partials/header.php';
    echo '<main class="pt-32 pb-20"><div class="max-w-3xl mx-auto px-4 text-center bg-white rounded-2xl shadow p-10"><h1 class="text-3xl font-extrabold mb-4">مقاله پیدا نشد</h1><p class="text-gray-600 mb-6">احتمالاً لینک تغییر کرده یا مقاله حذف شده است.</p><a class="text-emerald-600 font-bold" href="' . site_url('blog') . '">بازگشت به بلاگ</a></div></main>';
    include __DIR__ . '/partials/footer.php';
    exit;
}
$title = $post['meta_title'] ?: (($post['title'] ?? '') . ' | سایت دوز');
$description = $post['meta_description'] ?: ($post['excerpt'] ?: excerpt($post['content'] ?? '', 155));
render_public_head($title, $description, post_url($post), $post['cover'] ?? 'images/logo.png');
include __DIR__ . '/partials/header.php';
$articleSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $post['title'] ?? '',
    'description' => $description,
    'image' => asset_url($post['cover'] ?? 'images/logo.png'),
    'author' => ['@type' => 'Person', 'name' => $post['author'] ?? 'تیم سایت دوز'],
    'datePublished' => $post['published_at'] ?? '',
    'mainEntityOfPage' => post_url($post)
];

$sanitizedContent = render_rich_text($post['content'] ?? '');
$articleData = build_article_toc($sanitizedContent);
$articleHtml = $articleData['content'];
$toc = $articleData['toc'];
$relatedAll = get_related_posts($post, 8);
$relatedRight = array_slice($relatedAll, 0, 4);
$relatedLeft = array_slice($relatedAll, 4, 4);
if (!$relatedLeft && $relatedRight) {
    // اگر مقالات کافی نبود، همان فهرست را در هر دو ستون تقسیم می‌کنیم
    $half = (int) ceil(count($relatedRight) / 2);
    $relatedLeft = array_slice($relatedRight, $half);
    $relatedRight = array_slice($relatedRight, 0, $half);
}

$plainWordCount = str_word_count(strip_tags($sanitizedContent)) ?: (int) round(app_strlen(strip_tags($sanitizedContent)) / 5);
$readingMinutes = max(1, (int) ceil($plainWordCount / 180));
$authorName = $post['author'] ?: 'تیم سایت دوز';
$authorInitial = function_exists('mb_substr') ? mb_substr(trim($authorName), 0, 1, 'UTF-8') : substr(trim($authorName), 0, 1);

function render_related_item(array $p): string
{
    return '<a class="blog-related-item" href="' . e(post_url($p)) . '">'
        . '<img src="' . e(asset_url($p['cover'] ?? 'images/hero.png')) . '" alt="' . e($p['image_alt'] ?? $p['title'] ?? '') . '" loading="lazy">'
        . '<span><span class="r-cat">' . e($p['category'] ?? 'بلاگ') . '</span><span class="r-title">' . e($p['title'] ?? '') . '</span></span>'
        . '</a>';
}
?>
<script type="application/ld+json"><?= json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<main class="pt-16 pb-20">
  <article>
    <header class="blog-hero">
      <div class="blog-hero-glow"></div>
      <div class="scroll-cue"><span>بیشتر ببین</span><span class="scroll-cue-icon"></span></div>
      <div class="blog-shell relative z-10">
        <nav class="blog-breadcrumb" aria-label="مسیر صفحه">
          <a href="<?= site_url() ?>">خانه</a><span>/</span><a href="<?= site_url('blog') ?>">بلاگ</a><span>/</span><span class="is-current"><?= e($post['category'] ?? 'مقاله') ?></span>
        </nav>
        <span class="blog-cat-badge"><?= svg_icon('newspaper', 'w-4 h-4') ?> <?= e($post['category'] ?? 'بلاگ') ?></span>
        <h1 class="blog-hero-title"><?= e($post['title'] ?? '') ?></h1>
        <p class="blog-hero-excerpt"><?= e($post['excerpt'] ?: $description) ?></p>
        <div class="blog-hero-meta">
          <span class="blog-author"><span class="blog-author-avatar"><?= e($authorInitial) ?></span> <?= e($authorName) ?></span>
          <span class="blog-meta-sep">•</span>
          <span><?= svg_icon('chevron', 'w-4 h-4 rotate-[-90deg] opacity-60') ?> <?= format_date($post['published_at'] ?? '') ?></span>
          <span class="blog-meta-sep">•</span>
          <span><?= fa_number($readingMinutes) ?> دقیقه مطالعه</span>
        </div>
      </div>
    </header>
    <div class="blog-shell py-10">
      <div class="blog-layout">

        <div class="blog-toc-col">
          <div class="blog-rail">
            <?php if ($toc): ?>
            <button type="button" id="tocMobileToggle" class="blog-toc-mobile-toggle">
              <span><?= svg_icon('list', 'w-5 h-5') ?> فهرست مطالب</span>
              <?= svg_icon('chevron', 'w-4 h-4') ?>
            </button>
            <nav class="blog-toc-card" id="tocCard" aria-label="فهرست مطالب مقاله">
              <div class="blog-toc-title"><?= svg_icon('list', 'w-5 h-5') ?> فهرست مطالب</div>
              <ul class="blog-toc-list" id="tocList">
                <?php foreach ($toc as $i => $item): ?>
                  <li><a href="#<?= e($item['id']) ?>" class="toc-level-<?= (int) $item['level'] ?>" data-target="<?= e($item['id']) ?>"><span class="toc-num"><?= fa_number($i + 1) ?></span><?= e(html_entity_decode($item['text'], ENT_QUOTES, 'UTF-8')) ?></a></li>
                <?php endforeach; ?>
              </ul>
            </nav>
            <?php endif; ?>
            <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>#consultation-form" class="blog-cta-card">
              <span class="blog-cta-icon"><?= svg_icon('phone', 'w-5 h-5') ?></span>
              <span><strong>مشاوره رایگان</strong><small>سوالت رو از ما بپرس</small></span>
            </a>
          </div>
        </div>

        <div class="blog-main-col">
          <img src="<?= asset_url($post['cover'] ?? 'images/hero.png') ?>" class="w-full max-h-[440px] object-cover rounded-3xl shadow mb-8" alt="<?= e($post['image_alt'] ?? $post['title'] ?? '') ?>" loading="lazy">
          <div class="bg-white rounded-3xl shadow p-6 md:p-12 article-content">
            <?= $articleHtml ?>
          </div>
          <div class="blog-share-bar">
            <span class="blog-share-label"><?= svg_icon('external', 'w-4 h-4') ?> اشتراک‌گذاری این مقاله</span>
            <div class="blog-share-links">
              <a target="_blank" rel="noopener" href="https://t.me/share/url?url=<?= urlencode(post_url($post)) ?>&text=<?= urlencode($post['title'] ?? '') ?>">تلگرام</a>
              <a target="_blank" rel="noopener" href="https://wa.me/?text=<?= urlencode(($post['title'] ?? '') . ' ' . post_url($post)) ?>">واتس‌اپ</a>
              <button type="button" class="blog-copy-link" data-url="<?= e(post_url($post)) ?>">کپی لینک</button>
            </div>
          </div>
        </div>

        <div class="blog-related-col">
          <div class="blog-rail">
            <?php if ($relatedRight): ?>
            <div class="blog-related-card">
              <div class="blog-related-title"><?= svg_icon('star', 'w-5 h-5') ?> مقالات مشابه</div>
              <div class="flex flex-col gap-1">
                <?php foreach ($relatedRight as $rp) echo render_related_item($rp); ?>
              </div>
            </div>
            <?php endif; ?>
            <?php if ($relatedLeft): ?>
            <div class="blog-related-card">
              <div class="blog-related-title"><?= svg_icon('star', 'w-5 h-5') ?> پیشنهاد مطالعه</div>
              <div class="flex flex-col gap-1">
                <?php foreach ($relatedLeft as $rp) echo render_related_item($rp); ?>
              </div>
            </div>
            <?php endif; ?>
            <?php if (!$relatedRight && !$relatedLeft): ?>
            <div class="blog-related-card">
              <div class="blog-related-title"><?= svg_icon('newspaper', 'w-5 h-5') ?> شاید این‌ها هم به کارتون بیاد</div>
              <div class="flex flex-col gap-1">
                <a class="blog-related-item is-plain" href="<?= site_url('hazine-tarahi-site-gorgan') ?>"><span class="r-icon"><?= svg_icon('chart', 'w-5 h-5') ?></span><span class="r-title">هزینه طراحی سایت گرگان</span></a>
                <a class="blog-related-item is-plain" href="<?= site_url('hazine-seo-site-gorgan') ?>"><span class="r-icon"><?= svg_icon('search', 'w-5 h-5') ?></span><span class="r-title">هزینه سئو سایت گرگان</span></a>
                <a class="blog-related-item is-plain" href="<?= site_url('sozalat-motadavel-tarahi-site') ?>"><span class="r-icon"><?= svg_icon('check', 'w-5 h-5') ?></span><span class="r-title">سوالات متداول طراحی سایت</span></a>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </article>
</main>
<?php if ($toc): ?>
<script>
(function () {
  var toggle = document.getElementById('tocMobileToggle');
  var card = document.getElementById('tocCard');
  if (toggle && card) {
    toggle.addEventListener('click', function () {
      var open = card.classList.toggle('is-open');
      toggle.classList.toggle('is-open', open);
    });
  }
  var links = Array.prototype.slice.call(document.querySelectorAll('#tocList a'));
  links.forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      var target = document.getElementById(link.dataset.target);
      if (!target) return;
      var headerOffset = 96;
      var top = target.getBoundingClientRect().top + window.pageYOffset - headerOffset;
      window.scrollTo({ top: top, behavior: 'smooth' });
      if (card.classList.contains('is-open')) {
        card.classList.remove('is-open');
        if (toggle) toggle.classList.remove('is-open');
      }
    });
  });
  var headings = links.map(function (link) { return document.getElementById(link.dataset.target); }).filter(Boolean);
  if (headings.length) {
    var setActive = function () {
      var pos = window.scrollY + 110;
      var current = headings[0];
      headings.forEach(function (h) { if (h.offsetTop <= pos) current = h; });
      links.forEach(function (l) { l.classList.toggle('is-active', l.dataset.target === current.id); });
    };
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) { window.requestAnimationFrame(function () { setActive(); ticking = false; }); ticking = true; }
    }, { passive: true });
    setActive();
  }
})();
</script>
<?php endif; ?>
<script>
(function () {
  var btn = document.querySelector('.blog-copy-link');
  if (!btn) return;
  btn.addEventListener('click', function () {
    var url = btn.dataset.url;
    var done = function () { var old = btn.textContent; btn.textContent = 'کپی شد ✓'; setTimeout(function () { btn.textContent = old; }, 1800); };
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(url).then(done).catch(function () { prompt('لینک مقاله:', url); });
    } else {
      prompt('لینک مقاله:', url);
    }
  });
})();
</script>
<?php include __DIR__ . '/partials/footer.php'; ?>
