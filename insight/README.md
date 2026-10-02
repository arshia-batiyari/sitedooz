# سایت‌دوز اینسایت

ممیزی وب‌سایت با صف، خزش محدود، و نمایش زنده رویدادهای واقعی. گزارش از همان بررسی‌هایی ساخته می‌شود که هنگام خزش انجام شده‌اند: HTTPS، robots.txt، نقشه سایت، وضعیت صفحه‌ها، لینک‌های شکسته، عنوان و توضیح متا و H1 و canonical، و زمان پاسخ صفحه اصلی اگر اندازه‌گیری شده باشد. PageSpeed، هوش مصنوعی و سرچ کنسول در این نسخه نیستند.

سایت بازاریابی اصلی تغییر نمی‌کند. این برنامه دیتابیس خودش را دارد. صف روی دیتابیس است و به Redis نیاز ندارد. پخش زنده با Laravel Reverb است و مقیاس افقی Reverb خاموش است.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan reverb:install
php artisan migrate --seed
```

در `.env` این مقدارها لازم است. پورت ۸۰۸۰ برای سایت بازاریابی است؛ Reverb باید روی ۸۰۸۱ بماند.

```
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=127.0.0.1
REVERB_PORT=8081
REVERB_SCHEME=http
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8081
REVERB_SCALING_ENABLED=false
```

سه فرایند را با هم اجرا کنید:

```bash
php artisan serve --host=127.0.0.1 --port=8000
php artisan queue:work --tries=1 --timeout=120
php artisan reverb:start
```

صفحه `/` نشانی را می‌گیرد و به صفحه زنده می‌رود. اتصال سوکت که برقرار شد، کار ممیزی از صف شروع می‌شود. نمودار ساختار از همان رویداد `PageCrawled` ساخته می‌شود: هر گره یک نشانی واقعی است و یال فقط وقتی رسم می‌شود که صفحه از لینک داخلی صفحهٔ دیگری پیدا شده باشد. اگر Reverb در دسترس نباشد، ممیزی در صورت شروع‌شدن ادامه پیدا می‌کند و تازه‌سازی صفحه آخرین وضعیت ذخیره‌شده را نشان می‌دهد.

- `POST /api/audits` با `{ "url": "https://example.com" }` ممیزی را می‌سازد و همان لحظه در صف می‌گذارد. پاسخ وضعیت `queued` است.
- `GET /api/audits/{uuid}` وضعیت ذخیره‌شده را برمی‌گرداند.
- `GET /api/audits/{uuid}/pages` صفحه‌های خزش‌شده را برمی‌گرداند.
- `POST /audits/{uuid}/start` کار را فقط وقتی وضعیت `queued` است در صف می‌گذارد.
- `POST /audits/{uuid}/retry` ممیزی ناموفق را به صف برمی‌گرداند.

رویدادهای عمومی کانال `audit.{uuid}` این‌ها هستند: `AuditStarted`، `CrawlerStarted`، `PageCrawled`، `FindingDetected`، `AnalyzerStarted`، `AnalyzerCompleted`، `MetricCalculated`، `ScoreUpdated`، `AuditCompleted`، `AuditFailed`. شناسه ممیزی capability کانال است. متن استثنا و اطلاعات داخلی سرور در رویدادها نیست.
