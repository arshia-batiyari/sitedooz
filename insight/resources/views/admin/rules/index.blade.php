@extends('layouts.insight')

@section('title', 'قواعد')

@section('content')
    <h1 class="text-2xl font-extrabold mb-4">قواعد امتیازدهی</h1>
    <div class="overflow-x-auto bg-white border border-stone-300 rounded-2xl">
        <table class="w-full text-sm">
            <thead class="border-b">
                <tr>
                    <th class="p-3 text-right">کلید</th>
                    <th class="p-3 text-right">دسته</th>
                    <th class="p-3 text-right">شدت</th>
                    <th class="p-3 text-right">وزن</th>
                    <th class="p-3 text-right">فعال</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rules as $rule)
                    <tr class="border-b border-stone-100">
                        <td class="p-3"><a class="text-pine" href="{{ route('admin.rules.edit', $rule) }}">{{ $rule->key }}</a></td>
                        <td class="p-3">{{ config('audit.labels.'.$rule->category) }}</td>
                        <td class="p-3">{{ $rule->severity }}</td>
                        <td class="p-3">{{ $rule->weight }}</td>
                        <td class="p-3">{{ $rule->is_active ? 'بله' : 'خیر' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
