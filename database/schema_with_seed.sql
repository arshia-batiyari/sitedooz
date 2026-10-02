CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(120) NOT NULL PRIMARY KEY,
  setting_value TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS posts (
  id VARCHAR(40) NOT NULL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  category VARCHAR(120) NULL,
  author VARCHAR(120) NULL,
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  published_at DATE NULL,
  cover VARCHAR(255) NULL,
  image_alt VARCHAR(255) NULL,
  meta_title VARCHAR(255) NULL,
  meta_description TEXT NULL,
  excerpt TEXT NULL,
  content LONGTEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_posts_status_date (status, published_at),
  INDEX idx_posts_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS portfolio_items (
  id VARCHAR(40) NOT NULL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle TEXT NULL,
  image VARCHAR(255) NULL,
  url VARCHAR(255) NULL,
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_portfolio_status_sort (status, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS seo_items (
  id VARCHAR(40) NOT NULL PRIMARY KEY,
  keyword VARCHAR(255) NOT NULL,
  summary TEXT NULL,
  rank VARCHAR(120) NULL,
  image VARCHAR(255) NULL,
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_seo_status_sort (status, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS consultation_requests (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(160) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  business_type VARCHAR(120) NULL,
  project_type VARCHAR(120) NULL,
  preferred_contact VARCHAR(80) NULL,
  budget VARCHAR(120) NULL,
  message TEXT NULL,
  status ENUM('new','contacted','done') NOT NULL DEFAULT 'new',
  ip_hash CHAR(64) NULL,
  user_agent VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_consultation_status_date (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



SET NAMES utf8mb4;
TRUNCATE TABLE settings;
TRUNCATE TABLE posts;
TRUNCATE TABLE portfolio_items;
TRUNCATE TABLE seo_items;
TRUNCATE TABLE consultation_requests;

INSERT INTO settings (setting_key, setting_value) VALUES
('site_title', 'سایت دوز | طراحی سایت حرفه‌ای'),
('site_description', 'طراحی سایت سریع، سبک و قابل مدیریت برای کسب‌وکارهایی که می‌خواهند حضور آنلاین حرفه‌ای داشته باشند.'),
('phone', '09334496439'),
('whatsapp', '09334496439'),
('hero_title', 'طراحی سایت حرفه‌ای، سریع و قابل مدیریت'),
('hero_description', 'سایت دوز وب‌سایتی تمیز، واکنش‌گرا و قابل مدیریت می‌سازد؛ با پنل ساده برای محتوا، نمونه‌کار، تصاویر و مقالات.'),
('footer_title', 'برای شروع طراحی سایت آماده‌اید؟'),
('footer_description', 'برای بررسی نیازهای سایت و انتخاب ساختار مناسب، با سایت دوز تماس بگیرید.');

INSERT INTO posts (id,title,slug,category,author,status,published_at,cover,meta_title,meta_description,excerpt,content) VALUES
('post_001','چرا سرعت سایت برای تجربه کاربر مهم است؟','fast-lightweight-website-design','طراحی سایت','تیم سایت دوز','published','2026-06-20','images/hero-optimized.webp','طراحی سایت سبک و سریع | سایت دوز','چرا سرعت، ساختار تمیز و کدنویسی سبک تجربه بهتری برای کاربران می‌سازد.','سرعت، تجربه کاربری و مدیریت آسان سه عامل مهم برای یک سایت حرفه‌ای هستند.','<p>طراحی سایت سبک فقط ظاهر ساده نیست؛ یعنی صفحه سریع باز شود، کدهای اضافه کم باشد و کاربر در موبایل هم راحت مسیر خود را پیدا کند.</p><h2>چرا سرعت سایت مهم است؟</h2><p>کاربر قبل از اینکه طراحی را ببیند، باید صفحه برایش باز شود. هر ثانیه تأخیر می‌تواند باعث خروج کاربر شود.</p><h2>نقش ساختار HTML در کیفیت سایت</h2><p>عنوان‌ها، لینک‌های داخلی، متن‌های خوانا و تصاویر بهینه باعث می‌شوند سایت مرتب‌تر و قابل توسعه‌تر باشد.</p><h2>جمع‌بندی</h2><p>برای یک صفحه خدماتی، سبک بودن سایت، طراحی حرفه‌ای و مسیر تماس واضح سه بخش جدایی‌ناپذیر هستند.</p>'),
('post_002','صفحه خدمات سایت باید چه بخش‌هایی داشته باشد؟','service-page-structure','طراحی سایت','تیم سایت دوز','published','2026-06-17','images/portfolio-corporate-photo.webp','ساختار صفحه خدمات برای جذب مشتری | سایت دوز','یک صفحه خدمات خوب باید نیاز کاربر را پاسخ دهد و مسیر اعتمادسازی، نمونه‌کار و تماس را کامل کند.','صفحه خدمات فقط معرفی نیست؛ باید کاربر را از سوال اولیه تا درخواست مشاوره همراهی کند.','<p>صفحه خدمات باید دقیق، شفاف و متقاعدکننده باشد. کاربر باید خیلی سریع بفهمد چه خدمتی می‌گیرد، چه نتیجه‌ای برایش دارد و چطور می‌تواند اقدام کند.</p><h2>بخش‌های ضروری صفحه خدمات</h2><ul><li>معرفی کوتاه خدمت</li><li>مزایا و خروجی‌های قابل انتظار</li><li>نمونه‌کار یا نتایج قبلی</li><li>سوالات پرتکرار</li><li>دعوت به تماس واضح</li></ul><h2>جمع‌بندی</h2><p>هر صفحه خدمات باید مثل یک مسیر فروش آرام و قابل اعتماد عمل کند.</p>'),
('post_003','چطور بلاگ سایت به فروش خدمات کمک می‌کند؟','blog-content-for-service-business','تولید محتوا','تیم سایت دوز','published','2026-06-14','images/portfolio-shop-photo.webp','نقش بلاگ در فروش خدمات و جذب مشتری | سایت دوز','بلاگ حرفه‌ای می‌تواند اعتماد ایجاد کند و کاربران را با خدمات شما آشنا کند.','بلاگ زمانی ارزشمند است که فقط مقاله تولید نکند؛ بلکه کاربر را به سمت تصمیم درست هدایت کند.','<p>بلاگ برای سایت خدماتی یک ابزار جذب و اعتمادسازی است. وقتی کاربر هنوز آماده خرید نیست، مقاله خوب می‌تواند او را وارد سایت کند.</p><h2>بلاگ چه کمکی به سایت می‌کند؟</h2><p>مقالات هدفمند به سوالات واقعی کاربران پاسخ می‌دهند و از طریق لینک‌سازی داخلی، مسیر رسیدن به صفحات خدمات را ساده‌تر می‌کنند.</p><h2>چه مقالاتی ارزشمندترند؟</h2><p>مقالاتی که مشکل کاربر را حل می‌کنند، با تجربه عملی نوشته می‌شوند و در پایان مسیر مناسبی برای مشاوره یا مشاهده خدمات دارند.</p>');

INSERT INTO portfolio_items (id,title,subtitle,image,url,status,sort_order) VALUES
('pf_001','سایت شرکتی','طراحی سایت معرفی خدمات با ساختار سریع و قابل توسعه','images/portfolio-corporate-photo.webp','#','published',0),
('pf_002','فروشگاه اینترنتی','طراحی فروشگاه حرفه‌ای با مسیر خرید ساده','images/portfolio-shop-photo.webp','#','published',1),
('pf_003','سایت آموزشی','طراحی پلتفرم آموزشی سبک و کاربرپسند','images/portfolio-academy-photo.webp','#','published',2);

INSERT INTO seo_items (id,keyword,summary,rank,image,status,sort_order) VALUES
('seo_001','طراحی سایت فروشگاهی، ساخت فروشگاه اینترنتی، طراحی فروشگاه آنلاین','نمونه‌ای از رشد رتبه کلمات فروشگاهی؛ مناسب برای اینکه کاربر سریع متوجه شود کدام عبارت‌ها به صفحه اول رسیده‌اند.','رشد پروژه فروشگاهی','images/seo-growth-visual.svg','published',0),
('seo_002','طراحی سایت شرکتی، طراحی سایت خدماتی، شرکت طراحی سایت','در این نمونه، تمرکز روی کلمات خدماتی و شرکتی بوده تا مسیر دیده‌شدن و دریافت تماس شفاف‌تر شود.','رشد پروژه شرکتی','images/web-growth-dashboard.svg','published',1),
('seo_003','سئو سایت وردپرس، خدمات سئو سایت، بهینه سازی سایت','نمایش خلاصه رتبه‌ها به شکل گزارش؛ هم خوانا برای کاربر، هم مناسب برای اسکرین‌شات و ارائه نتیجه.','رشد پروژه SEO','images/portfolio-corporate-photo.webp','published',2);
