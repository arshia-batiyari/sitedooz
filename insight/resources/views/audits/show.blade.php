@extends('layouts.insight')

@section('title', 'گزارش ممیزی')

@section('content')
    <div id="audit-report" data-status="{{ $audit->status->value }}" data-poll="{{ route('api.audits.show', $audit) }}">
        <p class="text-sm text-stone-600 mb-2">{{ $audit->url }}</p>
        <h1 class="text-2xl font-extrabold mb-2">گزارش سایت‌دوز</h1>
        <p id="audit-status" class="mb-6">وضعیت: {{ $audit->status->label() }}</p>

        @if ($audit->status->value === 'failed')
            <p class="bg-red-50 border border-red-200 rounded-xl p-4">{{ $audit->error_message }}</p>
        @elseif (! $audit->status->isFinished())
            <p class="bg-white border border-stone-300 rounded-xl p-4">بررسی در جریان است. این صفحه خودش به‌روز می‌شود.</p>
        @else
            <p class="text-stone-700 mb-6 leading-8">امتیازها از قواعد فعال و داده‌های همین بررسی ساخته شده‌اند. اگر بخشی داده کافی نداشته باشد، در میانگین کل اثر داده نمی‌شود. زبان گزارش قطعی نیست: هر مورد می‌تواند فرصت بهبود باشد، نه حکم قطعی افت رتبه.</p>
            <section class="bg-ink text-paper rounded-2xl p-6 mb-6">
                <p class="text-sm">امتیاز کلی</p>
                <p class="text-5xl font-extrabold">{{ $report['overall_score'] === null ? '—' : $report['overall_score'] }}</p>
            </section>
            <section class="grid sm:grid-cols-2 gap-3 mb-8">
                @foreach ($report['category_scores'] as $category)
                    <article class="bg-white border border-stone-300 rounded-xl p-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span>{{ $category['label'] }}</span>
                            <span>{{ $category['score'] === null ? 'داده کافی نیست' : $category['score'] }}</span>
                        </div>
                        <div class="h-2 bg-stone-200 rounded-full overflow-hidden">
                            <div class="h-full bg-pine" style="width: {{ $category['score'] ?? 0 }}%"></div>
                        </div>
                    </article>
                @endforeach
            </section>

            <h2 class="text-xl font-extrabold mb-3">پنج اقدام اول</h2>
            <ol class="space-y-3 mb-8">
                @forelse ($report['top_actions'] as $finding)
                    @include('audits.partials.finding', ['finding' => $finding])
                @empty
                    <li class="text-stone-600">مورد اولویتی خارج از بخش «نیاز به بررسی انسانی» ثبت نشده است.</li>
                @endforelse
            </ol>

            <h2 class="text-xl font-extrabold mb-3">موارد بحرانی و مهم</h2>
            <div class="space-y-3 mb-8">
                @forelse ($report['critical_and_high'] as $finding)
                    @include('audits.partials.finding', ['finding' => $finding])
                @empty
                    <p class="text-stone-600">مورد بحرانی یا با اهمیت بالا در این بررسی ثبت نشد.</p>
                @endforelse
            </div>

            <h2 class="text-xl font-extrabold mb-3">فرصت‌های تبدیل</h2>
            <div class="space-y-3 mb-8">
                @forelse ($report['conversion_opportunities'] as $finding)
                    @include('audits.partials.finding', ['finding' => $finding])
                @empty
                    <p class="text-stone-600">در نشانه‌های قابل اندازه‌گیری، فرصت تبدیل جداگانه‌ای دیده نشد.</p>
                @endforelse
            </div>

            <h2 class="text-xl font-extrabold mb-3">نیاز به بررسی انسانی</h2>
            <div class="space-y-3 mb-8">
                @forelse ($report['needs_human_review'] as $finding)
                    @include('audits.partials.finding', ['finding' => $finding])
                @empty
                    <p class="text-stone-600">موردی برای بررسی انسانی جدا نشده است.</p>
                @endforelse
            </div>

            <h2 class="text-xl font-extrabold mb-3">صفحات مشکل‌دار</h2>
            <ul class="space-y-2 mb-8">
                @forelse ($report['page_problems'] as $page)
                    <li class="bg-white border border-stone-300 rounded-xl p-3 text-sm">{{ $page['final_url'] }} — وضعیت {{ $page['status_code'] }}</li>
                @empty
                    <li class="text-stone-600">صفحه خطادار یا مسدودشده‌ای در محدوده خزش ثبت نشد.</li>
                @endforelse
            </ul>
        @endif
    </div>
    <script>
        const root = document.getElementById('audit-report');
        const finished = ['completed', 'failed'];
        async function poll() {
            if (finished.includes(root.dataset.status)) return;
            const response = await fetch(root.dataset.poll, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) return;
            const payload = await response.json();
            const status = payload.data.status;
            document.getElementById('audit-status').textContent = 'وضعیت: ' + payload.data.status_label;
            if (finished.includes(status)) {
                location.reload();
                return;
            }
            setTimeout(poll, 2000);
        }
        poll();
    </script>
@endsection
