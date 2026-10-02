<?php
require_once __DIR__ . '/../app/functions.php';
require_login();
$id     = $_GET['id'] ?? '';
$isEdit = $id !== '';
$post   = $isEdit ? (get_post_by_id($id) ?? null) : null;
if ($isEdit && !$post) {
    header('Location: ' . site_url('admin/posts.php'));
    exit;
}
$post = $post ?? [
    'id' => make_id('post'), 'title' => '', 'slug' => '', 'category' => '', 'author' => 'تیم سایت دوز',
    'status' => 'published', 'published_at' => now_date(),
    'cover' => '', 'image_alt' => '', 'meta_title' => '', 'meta_description' => '', 'excerpt' => '', 'content' => ''
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $uploaded = upload_image('cover_file');
    $title    = trim($_POST['title'] ?? '');
    $slug     = trim($_POST['slug'] ?? '') ?: $title;
    $post = [
        'id'               => $_POST['id'] ?? make_id('post'),
        'title'            => $title,
        'slug'             => slugify($slug),
        'category'         => trim($_POST['category'] ?? ''),
        'author'           => trim($_POST['author'] ?? ''),
        'status'           => $_POST['status'] ?? 'draft',
        'published_at'     => $_POST['published_at'] ?: now_date(),
        'cover'            => $uploaded ?: trim($_POST['cover'] ?? ''),
        'image_alt'        => trim($_POST['image_alt'] ?? ''),
        'meta_title'       => trim($_POST['meta_title'] ?? ''),
        'meta_description' => trim($_POST['meta_description'] ?? ''),
        'excerpt'          => trim($_POST['excerpt'] ?? ''),
        'content'          => trim($_POST['content'] ?? ''),
    ];
    save_post($post);
    header('Location: ' . site_url('admin/posts.php'));
    exit;
}
include __DIR__ . '/_header.php';
?>
<div class="mb-8"><h1 class="text-3xl font-extrabold mb-2"><?= $isEdit ? 'ویرایش مقاله' : 'مقاله جدید' ?></h1><p class="text-slate-500">برای محتوای مقاله از ادیتور متنی زیر استفاده کنید؛ می‌توانید تیتر، لیست، جدول، لینک و تصویر (با درگ‌ودراپ یا دکمه درج تصویر) اضافه کنید.</p></div>
<form method="post" enctype="multipart/form-data" class="admin-card p-6 space-y-6">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= e($post['id']) ?>">
  <div class="grid md:grid-cols-2 gap-5">
    <div><label class="label">عنوان مقاله</label><input class="field" name="title" value="<?= e($post['title']) ?>" required></div>
    <div><label class="label">اسلاگ / آدرس</label><input class="field" name="slug" value="<?= e($post['slug']) ?>" placeholder="مثلا: seo-friendly-website"></div>
    <div><label class="label">دسته‌بندی</label><input class="field" name="category" value="<?= e($post['category']) ?>" placeholder="سئو، طراحی سایت، محتوا..."></div>
    <div><label class="label">نویسنده</label><input class="field" name="author" value="<?= e($post['author']) ?>"></div>
    <div><label class="label">تاریخ انتشار</label><input class="field" type="date" name="published_at" value="<?= e($post['published_at']) ?>"></div>
    <div><label class="label">وضعیت</label><select class="field" name="status"><option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>منتشر شده</option><option value="draft" <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>پیش‌نویس</option></select></div>
  </div>
  <div class="grid md:grid-cols-2 gap-5">
    <div><label class="label">آدرس تصویر کاور</label><input class="field" name="cover" value="<?= e($post['cover']) ?>" placeholder="images/hero.png یا uploads/image.webp"></div>
    <div><label class="label">آپلود تصویر کاور</label><input class="field" type="file" name="cover_file" accept="image/*"></div>
  </div>
  <div><label class="label">متن جایگزین تصویر (image_alt)</label><input class="field" name="image_alt" value="<?= e($post['image_alt'] ?? '') ?>" placeholder="توضیح کوتاه تصویر برای سئو و دسترس‌پذیری"></div>
  <div><label class="label">Meta Title</label><input class="field" name="meta_title" value="<?= e($post['meta_title']) ?>"></div>
  <div><label class="label">Meta Description</label><textarea class="field" rows="3" name="meta_description"><?= e($post['meta_description']) ?></textarea></div>
  <div><label class="label">خلاصه مقاله</label><textarea class="field" rows="3" name="excerpt"><?= e($post['excerpt']) ?></textarea></div>

  <div>
    <div class="flex items-center justify-between flex-wrap gap-3 mb-2">
      <label class="label mb-0">متن کامل مقاله</label>

      <!-- کلمه‌شمار -->
      <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
        <span class="text-sm font-bold text-slate-500 whitespace-nowrap">کلمه‌شمار:</span>
        <input type="text" id="keywordCounterInput" placeholder="کلمه یا عبارت..."
               class="text-sm border border-slate-200 rounded-lg px-2 py-1 w-40 md:w-56 focus:outline-none focus:ring-2 focus:ring-emerald-400">
        <span id="keywordCounterResult"
              class="text-sm font-extrabold text-emerald-600 bg-emerald-50 rounded-lg px-3 py-1 min-w-[2rem] text-center">۰</span>
      </div>
    </div>
    <textarea class="field" id="postContentEditor" rows="16" name="content"><?= e($post['content']) ?></textarea>
  </div>

  <div class="flex flex-wrap gap-3"><button class="bg-emerald-500 text-white px-7 py-3 rounded-xl font-extrabold">ذخیره</button><a href="<?= site_url('admin/posts.php') ?>" class="bg-slate-100 px-7 py-3 rounded-xl font-bold">انصراف</a></div>
