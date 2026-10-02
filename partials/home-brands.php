<section id="brands" class="py-16 bg-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-4">
            <p class="text-center text-slate-400 font-bold text-sm mb-6">کسب‌وکارهایی که به سایت دوز اعتماد کرده‌اند</p>
            <div class="brand-slider rounded-3xl bg-slate-50 border border-slate-100 py-6 overflow-hidden"
                aria-label="اسلایدر برندهای همکار">
                <div class="brand-track">
                    <?php $brandList = ['alijenab.png' => 'علی‌جناب','inyab.png' => 'بانک اطلاعاتی صنایع کشور', 'avan.png' => 'آوان', 'inyab.webp' => 'اینیاب', 'elinor.ico' => 'الینور', 'shadila.webp' => 'شادیلا', 'fiore.webp' => 'فیوره', 'ownze.webp' => 'اونزی']; ?>
                    <?php for ($rep = 0; $rep < 3; $rep++): ?>
                    <?php foreach ($brandList as $brand => $brandName): ?>
                    <div class="brand-slide">
                        <img src="<?= asset_url('images/' . $brand) ?>" alt="نمونه کار سایت دوز برای برند <?= e($brandName) ?>" loading="lazy">
                    </div>
                    <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>

    