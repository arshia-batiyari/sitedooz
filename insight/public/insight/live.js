(function () {
    const node = document.getElementById('audit-snapshot');
    if (!node) {
        return;
    }

    const snapshot = JSON.parse(node.textContent || '{}');
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const known = new Set(Array.from(document.querySelectorAll('[data-key]')).map(function (element) {
        return element.getAttribute('data-key');
    }));
    let scoreShown = snapshot.overall_score;
    let countShown = snapshot.findings_count || 0;
    let countTarget = countShown;
    const categoryShown = Object.assign({}, snapshot.categories || {});
    const frames = {};
    let connectedOnce = false;
    let started = false;

    const feed = document.getElementById('feed');
    const pages = document.getElementById('pages');
    const findings = document.getElementById('findings');
    const statusLabel = document.getElementById('status-label');
    const statusDot = document.getElementById('status-dot');
    const analyzer = document.getElementById('analyzer');
    const scoreValue = document.getElementById('score-value');
    const scoreRing = document.getElementById('score-ring');
    const scoreCaption = document.getElementById('score-caption');
    const progress = document.getElementById('progress');
    const progressBar = document.getElementById('progress-bar');
    const progressLabel = document.getElementById('progress-label');
    const findingCount = document.getElementById('finding-count');
    const pageCount = document.getElementById('page-count');
    const failure = document.getElementById('failure');
    const failureReason = document.getElementById('failure-reason');
    const report = document.getElementById('report');

    const labels = {
        queued: 'در صف',
        crawling: 'در حال بررسی صفحات',
        analyzing: 'در حال تحلیل',
        generating_report: 'در حال ساخت گزارش',
        completed: 'تکمیل شده',
        failed: 'ناموفق',
    };

    function text(value) {
        return value === null || value === undefined ? '' : String(value);
    }

    function pathOf(url) {
        try {
            const path = new URL(url).pathname;
            return path === '' ? '/' : path;
        } catch (error) {
            return text(url);
        }
    }

    function clearPlaceholder(id) {
        const placeholder = document.getElementById(id);
        if (placeholder) {
            placeholder.remove();
        }
    }

    function append(list, key, className, content) {
        if (!list || known.has(key)) {
            return null;
        }
        known.add(key);
        const item = document.createElement('li');
        item.setAttribute('data-key', key);
        if (className) {
            item.className = className;
        }
        if (!reduce) {
            item.classList.add('enter');
        }
        content(item);
        list.appendChild(item);
        return item;
    }

    function feedLine(key, tone, message) {
        clearPlaceholder('feed-placeholder');
        append(feed, 'feed:' + key, 'tone-' + tone, function (item) {
            item.textContent = message;
        });
    }

    function setStatus(status, label) {
        if (statusLabel) {
            statusLabel.textContent = label || labels[status] || status;
        }
        if (statusDot) {
            statusDot.setAttribute('data-status', status);
        }
    }

    function setProgress(value) {
        const next = Math.max(0, Math.min(100, Math.round(Number(value) || 0)));
        if (progressBar) {
            progressBar.style.width = next + '%';
        }
        if (progress) {
            progress.setAttribute('aria-valuenow', String(next));
        }
        if (progressLabel) {
            progressLabel.textContent = next + '٪';
        }
    }

    function renderScore(value) {
        if (scoreValue) {
            scoreValue.textContent = value === null ? '—' : String(value);
        }
        if (scoreRing) {
            scoreRing.style.setProperty('--p', value === null ? '0' : String(value));
        }
    }

    function tween(key, from, to, duration, onFrame) {
        if (frames[key]) {
            cancelAnimationFrame(frames[key]);
        }
        if (reduce || from === null || from === undefined || from === to) {
            onFrame(to);
            frames[key] = 0;
            return;
        }
        const start = performance.now();
        const step = function (now) {
            const ratio = Math.min(1, (now - start) / duration);
            const eased = 1 - Math.pow(1 - ratio, 3);
            onFrame(Math.round(from + (to - from) * eased));
            if (ratio < 1) {
                frames[key] = requestAnimationFrame(step);
            }
        };
        frames[key] = requestAnimationFrame(step);
    }

    function setScore(value) {
        if (value === null || value === undefined || Number.isNaN(Number(value))) {
            return;
        }
        const target = Math.round(Number(value));
        if (scoreShown === null || scoreShown === undefined) {
            scoreShown = target;
            renderScore(target);
            return;
        }
        const from = scoreShown;
        tween('score', from, target, 520, function (current) {
            scoreShown = current;
            renderScore(current);
        });
    }

    function setCategory(key, value) {
        const tile = document.querySelector('[data-category="' + key + '"] [data-score]');
        if (!tile || value === null || value === undefined) {
            return;
        }
        const target = Math.round(Number(value));
        const from = Object.prototype.hasOwnProperty.call(categoryShown, key) ? categoryShown[key] : null;
        if (from === null || from === undefined) {
            categoryShown[key] = target;
            tile.textContent = String(target);
            return;
        }
        tween('category:' + key, from, target, 420, function (current) {
            categoryShown[key] = current;
            tile.textContent = String(current);
        });
    }

    function bumpCount() {
        countTarget += 1;
        const from = countShown;
        tween('count', from, countTarget, 320, function (current) {
            countShown = current;
            if (findingCount) {
                findingCount.textContent = String(current);
            }
        });
    }

    function addPage(url) {
        clearPlaceholder('pages-placeholder');
        const item = append(pages, 'crawled:' + url, '', function (row) {
            const mark = document.createElement('span');
            mark.className = 'mark';
            mark.textContent = '✓';
            const label = document.createElement('span');
            label.textContent = pathOf(url);
            row.appendChild(mark);
            row.appendChild(label);
        });
        if (item && pageCount) {
            const current = pages.querySelectorAll('[data-key]').length;
            pageCount.textContent = String(current);
        }
        feedLine('page:' + url, 'check', 'صفحه ' + pathOf(url) + ' بررسی شد');
    }

    function metricLine(metric) {
        const name = metric.name;
        const value = Number(metric.value);
        if (name === 'robots_txt') {
            return value >= 1 ? 'robots.txt بررسی شد' : 'robots.txt پیدا نشد';
        }
        if (name === 'sitemap') {
            return value > 0 ? 'نقشه سایت پیدا شد' : 'نقشه سایت پیدا نشد';
        }
        if (name === 'https') {
            return value >= 1 ? 'اتصال HTTPS بررسی شد' : 'سایت از HTTPS استفاده نمی‌کند';
        }
        if (name === 'response_time') {
            return 'زمان پاسخ صفحه اصلی: ' + Math.round(value) + ' میلی‌ثانیه';
        }
        return null;
    }

    function addMetric(metric) {
        const message = metricLine(metric);
        if (message) {
            feedLine('metric:' + metric.name, 'check', message);
        }
    }

    function important(severity) {
        return severity === 'critical' || severity === 'high' || severity === 'medium';
    }

    function rank(severity) {
        return { critical: 0, high: 1, medium: 2, low: 3, info: 4 }[severity] ?? 5;
    }

    function addFinding(finding) {
        const key = 'finding:' + finding.rule_key + ':' + (finding.url || '');
        if (known.has('feed:' + key)) {
            return;
        }
        bumpCount();
        feedLine(key, important(finding.severity) ? 'warn' : 'check', finding.title);
        if (!important(finding.severity) || known.has('card:' + key)) {
            return;
        }
        known.add('card:' + key);
        clearPlaceholder('findings-placeholder');
        const card = document.createElement('article');
        card.className = 'finding severity-' + finding.severity + (reduce ? '' : ' enter');
        card.setAttribute('data-key', 'card:' + key);
        const severity = document.createElement('p');
        severity.className = 'severity';
        severity.textContent = finding.severity;
        const title = document.createElement('h3');
        title.textContent = finding.title;
        card.appendChild(severity);
        card.appendChild(title);
        if (finding.url) {
            const url = document.createElement('p');
            url.className = 'finding-url';
            url.textContent = finding.url;
            card.appendChild(url);
        }
        findings.appendChild(card);
        trimFindings();
    }

    function trimFindings() {
        const cards = Array.from(findings.querySelectorAll('.finding'));
        if (cards.length <= 4) {
            return;
        }
        cards.sort(function (left, right) {
            return rank(severityOf(left)) - rank(severityOf(right));
        });
        cards.slice(4).forEach(function (card) {
            card.remove();
        });
    }

    function severityOf(card) {
        const match = card.className.match(/severity-([a-z]+)/);
        return match ? match[1] : 'info';
    }

    function showReport(payload) {
        setStatus('completed', labels.completed);
        setProgress(100);
        if (scoreCaption) {
            scoreCaption.textContent = 'نتیجه نهایی';
        }
        if (analyzer) {
            analyzer.textContent = 'تحلیل تمام شد';
        }
        setScore(payload.overall_score);
        Object.keys(payload.categories || {}).forEach(function (key) {
            setCategory(key, payload.categories[key]);
        });
        fillList(document.getElementById('top-issues'), payload.top_issues || [], function (item, issue) {
            const title = document.createElement('strong');
            title.textContent = issue.title;
            const recommendation = document.createElement('span');
            recommendation.textContent = issue.recommendation;
            item.appendChild(title);
            item.appendChild(recommendation);
        });
        fillList(document.getElementById('top-recommendations'), payload.top_recommendations || [], function (item, recommendation) {
            item.textContent = recommendation;
        });
        fillList(document.getElementById('full-findings'), payload.findings || [], function (item, finding) {
            item.className = 'severity-' + finding.severity;
            const severity = document.createElement('span');
            severity.textContent = finding.severity;
            const title = document.createElement('strong');
            title.textContent = finding.title;
            const recommendation = document.createElement('em');
            recommendation.textContent = finding.recommendation;
            item.appendChild(severity);
            item.appendChild(title);
            item.appendChild(recommendation);
        });
        if (report) {
            report.classList.remove('is-hidden');
        }
    }

    function fillList(list, rows, render) {
        if (!list) {
            return;
        }
        list.replaceChildren();
        rows.forEach(function (row) {
            const item = document.createElement('li');
            render(item, row);
            list.appendChild(item);
        });
    }

    function showFailure(reason) {
        setStatus('failed', labels.failed);
        if (failureReason) {
            failureReason.textContent = text(reason);
        }
        if (failure) {
            failure.classList.remove('is-hidden');
        }
        if (analyzer) {
            analyzer.textContent = '';
        }
    }

    function onPayload(eventName, payload) {
        if (eventName === 'AuditStarted' || eventName === 'CrawlerStarted') {
            setStatus('crawling');
            return;
        }
        if (eventName === 'PageCrawled') {
            addPage(payload.url);
            if (payload.progress !== undefined) {
                setProgress(payload.progress);
            }
            return;
        }
        if (eventName === 'MetricCalculated') {
            addMetric(payload);
            return;
        }
        if (eventName === 'FindingDetected') {
            addFinding(payload);
            return;
        }
        if (eventName === 'AnalyzerStarted') {
            setStatus('analyzing');
            if (analyzer) {
                analyzer.textContent = 'در حال تحلیل ' + payload.label;
            }
            feedLine('analyzer-start:' + payload.category, 'check', 'تحلیل ' + payload.label + ' آغاز شد');
            return;
        }
        if (eventName === 'AnalyzerCompleted') {
            if (analyzer) {
                analyzer.textContent = 'تحلیل ' + payload.label + ' تکمیل شد';
            }
            feedLine('analyzer:' + payload.category, 'check', 'تحلیل ' + payload.label + ' تکمیل شد');
            if (payload.progress !== undefined) {
                setProgress(payload.progress);
            }
            return;
        }
        if (eventName === 'ScoreUpdated') {
            setScore(payload.overall_score);
            Object.keys(payload.categories || {}).forEach(function (key) {
                setCategory(key, payload.categories[key]);
            });
            if (payload.progress !== undefined) {
                setProgress(payload.progress);
            }
            return;
        }
        if (eventName === 'AuditCompleted') {
            showReport(payload);
            return;
        }
        if (eventName === 'AuditFailed') {
            showFailure(payload.reason);
        }
    }

    async function startAudit() {
        if (started || snapshot.status !== 'queued') {
            return;
        }
        started = true;
        const token = document.querySelector('meta[name="csrf-token"]');
        try {
            const response = await fetch('/audits/' + snapshot.uuid + '/start', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token ? token.content : '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (!response.ok) {
                setStatus('queued', 'تحلیل شروع نشد. صفحه را تازه‌سازی کنید.');
            }
        } catch (error) {
            setStatus('queued', 'تحلیل شروع نشد. صفحه را تازه‌سازی کنید.');
        }
    }

    function connect() {
        if (snapshot.status === 'completed' || snapshot.status === 'failed') {
            return;
        }
        if (!snapshot.broadcast || !snapshot.broadcast.key || typeof Echo === 'undefined') {
            setStatus(snapshot.status, 'پخش زنده پیکربندی نشده است.');
            return;
        }
        const echo = new Echo({
            broadcaster: 'reverb',
            key: snapshot.broadcast.key,
            wsHost: snapshot.broadcast.host,
            wsPort: snapshot.broadcast.port,
            wssPort: snapshot.broadcast.port,
            forceTLS: snapshot.broadcast.scheme === 'https',
            enabledTransports: ['ws', 'wss'],
        });
        const channel = echo.channel('audit.' + snapshot.uuid);
        [
            'AuditStarted',
            'CrawlerStarted',
            'PageCrawled',
            'FindingDetected',
            'AnalyzerStarted',
            'AnalyzerCompleted',
            'MetricCalculated',
            'ScoreUpdated',
            'AuditCompleted',
            'AuditFailed',
        ].forEach(function (name) {
            channel.listen('.' + name, function (payload) {
                onPayload(name, payload || {});
            });
        });
        const connection = echo.connector.pusher.connection;
        const onConnected = function () {
            if (connectedOnce) {
                return;
            }
            connectedOnce = true;
            feedLine('connection', 'check', 'اتصال برقرار شد');
            startAudit();
        };
        connection.bind('connected', onConnected);
        connection.bind('unavailable', function () {
            if (!connectedOnce) {
                setStatus(snapshot.status, 'ارتباط زنده برقرار نشد.');
            }
        });
        connection.bind('failed', function () {
            if (!connectedOnce) {
                setStatus(snapshot.status, 'ارتباط زنده برقرار نشد.');
            }
        });
        if (connection.state === 'connected') {
            onConnected();
        }
    }

    requestAnimationFrame(function () {
        document.body.setAttribute('data-live', '1');
    });
    connect();
})();
