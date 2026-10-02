@extends('layouts.insight')

@section('title', 'بررسی سایت | سایت‌دوز اینسایت')

@section('content')
    <section class="grid gap-8 md:grid-cols-5 items-start">
        <div class="md:col-span-3">
            <p class="text-pine font-semibold text-sm mb-2">گزارش برای صاحب کسب‌وکار</p>
            <h1 class="text-3xl font-extrabold leading-relaxed mb-4">ببینید سایت‌تان کجا مشتری را از دست می‌دهد.</h1>
            <p class="text-stone-700 leading-8">اینسایت فقط یک چک‌لیست سئو نیست. گزارش می‌گوید چه چیزی دیده شده، چرا برای کسب‌وکار مهم است، و بهتر است اول کدام مورد اصلاح شود. نتیجه‌ها بر اساس نشانه‌های قابل اندازه‌گیری‌اند و جای بررسی انسانی را نمی‌گیرند.</p>
        </div>
        <form method="POST" action="{{ route('audits.store') }}" class="md:col-span-2 bg-white border border-stone-300 rounded-2xl p-5 space-y-3">
            @csrf
            <label class="block text-sm font-semibold" for="url">نشانی وب‌سایت</label>
            <input id="url" name="url" type="url" required value="{{ old('url') }}" placeholder="https://example.com" class="w-full border border-stone-300 rounded-xl px-3 py-2">
            @error('url')<p class="text-red-700 text-sm">{{ $message }}</p>@enderror
            <label class="block text-sm" for="name">نام</label>
            <input id="name" name="name" value="{{ old('name') }}" class="w-full border border-stone-300 rounded-xl px-3 py-2">
            <label class="block text-sm" for="mobile">موبایل</label>
            <input id="mobile" name="mobile" value="{{ old('mobile') }}" class="w-full border border-stone-300 rounded-xl px-3 py-2">
            <label class="block text-sm" for="email">ایمیل</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full border border-stone-300 rounded-xl px-3 py-2">
            @error('email')<p class="text-red-700 text-sm">{{ $message }}</p>@enderror
            <label class="block text-sm" for="business_name">نام کسب‌وکار</label>
            <input id="business_name" name="business_name" value="{{ old('business_name') }}" class="w-full border border-stone-300 rounded-xl px-3 py-2">
            <button type="submit" class="w-full bg-pine text-white rounded-xl py-2 font-semibold">شروع بررسی</button>
        </form>
    </section>
@endsection
