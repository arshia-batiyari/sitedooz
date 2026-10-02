(function () {
    const LIMIT = 48;
    const canvas = document.getElementById('site-graph');
    const empty = document.getElementById('graph-empty');
    const summary = document.getElementById('graph-summary');
    const drawer = document.getElementById('page-drawer');
    const drawerBody = document.getElementById('drawer-body');
    if (!canvas || !drawer || !drawerBody) {
        return;
    }

    const ctx = canvas.getContext('2d');
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const pages = [];
    const findings = [];
    const clusters = new Map();
    let current = [];
    let responseMs = null;
    let finished = false;
    let selectedId = null;
    let hoverId = null;
    let cam = { x: 0, y: 0, scale: 1 };
    let fitted = { x: 0, y: 0, scale: 1 };
    let dragging = false;
    let moved = false;
    let lastPointer = null;
    let running = false;

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

    function rank(severity) {
        return { critical: 0, high: 1, medium: 2, low: 3, info: 4 }[severity] ?? 5;
    }

    function pageByUrl(url) {
        if (!url) {
            return null;
        }
        return pages.find(function (page) {
            return page.url === url || page.finalUrl === url;
        }) || null;
    }

    function stateOf(page) {
        if (!page.statusCode || page.statusCode >= 400) {
            return 'error';
        }
        const severities = page.findings.map(function (finding) {
            return finding.severity;
        });
        if (severities.includes('critical') || severities.includes('high')) {
            return 'error';
        }
        if (severities.includes('medium')) {
            return 'warning';
        }
        return finished ? 'completed' : 'discovered';
    }

    function buildItems() {
        const visible = pages.slice(0, LIMIT).map(function (page) {
            return {
                id: page.url,
                cluster: false,
                page: page,
                depth: page.depth || 0,
                path: page.depth === 0 ? 'خانه' : page.path,
            };
        });
        const groups = new Map();
        pages.slice(LIMIT).forEach(function (page) {
            const key = page.discoveredFrom || page.url;
            if (!groups.has(key)) {
                groups.set(key, []);
            }
            groups.get(key).push(page);
        });
        groups.forEach(function (group, key) {
            if (!clusters.has(key)) {
                clusters.set(key, {});
            }
            visible.push({
                id: 'cluster:' + key,
                cluster: true,
                parentUrl: key,
                pages: group,
                depth: group[0].depth || 1,
                path: group.length + ' صفحه',
                store: clusters.get(key),
            });
        });
        return visible;
    }

    function place(items) {
        const root = items.find(function (item) {
            return !item.cluster && item.depth === 0;
        }) || items[0];
        const levels = new Map();
        items.forEach(function (item) {
            if (root && item.id === root.id) {
                item.tx = 0;
                item.ty = 0;
                return;
            }
            const depth = Math.max(1, item.depth || 1);
            if (!levels.has(depth)) {
                levels.set(depth, []);
            }
            levels.get(depth).push(item);
        });
        levels.forEach(function (list, depth) {
            list.sort(function (left, right) {
                return text(left.path).localeCompare(text(right.path));
            });
            const radius = 142 * depth;
            list.forEach(function (item, index) {
                const angle = (-Math.PI / 2) + ((index + 0.5) / list.length) * Math.PI * 2;
                item.tx = Math.cos(angle) * radius;
                item.ty = Math.sin(angle) * radius;
            });
        });
    }

    function storeOf(item) {
        return item.cluster ? item.store : item.page;
    }

    function sync(animateNew) {
        const items = buildItems();
        place(items);
        items.forEach(function (item) {
            const store = storeOf(item);
            store.tx = item.tx;
            store.ty = item.ty;
            if (store.x === undefined || reduce) {
                const parent = pageByUrl(item.cluster ? item.parentUrl : item.page.discoveredFrom);
                store.x = animateNew && parent && parent.x !== undefined ? parent.x : item.tx;
                store.y = animateNew && parent && parent.y !== undefined ? parent.y : item.ty;
                if (animateNew) {
                    store.born = performance.now();
                }
            }
            item.x = store.x;
            item.y = store.y;
            item.born = store.born || 0;
        });
        current = items;
    }

    function stepPositions() {
        current.forEach(function (item) {
            const store = storeOf(item);
            const dx = store.tx - store.x;
            const dy = store.ty - store.y;
            store.x += Math.abs(dx) < 0.4 ? dx : dx * 0.2;
            store.y += Math.abs(dy) < 0.4 ? dy : dy * 0.2;
            item.x = store.x;
            item.y = store.y;
            item.tx = store.tx;
            item.ty = store.ty;
        });
    }

    function linkPairs() {
        const byId = new Map(current.map(function (item) {
            return [item.id, item];
        }));
        const lines = [];
        const seen = new Set();
        function add(from, to) {
            if (!from || !to || from.id === to.id) {
                return;
            }
            const key = from.id + '>' + to.id;
            if (seen.has(key)) {
                return;
            }
            seen.add(key);
            lines.push([from, to]);
        }
        current.forEach(function (item) {
            if (item.cluster) {
                if (byId.has(item.parentUrl)) {
                    add(byId.get(item.parentUrl), item);
                }
                return;
            }
            const parent = pageByUrl(item.page.discoveredFrom);
            if (parent && byId.has(parent.url)) {
                add(byId.get(parent.url), item);
            }
            (item.page.internalLinks || []).forEach(function (link) {
                const target = pageByUrl(link);
                if (target && byId.has(target.url)) {
                    add(item, byId.get(target.url));
                }
            });
        });
        return lines;
    }

    function viewSize() {
        const rect = canvas.getBoundingClientRect();
        const ratio = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = Math.max(1, Math.floor(rect.width * ratio));
        canvas.height = Math.max(1, Math.floor(rect.height * ratio));
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        return rect;
    }

    function palette() {
        const style = getComputedStyle(document.documentElement);
        return {
            ink: style.getPropertyValue('--ink').trim() || '#1c1917',
            muted: style.getPropertyValue('--muted').trim() || '#78716c',
            line: style.getPropertyValue('--line').trim() || '#d6d3d1',
            surface: style.getPropertyValue('--surface').trim() || '#fff',
            accent: style.getPropertyValue('--accent').trim() || '#0f766e',
            warn: style.getPropertyValue('--warn').trim() || '#b45309',
            danger: style.getPropertyValue('--danger').trim() || '#b91c1c',
        };
    }

    function draw() {
        const rect = viewSize();
        const color = palette();
        stepPositions();
        ctx.clearRect(0, 0, rect.width, rect.height);
        ctx.save();
        ctx.translate(rect.width / 2, rect.height / 2);
        ctx.scale(cam.scale, cam.scale);
        ctx.translate(cam.x, cam.y);
        const now = performance.now();
        linkPairs().forEach(function (pair) {
            const born = Math.max(pair[0].born || 0, pair[1].born || 0);
            const age = born ? Math.min(1, (now - born) / 420) : 1;
            ctx.beginPath();
            ctx.moveTo(pair[0].x, pair[0].y);
            ctx.lineTo(pair[1].x, pair[1].y);
            ctx.strokeStyle = color.line;
            ctx.globalAlpha = 0.35 + (0.65 * age);
            ctx.lineWidth = 1.4;
            ctx.stroke();
            ctx.globalAlpha = 1;
        });
        current.forEach(function (item) {
            const page = item.page;
            const state = item.cluster ? 'discovered' : stateOf(page);
            const stroke = state === 'completed' ? color.accent : state === 'warning' ? color.warn : state === 'error' ? color.danger : color.muted;
            const radius = item.cluster ? 24 : (page && page.depth === 0 ? 28 : 18);
            const age = item.born ? Math.min(1, (now - item.born) / 420) : 1;
            const scale = reduce ? 1 : 0.7 + (0.3 * age);
            ctx.save();
            ctx.translate(item.x, item.y);
            ctx.scale(scale, scale);
            ctx.globalAlpha = reduce ? 1 : Math.max(0.2, age);
            if (item.id === selectedId || item.id === hoverId) {
                ctx.beginPath();
                ctx.arc(0, 0, radius + 8, 0, Math.PI * 2);
                ctx.strokeStyle = color.accent;
                ctx.globalAlpha = 0.4;
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.globalAlpha = reduce ? 1 : age;
            }
            if (item.born && now - item.born < 900) {
                ctx.beginPath();
                ctx.arc(0, 0, radius + 13, 0, Math.PI * 2);
                ctx.strokeStyle = stroke;
                ctx.globalAlpha = 0.45 * (1 - ((now - item.born) / 900));
                ctx.lineWidth = 1.5;
                ctx.stroke();
                ctx.globalAlpha = reduce ? 1 : age;
            }
            ctx.beginPath();
            ctx.arc(0, 0, radius, 0, Math.PI * 2);
            ctx.fillStyle = color.surface;
            ctx.fill();
            ctx.lineWidth = 2.5;
            ctx.strokeStyle = stroke;
            if (item.cluster) {
                ctx.setLineDash([3, 3]);
            }
            ctx.stroke();
            ctx.setLineDash([]);
            ctx.fillStyle = color.ink;
            ctx.font = '12px Vazirmatn, Tahoma, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const mark = item.cluster ? String(item.pages.length) : (page && page.depth === 0 ? 'خانه' : '');
            if (mark !== '') {
                ctx.fillText(mark, 0, 0);
            }
            const label = !item.cluster && page && page.depth === 0 ? '' : text(item.path);
            if (label !== '') {
                ctx.font = '11px Vazirmatn, Tahoma, sans-serif';
                ctx.fillStyle = color.muted;
                ctx.fillText(label.length > 28 ? label.slice(0, 26) + '…' : label, 0, radius + 16);
            }
            ctx.restore();
        });
        ctx.restore();
        if (empty) {
            empty.classList.toggle('is-hidden', pages.length > 0);
        }
    }

    function needsFrame() {
        if (dragging) {
            return true;
        }
        const now = performance.now();
        return current.some(function (item) {
            const store = storeOf(item);
            return Math.abs(store.tx - store.x) > 0.6 || Math.abs(store.ty - store.y) > 0.6 || (item.born && now - item.born < 920);
        });
    }

    function frame() {
        draw();
        if (needsFrame()) {
            requestAnimationFrame(frame);
            return;
        }
        running = false;
    }

    function poke(animateNew) {
        sync(animateNew);
        if (!running) {
            running = true;
            requestAnimationFrame(frame);
        }
    }

    function pointer(event) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top,
            width: rect.width,
            height: rect.height,
        };
    }

    function hit(event) {
        const point = pointer(event);
        let found = null;
        let best = 28;
        current.forEach(function (item) {
            const sx = (item.x + cam.x) * cam.scale + point.width / 2;
            const sy = (item.y + cam.y) * cam.scale + point.height / 2;
            const distance = Math.hypot(sx - point.x, sy - point.y);
            if (distance < best) {
                best = distance;
                found = item;
            }
        });
        return found;
    }

    function counts(list) {
        const tally = { critical: 0, high: 0, medium: 0, low: 0, info: 0 };
        list.forEach(function (finding) {
            if (Object.prototype.hasOwnProperty.call(tally, finding.severity)) {
                tally[finding.severity] += 1;
            }
        });
        return tally;
    }

    function row(parent, label, value) {
        const line = document.createElement('p');
        const name = document.createElement('span');
        name.textContent = label;
        const data = document.createElement('strong');
        data.textContent = value;
        line.appendChild(name);
        line.appendChild(data);
        parent.appendChild(line);
    }

    function openPage(page) {
        selectedId = page.url;
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        drawerBody.replaceChildren();
        const heading = document.createElement('h3');
        heading.textContent = page.depth === 0 ? 'خانه' : page.path;
        drawerBody.appendChild(heading);
        row(drawerBody, 'نشانی', page.url);
        row(drawerBody, 'عنوان', page.title || '—');
        row(drawerBody, 'وضعیت HTTP', Number.isFinite(page.statusCode) ? String(page.statusCode) : '—');
        row(drawerBody, 'عمق', String(page.depth || 0));
        row(drawerBody, 'لینک‌های داخلی', String(page.internalLinkCount || 0));

        const seo = document.createElement('div');
        const seoTitle = document.createElement('h4');
        seoTitle.textContent = 'سئو';
        seo.appendChild(seoTitle);
        row(seo, 'عنوان صفحه', page.title ? 'دارد' : 'ندارد');
        row(seo, 'توضیح متا', page.hasMetaDescription ? 'دارد' : 'ندارد');
        row(seo, 'تگ H1', page.hasH1 ? 'دارد' : 'ندارد');

        const speed = document.createElement('div');
        const speedTitle = document.createElement('h4');
        speedTitle.textContent = 'سرعت';
        speed.appendChild(speedTitle);
        const speedText = document.createElement('p');
        speedText.className = 'note';
        speedText.textContent = page.depth === 0 && responseMs !== null
            ? 'زمان پاسخ صفحه اصلی: ' + responseMs + ' میلی‌ثانیه'
            : 'زمان پاسخ این صفحه جداگانه اندازه‌گیری نشده است.';
        speed.appendChild(speedText);

        const issues = document.createElement('div');
        issues.id = 'drawer-findings';
        const issueTitle = document.createElement('h4');
        issueTitle.textContent = 'یافته‌ها';
        issues.appendChild(issueTitle);
        const tally = counts(page.findings);
        const labels = { critical: 'بحرانی', high: 'بالا', medium: 'متوسط', low: 'کم', info: 'اطلاعات' };
        const present = Object.keys(tally).filter(function (key) {
            return tally[key] > 0;
        });
        if (present.length === 0) {
            const none = document.createElement('p');
            none.textContent = 'یافته‌ای برای این صفحه ثبت نشده است.';
            issues.appendChild(none);
        } else {
            present.forEach(function (key) {
                const item = document.createElement('p');
                item.textContent = tally[key] + ' ' + labels[key];
                issues.appendChild(item);
            });
        }
        const ordered = page.findings.slice().sort(function (left, right) {
            return rank(left.severity) - rank(right.severity);
        });
        if (ordered[0]) {
            row(issues, 'مهم‌ترین مورد', ordered[0].title);
        }
        ordered.forEach(function (finding) {
            const card = document.createElement('article');
            const title = document.createElement('strong');
            title.textContent = finding.title;
            const advice = document.createElement('span');
            advice.textContent = finding.recommendation || '';
            card.appendChild(title);
            if (finding.recommendation) {
                card.appendChild(advice);
            }
            issues.appendChild(card);
        });

        drawerBody.appendChild(seo);
        drawerBody.appendChild(speed);
        drawerBody.appendChild(issues);
        const jump = document.createElement('button');
        jump.type = 'button';
        jump.textContent = 'مشاهده تحلیل صفحه';
        jump.addEventListener('click', function () {
            issues.scrollIntoView({ block: 'start' });
        });
        drawerBody.appendChild(jump);
        poke(false);
    }

    function openCluster(item) {
        selectedId = item.id;
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        drawerBody.replaceChildren();
        const heading = document.createElement('h3');
        heading.textContent = item.pages.length + ' صفحه در یک گروه';
        drawerBody.appendChild(heading);
        const note = document.createElement('p');
        note.className = 'note';
        note.textContent = 'این صفحه‌ها بررسی شده‌اند و برای خوانایی نمودار در یک گروه نمایش داده می‌شوند.';
        drawerBody.appendChild(note);
        item.pages.forEach(function (page) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = page.path;
            button.addEventListener('click', function () {
                openPage(page);
            });
            drawerBody.appendChild(button);
        });
        poke(false);
    }

    function updateSummary() {
        if (!summary) {
            return;
        }
        if (!finished) {
            summary.classList.add('is-hidden');
            return;
        }
        const attention = pages.filter(function (page) {
            const state = stateOf(page);
            return state === 'warning' || state === 'error';
        }).length;
        const healthy = pages.filter(function (page) {
            return stateOf(page) === 'completed';
        }).length;
        const high = findings.filter(function (finding) {
            return finding.severity === 'critical' || finding.severity === 'high';
        }).length;
        summary.replaceChildren();
        [pages.length + ' صفحه بررسی شد', attention + ' صفحه نیاز به توجه دارد', high + ' مسئله با اولویت بالا', healthy + ' صفحه سالم'].forEach(function (label) {
            const item = document.createElement('span');
            item.textContent = label;
            summary.appendChild(item);
        });
        summary.classList.remove('is-hidden');
    }

    function remember(payload, animate) {
        if (!payload || !payload.url || pageByUrl(payload.url)) {
            return;
        }
        pages.push({
            url: payload.url,
            finalUrl: payload.final_url || payload.url,
            path: payload.path || pathOf(payload.url),
            statusCode: Number(payload.status_code || 0),
            title: payload.title || '',
            depth: Number(payload.depth || 0),
            discoveredFrom: payload.discovered_from || null,
            internalLinkCount: Number(payload.internal_link_count || (payload.internal_links || []).length || 0),
            internalLinks: Array.isArray(payload.internal_links) ? payload.internal_links : [],
            hasMetaDescription: Boolean(payload.has_meta_description),
            hasH1: Boolean(payload.has_h1),
            findings: [],
        });
        findings.forEach(attach);
        poke(animate);
        if (finished) {
            updateSummary();
        }
    }

    function attach(finding) {
        const page = pageByUrl(finding.url);
        if (!page) {
            return;
        }
        const key = (finding.rule_key || finding.title) + ':' + (finding.url || '');
        if (page.findings.some(function (item) {
            return item.key === key;
        })) {
            return;
        }
        page.findings.push({
            key: key,
            severity: finding.severity,
            title: finding.title,
            recommendation: finding.recommendation || '',
            category: finding.category || '',
        });
        if (selectedId === page.url && drawer.classList.contains('is-open')) {
            openPage(page);
            return;
        }
        poke(false);
    }

    function fit() {
        sync(false);
        if (current.length === 0) {
            cam = { x: 0, y: 0, scale: 1 };
            fitted = Object.assign({}, cam);
            poke(false);
            return;
        }
        let minX = Infinity;
        let minY = Infinity;
        let maxX = -Infinity;
        let maxY = -Infinity;
        current.forEach(function (item) {
            minX = Math.min(minX, item.tx);
            minY = Math.min(minY, item.ty);
            maxX = Math.max(maxX, item.tx);
            maxY = Math.max(maxY, item.ty);
        });
        const rect = canvas.getBoundingClientRect();
        const spanX = Math.max(120, maxX - minX + 180);
        const spanY = Math.max(120, maxY - minY + 160);
        cam = {
            x: -((minX + maxX) / 2),
            y: -((minY + maxY) / 2),
            scale: Math.max(0.45, Math.min(1.35, Math.min((rect.width - 40) / spanX, (rect.height - 40) / spanY))),
        };
        fitted = { x: cam.x, y: cam.y, scale: cam.scale };
        poke(false);
    }

    canvas.addEventListener('pointerdown', function (event) {
        dragging = true;
        moved = false;
        lastPointer = pointer(event);
        canvas.setPointerCapture(event.pointerId);
    });
    canvas.addEventListener('pointermove', function (event) {
        if (dragging && lastPointer) {
            const point = pointer(event);
            if (Math.hypot(point.x - lastPointer.x, point.y - lastPointer.y) > 3) {
                moved = true;
            }
            cam.x += (point.x - lastPointer.x) / cam.scale;
            cam.y += (point.y - lastPointer.y) / cam.scale;
            lastPointer = point;
            poke(false);
            return;
        }
        const item = hit(event);
        const next = item ? item.id : null;
        if (next !== hoverId) {
            hoverId = next;
            canvas.style.cursor = item ? 'pointer' : 'grab';
            poke(false);
        }
    });
    canvas.addEventListener('pointerup', function (event) {
        dragging = false;
        if (!moved) {
            const item = hit(event);
            if (item && item.cluster) {
                openCluster(item);
            } else if (item && item.page) {
                openPage(item.page);
            }
        }
        lastPointer = null;
    });
    canvas.addEventListener('pointerleave', function () {
        hoverId = null;
        poke(false);
    });
    canvas.addEventListener('wheel', function (event) {
        event.preventDefault();
        cam.scale = Math.max(0.4, Math.min(2.2, cam.scale * (event.deltaY > 0 ? 0.92 : 1.08)));
        poke(false);
    }, { passive: false });

    document.getElementById('graph-fit')?.addEventListener('click', fit);
    document.getElementById('graph-reset')?.addEventListener('click', function () {
        cam = { x: fitted.x, y: fitted.y, scale: fitted.scale };
        poke(false);
    });
    document.getElementById('drawer-close')?.addEventListener('click', function () {
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        selectedId = null;
        poke(false);
    });
    window.addEventListener('resize', function () {
        poke(false);
    });

    window.InsightGraph = {
        mount: function (snapshot) {
            responseMs = snapshot.response_time_ms;
            finished = snapshot.status === 'completed';
            (snapshot.pages || []).forEach(function (page) {
                remember(page, false);
            });
            (snapshot.findings || []).forEach(function (finding) {
                findings.push(finding);
                attach(finding);
            });
            updateSummary();
            fit();
        },
        addPage: function (payload) {
            remember(payload, true);
        },
        addFinding: function (finding) {
            const key = (finding.rule_key || finding.title) + ':' + (finding.url || '');
            if (findings.some(function (item) {
                return ((item.rule_key || item.title) + ':' + (item.url || '')) === key;
            })) {
                return;
            }
            findings.push(finding);
            attach(finding);
            if (finished) {
                updateSummary();
            }
        },
        setResponseTime: function (value) {
            responseMs = Math.round(Number(value));
            const page = pageByUrl(selectedId);
            if (page && drawer.classList.contains('is-open')) {
                openPage(page);
            }
        },
        complete: function () {
            finished = true;
            updateSummary();
            poke(false);
        },
    };
})();
