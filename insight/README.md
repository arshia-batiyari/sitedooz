# سایت‌دوز اینسایت

ابزار بررسی سایت برای گزارش کسب‌وکارمحور. منطق ممیزی داخل همین برنامه لاراول است و سایت بازاریابی اصلی را تغییر نمی‌دهد.

## اجرا

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

صف را با `php artisan queue:work` پردازش کنید. تا وقتی Redis نباشد، `QUEUE_CONNECTION=database` کافی است.

`PAGESPEED_API_KEY` اختیاری است. اگر خالی باشد، بخش سرعت «در دسترس نیست» می‌ماند و ممیزی شکست نمی‌خورد.

`INSIGHT_ADMIN_EMAIL` و `INSIGHT_ADMIN_PASSWORD` را در `.env` بگذارید و سیدر را اجرا کنید. ورود مدیریت: `/admin/login`.

API:

- `POST /api/audits`
- `GET /api/audits/{uuid}`
- `GET /api/audits/{uuid}/summary`
- `GET /api/audits/{uuid}/findings`
- `GET /api/audits/{uuid}/pages`
