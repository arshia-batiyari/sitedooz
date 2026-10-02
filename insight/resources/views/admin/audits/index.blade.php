@extends('layouts.insight')

@section('title', 'ممیزی‌ها')

@section('content')
    <h1 class="text-2xl font-extrabold mb-4">ممیزی‌ها</h1>
    <div class="overflow-x-auto bg-white border border-stone-300 rounded-2xl">
        <table class="w-full text-sm">
            <thead class="text-right border-b">
                <tr>
                    <th class="p-3">سایت</th>
                    <th class="p-3">وضعیت</th>
                    <th class="p-3">امتیاز</th>
                    <th class="p-3">زمان</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($audits as $audit)
                    <tr class="border-b border-stone-100">
                        <td class="p-3"><a class="text-pine" href="{{ route('admin.audits.show', $audit) }}">{{ $audit->host }}</a></td>
                        <td class="p-3">{{ $audit->status->label() }}</td>
                        <td class="p-3">{{ $audit->overall_score ?? '—' }}</td>
                        <td class="p-3">{{ $audit->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td class="p-3" colspan="4">هنوز ممیزی ثبت نشده است.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $audits->links() }}</div>
@endsection
