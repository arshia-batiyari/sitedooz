@extends('layouts.insight')

@section('title', 'جزئیات ممیزی')

@section('content')
    <p class="text-sm text-stone-600">{{ $audit->uuid }}</p>
    <h1 class="text-2xl font-extrabold mb-2 break-all">{{ $audit->url }}</h1>
    <p class="mb-4">وضعیت: {{ $audit->status->label() }} @if($audit->overall_score !== null) — امتیاز {{ $audit->overall_score }} @endif</p>
    @if (session('status'))<p class="mb-4">{{ session('status') }}</p>@endif
    @if ($audit->status->value === 'failed')
        <p class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">{{ $audit->error_message }}</p>
        <form method="POST" action="{{ route('admin.audits.retry', $audit) }}">
            @csrf
            <button class="bg-clay text-white rounded-xl px-4 py-2">تلاش دوباره</button>
        </form>
    @endif
    <h2 class="font-bold mt-6 mb-2">سرنخ</h2>
    <p class="text-sm">{{ $audit->name }} · {{ $audit->mobile }} · {{ $audit->email }} · {{ $audit->business_name }} · {{ $audit->lead_source }}</p>
    <h2 class="font-bold mt-6 mb-3">یافته‌ها</h2>
    <div class="space-y-3">
        @foreach ($report['findings_by_category'] as $items)
            @foreach ($items as $finding)
                @include('audits.partials.finding', ['finding' => $finding])
            @endforeach
        @endforeach
    </div>
@endsection
