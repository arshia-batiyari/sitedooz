@extends('layouts.insight')

@section('title', $rule->key)

@section('content')
    <h1 class="text-2xl font-extrabold mb-2">{{ $rule->name }}</h1>
    <p class="text-sm text-stone-600 mb-4">{{ $rule->key }}</p>
    <form method="POST" action="{{ route('admin.rules.update', $rule) }}" class="bg-white border border-stone-300 rounded-2xl p-5 space-y-3 max-w-xl">
        @csrf
        @method('PUT')
        <label class="block text-sm" for="severity">شدت</label>
        <select id="severity" name="severity" class="w-full border border-stone-300 rounded-xl px-3 py-2">
            @foreach (['critical', 'high', 'medium', 'low', 'info'] as $severity)
                <option value="{{ $severity }}" @selected(old('severity', $rule->severity) === $severity)>{{ $severity }}</option>
            @endforeach
        </select>
        <label class="block text-sm" for="weight">وزن داخل دسته</label>
        <input id="weight" name="weight" type="number" min="1" max="100" value="{{ old('weight', $rule->weight) }}" class="w-full border border-stone-300 rounded-xl px-3 py-2">
        <label class="flex gap-2 items-center text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $rule->is_active))>
            فعال
        </label>
        <label class="block text-sm" for="recommendation">پیشنهاد</label>
        <textarea id="recommendation" name="recommendation" rows="4" class="w-full border border-stone-300 rounded-xl px-3 py-2">{{ old('recommendation', $rule->recommendation) }}</textarea>
        <button class="bg-pine text-white rounded-xl px-4 py-2">ذخیره</button>
    </form>
@endsection
