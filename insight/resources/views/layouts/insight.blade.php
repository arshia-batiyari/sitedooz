<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'سایت‌دوز اینسایت')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Vazirmatn', 'Tahoma', 'sans-serif'] },
                    colors: {
                        ink: '#1c1915',
                        paper: '#f6f1e7',
                        pine: '#1f6f5b',
                        clay: '#c4652d',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-paper text-ink font-sans min-h-screen">
    <header class="border-b border-stone-300">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="font-extrabold text-lg">سایت‌دوز اینسایت</a>
            <nav class="text-sm flex gap-4">
                @if (session()->has('insight_admin_id'))
                    <a href="{{ route('admin.audits.index') }}">ممیزی‌ها</a>
                    <a href="{{ route('admin.rules.index') }}">قواعد</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit">خروج</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}">مدیریت</a>
                @endif
            </nav>
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
