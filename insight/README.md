# سایت‌دوز اینسایت

فاز ۱: ساخت ممیزی، اعتبارسنجی نشانی، جلوگیری از درخواست به آدرس‌های داخلی، و خزش محدود. گزارش ظاهری، PageSpeed، هوش مصنوعی و سرچ کنسول در این فاز نیستند.

سایت بازاریابی اصلی تغییر نمی‌کند. این برنامه دیتابیس خودش را دارد.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
php artisan queue:work
```

- `POST /api/audits` با `{ "url": "https://example.com" }` شناسه و وضعیت `queued` برمی‌گرداند.
- `GET /api/audits/{uuid}` وضعیت را برمی‌گرداند.
- `GET /api/audits/{uuid}/pages` صفحه‌های خزش‌شده را برمی‌گرداند.

خزش `robots.txt` را رعایت می‌کند، `sitemap.xml` را اگر باشد ثبت می‌کند، نشانی را نرمال می‌کند، و با سقف صفحه، عمق، و زمان درخواست متوقف می‌شود.
