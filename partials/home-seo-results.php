<section id="seo" class="seo-showcase py-20 bg-slate-950 text-white overflow-hidden relative">
        <div class="absolute inset-0 opacity-30"
            style="background:radial-gradient(circle at 18% 18%,#10b981,transparent 26%),radial-gradient(circle at 86% 76%,#38bdf8,transparent 28%),linear-gradient(135deg,#020617,#0f172a)">
        </div>
        <div class="max-w-6xl mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-5">
                <span class="text-emerald-300 font-extrabold">نمونه کارهای SEO</span>
            </div>

            <div class="seo-shot-frame rounded-[2rem] p-4 md:p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <span class="seo-shot-dot"></span><span class="seo-shot-dot"></span><span
                            class="seo-shot-dot"></span>
                        <strong class="mr-2 text-white">گزارش نمونه رتبه کلمات کلیدی</strong>
                    </div>
                    <span class="text-xs md:text-sm text-white/60">خروجی ها قابل مشاهده در گوگل است.</span>
                </div>

                <div class="seo-report-grid grid lg:grid-cols-3 gap-5">
                    <?php foreach (array_slice($seoItems, 0, 3) as $i => $item): ?>
                    <?php
                    $defaultKeywords = [['طراحی سایت فروشگاهی', 'ساخت فروشگاه اینترنتی', 'طراحی فروشگاه آنلاین'], ['طراحی سایت شرکتی', 'طراحی سایت خدماتی', 'شرکت طراحی سایت'], ['سئو سایت وردپرس', 'خدمات سئو سایت', 'بهینه سازی سایت']];
                    $beforeRanks = [['+۵۰', '۳۸', '۴۶'], ['۴۱', '+۵۰', '۳۳'], ['۳۷', '۴۴', '+۵۰']];
                    $afterRanks = [['۳', '۵', '۸'], ['۲', '۶', '۹'], ['۴', '۷', '۱۰']];
                    $projectLabels = ['پروژه فروشگاهی', 'پروژه شرکتی', 'پروژه خدماتی'];
                    $resultLabels = ['۳ کلمه در صفحه اول', '۲ کلمه Top 5', '۳ کلمه قابل گزارش'];
                    
                    $rawKeywords = trim((string) ($item['keyword'] ?? ''));
                    $keywords = preg_split('/[،,|]+/u', $rawKeywords, -1, PREG_SPLIT_NO_EMPTY);
                    $keywords = array_values(array_map('trim', $keywords ?: []));
                    if (count($keywords) < 3) {
                        $keywords = $defaultKeywords[$i] ?? $defaultKeywords[0];
                    }
                    $keywords = array_slice($keywords, 0, 3);
                    ?>
                    <article
                        class="seo-report-card rounded-[1.75rem] border border-white/20 p-5 md:p-6 text-slate-900">
                        <div class="seo-report-topbar">
                            <span class="seo-report-label"><?= svg_icon('chart', 'w-4 h-4') ?>
                                <?= e($projectLabels[$i] ?? 'پروژه SEO') ?></span>
                            <span class="seo-report-date">نمونه رتبه</span>
                        </div>

                        <h3 class="font-black text-xl leading-8 mt-5 mb-2"><?= e($item['rank'] ?? 'رشد رتبه کلمات') ?>
                        </h3>
                        <p class="text-sm text-slate-600 leading-8 mb-4"><?= e($item['summary'] ?? '') ?></p>

                        <div class="seo-keyword-list" aria-label="جدول کلمات کلیدی بالا آمده">
                            <div class="seo-rank-row is-head">
                                <span>کلمه کلیدی</span>
                                <span>قبل</span>
                                <span>الان</span>
                            </div>
                            <?php    foreach ($keywords as $kIndex => $keyword): ?>
                            <div class="seo-rank-row">
                                <span class="seo-keyword-text"><?= e($keyword) ?></span>
                                <span class="seo-rank-before"><?= e($beforeRanks[$i][$kIndex] ?? '+۵۰') ?></span>
                                <span
                                    class="seo-rank-after <?= ($afterRanks[$i][$kIndex] ?? '') === '۱' ? 'top-one' : '' ?>"><?= e($afterRanks[$i][$kIndex] ?? '۱۰') ?></span>
                            </div>
                            <?php    endforeach; ?>
                        </div>


                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <p class="text-center text-white/70 text-sm mt-8 max-w-3xl mx-auto leading-8">نتایج هر پروژه به شرایط، رقابت و زمان اجرای استراتژی وابسته است.</p>
        </div>
</section>

    