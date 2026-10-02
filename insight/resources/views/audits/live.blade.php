<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تحلیل {{ $snapshot['host'] }} — سایت‌دوز اینسایت</title>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=vazirmatn:400,500,600">
    <link rel="stylesheet" href="{{ asset('insight/live.css') }}">
</head>
<body>
    <main class="shell" id="live-root">
        <header class="topbar">
            <a class="wordmark" href="/">سایت‌دوز اینسایت</a>
            <p class="host" id="audit-host">{{ $snapshot['host'] }}</p>
        </header>

        <section class="score-panel">
            <div class="score-block">
                <div class="score-ring" id="score-ring" style="--p: {{ $snapshot['overall_score'] ?? 0 }}">
                    <div class="score-core">
                        <span id="score-value">{{ $snapshot['overall_score'] ?? '—' }}</span>
                    </div>
                </div>
                <div>
                    <p class="eyebrow">امتیاز سایت‌دوز</p>
                    <h1 id="score-caption">@if ($snapshot['status'] === 'completed') نتیجه نهایی @else تا پایان تحلیل @endif</h1>
                    <p class="status-line"><span class="status-dot" id="status-dot" data-status="{{ $snapshot['status'] }}"></span><span id="status-label">{{ $snapshot['status_label'] }}</span></p>
                    <p class="analyzer" id="analyzer">@if ($snapshot['status'] === 'completed') تحلیل تمام شد @endif</p>
                </div>
            </div>
            <div class="progress-wrap">
                <div class="progress-meta"><span>پیشرفت</span><span id="progress-label">{{ $snapshot['progress'] }}٪</span></div>
                <div class="progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $snapshot['progress'] }}" id="progress">
                    <div class="progress-bar" id="progress-bar" style="width: {{ $snapshot['progress'] }}%"></div>
                </div>
            </div>
        </section>

        <section class="grid">
            <article class="panel">
                <div class="panel-head"><h2>فعالیت</h2></div>
                <ol class="feed" id="feed" aria-live="polite">
                    @forelse ($snapshot['activity'] as $line)
                        <li data-key="{{ $line['key'] }}" class="tone-{{ $line['tone'] }}">{{ $line['text'] }}</li>
                    @empty
                        <li class="placeholder" id="feed-placeholder">رویدادهای واقعی تحلیل اینجا دیده می‌شوند.</li>
                    @endforelse
                </ol>
            </article>
            <article class="panel">
                <div class="panel-head"><h2>صفحه‌های بررسی‌شده</h2><span id="page-count">{{ count($snapshot['pages']) }}</span></div>
                <ol class="pages" id="pages">
                    @forelse ($snapshot['pages'] as $page)
                        <li data-key="crawled:{{ $page['url'] }}"><span class="mark">✓</span><span>{{ $page['path'] }}</span></li>
                    @empty
                        <li class="placeholder" id="pages-placeholder">هنوز صفحه‌ای بررسی نشده است.</li>
                    @endforelse
                </ol>
            </article>
        </section>

        <section class="grid findings-grid">
            <article class="panel">
                <div class="panel-head"><h2>یافته‌های مهم</h2><span id="finding-count">{{ $snapshot['findings_count'] }}</span></div>
                <div class="findings" id="findings">
                    @forelse ($snapshot['live_findings'] as $finding)
                        <article class="finding severity-{{ $finding['severity'] }}" data-key="card:finding:{{ $finding['rule_key'] }}:{{ $finding['url'] }}">
                            <p class="severity">{{ $finding['severity'] }}</p>
                            <h3>{{ $finding['title'] }}</h3>
                            @if ($finding['url'])
                                <p class="finding-url">{{ $finding['url'] }}</p>
                            @endif
                        </article>
                    @empty
                        <p class="placeholder" id="findings-placeholder">یافته مهمی هنوز ثبت نشده است.</p>
                    @endforelse
                </div>
            </article>
            <article class="panel">
                <div class="panel-head"><h2>امتیاز دسته‌ها</h2></div>
                <div class="categories" id="categories">
                    @foreach ($snapshot['labels'] as $key => $label)
                        <article class="category" data-category="{{ $key }}">
                            <span>{{ $label }}</span>
                            <strong data-score>{{ $snapshot['categories'][$key] ?? '—' }}</strong>
                        </article>
                    @endforeach
                </div>
            </article>
        </section>

        <section id="failure" @class(['panel', 'failure', 'is-hidden' => $snapshot['status'] !== 'failed'])>
            <h2>تحلیل سایت متوقف شد</h2>
            <p id="failure-reason">{{ $snapshot['error'] }}</p>
            <form method="post" action="{{ route('audits.retry', $audit) }}">
                @csrf
                <button type="submit">تلاش مجدد</button>
            </form>
        </section>

        <section id="report" @class(['panel', 'report', 'is-hidden' => $snapshot['status'] !== 'completed'])>
            <h2>گزارش نهایی</h2>
            <div class="report-columns">
                <div>
                    <h3>مسائل مهم</h3>
                    <ol id="top-issues">
                        @foreach ($snapshot['top_issues'] as $issue)
                            <li><strong>{{ $issue['title'] }}</strong><span>{{ $issue['recommendation'] }}</span></li>
                        @endforeach
                    </ol>
                </div>
                <div>
                    <h3>پیشنهادها</h3>
                    <ol id="top-recommendations">
                        @foreach ($snapshot['top_recommendations'] as $recommendation)
                            <li>{{ $recommendation }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
            <h3>همه یافته‌ها</h3>
            <ol class="full-findings" id="full-findings">
                @foreach ($snapshot['findings'] as $finding)
                    <li class="severity-{{ $finding['severity'] }}"><span>{{ $finding['severity'] }}</span><strong>{{ $finding['title'] }}</strong><em>{{ $finding['recommendation'] }}</em></li>
                @endforeach
            </ol>
        </section>
    </main>
    <script id="audit-snapshot" type="application/json">@json($snapshot)</script>
    <script src="{{ asset('insight/vendor/pusher.min.js') }}"></script>
    <script src="{{ asset('insight/vendor/echo.iife.js') }}"></script>
    <script src="{{ asset('insight/live.js') }}"></script>
</body>
</html>