</form>
<script src="<?= asset_url('assets/tinymce/tinymce.min.js') ?>" referrerpolicy="origin"></script>
<script>
tinymce.init({
  selector: '#postContentEditor',
  height: 560,
  directionality: 'rtl',
  content_style: "body{font-family:Vazirmatn,Tahoma,sans-serif;font-size:15px;line-height:2;direction:rtl}",
  plugins: 'lists link image media table code codesample link autoresize advlist searchreplace visualblocks fullscreen preview wordcount directionality',
  toolbar: 'undo redo | blocks | bold italic underline | forecolor | alignright aligncenter alignleft | bullist numlist | link image media table | code preview fullscreen',
  menubar: false,
  branding: false,
  promotion: false,
  license_key: 'gpl',
  images_upload_url: '<?= site_url('admin/editor-image-upload.php') ?>',
  automatic_uploads: true,
  images_reuse_filename: true,
  relative_urls: false,
  remove_script_host: false,
  images_upload_handler: function (blobInfo) {
    return new Promise(function (resolve, reject) {
      var formData = new FormData();
      formData.append('file', blobInfo.blob(), blobInfo.filename());
      formData.append('csrf_token', '<?= csrf_token() ?>');
      fetch('<?= site_url('admin/editor-image-upload.php') ?>', { method: 'POST', body: formData, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.location) { resolve(data.location); }
          else { reject(data.error || 'خطا در آپلود تصویر'); }
        })
        .catch(function () { reject('خطا در ارتباط با سرور برای آپلود تصویر'); });
    });
  },
  setup: function (editor) {
    editor.on('keyup change SetContent undo redo', function () {
      runKeywordCount();
    });
  }
});

document.querySelector('form').addEventListener('submit', function () {
  tinymce.triggerSave();
});

// ---------- کلمه‌شمار ----------
function countOccurrences(haystack, needle) {
  if (!needle) return 0;
  // escape regex special chars in needle
  var escaped = needle.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  var matches = haystack.match(new RegExp(escaped, 'gi'));
  return matches ? matches.length : 0;
}

function runKeywordCount() {
  var keywordInput = document.getElementById('keywordCounterInput');
  var resultEl = document.getElementById('keywordCounterResult');
  var keyword = keywordInput.value.trim();

  var editor = tinymce.get('postContentEditor');
  var text = editor ? editor.getContent({ format: 'text' }) : document.getElementById('postContentEditor').value;

  var count = countOccurrences(text, keyword);
  resultEl.textContent = toPersianDigits(count);
}

function toPersianDigits(num) {
  var fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
  return String(num).replace(/\d/g, function (d) { return fa[d]; });
}

document.getElementById('keywordCounterInput').addEventListener('input', runKeywordCount);
</script>
<?php include __DIR__ . '/_footer.php'; ?>