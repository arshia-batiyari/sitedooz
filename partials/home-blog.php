<section id="blog-preview" class="py-20 bg-slate-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">
                <div>
                    <span class="text-emerald-600 font-black">وبلاگ سایت دوز</span>
                </div>
                <a href="<?= site_url('blog') ?>"
                    class="bg-slate-900 text-white px-5 py-3 rounded-2xl font-black hover:bg-emerald-600 transition inline-flex items-center gap-2">مشاهده
                    همه مقالات <?= svg_icon('arrow', 'w-5 h-5') ?></a>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach ($posts as $i => $post): ?>
                <article class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200/70 card">
                    <a href="<?= post_url($post) ?>">
                        <img src="<?= asset_url($post['cover'] ?? 'images/blog-writing-visual.svg') ?>"
                            class="h-52 w-full object-cover transition duration-500 hover:scale-105"
                            alt="<?= e($post['title'] ?? '') ?>" loading="lazy" />
                    </a>
                    <div class="p-6">
                        <div class="text-xs text-emerald-600 font-black mb-3"><?= e($post['category'] ?? 'بلاگ') ?> ·
                            <?= format_date($post['published_at'] ?? '') ?></div>
                        <h3 class="font-black text-lg mb-3 leading-8"><a href="<?= post_url($post) ?>"
                                class="hover:text-emerald-600 transition"><?= e($post['title'] ?? '') ?></a></h3>
                        <p class="text-sm text-gray-600 leading-8 mb-5">
                            <?= e($post['excerpt'] ?: excerpt($post['content'] ?? '', 120)) ?></p>
                        <a href="<?= post_url($post) ?>"
                            class="inline-flex items-center gap-2 text-slate-900 font-black hover:text-emerald-600">ادامه
                            مطلب <?= svg_icon('arrow', 'w-4 h-4') ?></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

