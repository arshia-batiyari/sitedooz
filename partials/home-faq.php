<section id="faq" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-emerald-600 font-black">سوالات پرتکرار</span>
                <h2 class="text-3xl md:text-4xl font-black mt-2">سوالات متداول درباره طراحی سایت در گرگان و سئو سایت در
                    گرگان</h2>
            </div>
            <div class="space-y-4">
                <?php
$faqs = [
    ['چقدر طول می‌کشد طراحی سایت در گرگان آماده شود؟', 'زمان طراحی سایت در گرگان به پیچیدگی پروژه بستگی دارد. یک سایت شرکتی ساده معمولاً ۷ تا ۱۴ روز کاری. یک فروشگاه اینترنتی کامل ۲۱ تا ۳۰ روز. برای زمان دقیق پروژه خودتان، جزئیات را با ما در میان بگذارید.'],
    ['نتایج سئو سایت در گرگان چه موقع دیده می‌شود؟', 'سئو سایت در گرگان در بازار محلی گرگان — که رقابتش از تهران کمتر است — معمولاً در ۳ تا ۶ ماه نتایج اولیه می‌دهد. برخی کلمات کم‌رقابت‌تر ممکن است در همان ماه اول یا دوم به صفحه اول بروند.'],
    ['آیا بعد از طراحی سایت در گرگان می‌توانم محتوا را خودم تغییر دهم؟', 'بله. سایت دوز در طراحی سایت در گرگان پنل مدیریت ساده تحویل می‌دهد که بدون دانش فنی می‌توانید محتوا، تصاویر و متن‌ها را تغییر دهید.'],
    ['آیا سئو سایت در گرگان باید ماهانه ادامه داشته باشد؟', 'سئو سایت در گرگان مداوم نتایج بهتری می‌دهد. اما اگر بودجه محدود دارید، با یک پروژه پایه هم می‌توانید شروع کنید. در مشاوره رایگان توضیح می‌دهیم کدام مدل برای شما مناسب‌تر است.'],
    ['تحلیل رایگان سایت چه چیزی را نشان می‌دهد؟', 'تحلیل اولیه همان وب‌سایت را برای سرعت، سئو و مشکلات فنی قابل‌مشاهده بررسی می‌کند. این گزارش نتیجه همان بررسی است و جایگزین یک استراتژی کامل سئو نیست.'],
    ['آیا سایت دوز فقط در گرگان کار می‌کند؟', 'سایت دوز در طراحی سایت در گرگان و سئو سایت در گرگان تخصص دارد اما به صورت آنلاین به کسب‌وکارهای سراسر کشور هم خدمت ارائه می‌دهد.'],
];
foreach ($faqs as $faq): ?>
                <div class="faq-item soft-card rounded-2xl p-5 cursor-pointer">
                    <div class="flex justify-between items-center gap-4">
                        <h3 class="font-black text-lg"><?= e($faq[0]) ?></h3>
                        <?= svg_icon('chevron', 'w-5 h-5 faq-icon transition-transform duration-300 text-emerald-600') ?>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 leading-8 pt-4"><?= e($faq[1]) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script type="application/ld+json"><?= json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(static fn($faq) => [
                '@type' => 'Question',
                'name' => $faq[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq[1]],
            ], $faqs),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    </section>
