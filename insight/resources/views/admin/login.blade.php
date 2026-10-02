@extends('layouts.insight')

@section('title', 'ورود مدیریت')

@section('content')
    <form method="POST" action="{{ route('admin.login.store') }}" class="max-w-md bg-white border border-stone-300 rounded-2xl p-6 space-y-3">
        @csrf
        <h1 class="text-xl font-extrabold">ورود به مدیریت اینسایت</h1>
        <label class="block text-sm" for="email">ایمیل</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full border border-stone-300 rounded-xl px-3 py-2">
        @error('email')<p class="text-red-700 text-sm">{{ $message }}</p>@enderror
        <label class="block text-sm" for="password">رمز</label>
        <input id="password" name="password" type="password" class="w-full border border-stone-300 rounded-xl px-3 py-2">
        <button type="submit" class="bg-pine text-white rounded-xl px-4 py-2">ورود</button>
    </form>
@endsection
