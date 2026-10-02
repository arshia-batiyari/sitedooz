<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تحلیل سایت — سایت‌دوز اینسایت</title>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=vazirmatn:400,500,600">
    <link rel="stylesheet" href="{{ asset('insight/live.css') }}">
</head>
<body>
    <main class="shell shell-narrow">
        <p class="wordmark">سایت‌دوز اینسایت</p>
        <section class="panel form-panel">
            <h1>تحلیل زنده وب‌سایت</h1>
            <p class="lede">نشانی سایت را وارد کنید. روند بررسی، صفحه‌ها و یافته‌ها همان لحظه که در سرور اتفاق می‌افتند نمایش داده می‌شوند.</p>
            <form method="post" action="/audits">
                @csrf
                <label for="url">نشانی وب‌سایت</label>
                <input id="url" name="url" type="url" inputmode="url" autocomplete="url" placeholder="https://example.com" value="{{ old('url') }}" required>
                @error('url')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <button type="submit">شروع تحلیل</button>
            </form>
        </section>
    </main>
</body>
</html>
