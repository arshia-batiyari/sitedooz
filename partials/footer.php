<?php $settings = $settings ?? get_settings(); ?>
  <footer class="relative overflow-hidden bg-slate-950 text-white py-16 text-center">
    <div class="absolute inset-0 opacity-30" style="background:radial-gradient(circle at 80% 20%,#10b981,transparent 30%),radial-gradient(circle at 20% 80%,#38bdf8,transparent 28%)"></div>
    <div class="relative z-10">
    <h2 class="text-3xl font-bold mb-6"><?= e($settings['footer_title'] ?? 'آماده‌ای سایتت رو حرفه‌ای داشته باشی؟') ?></h2>
    <p class="text-white/80 mb-10 max-w-xl mx-auto px-4"><?= e($settings['footer_description'] ?? 'همین حالا تماس بگیر و مشاوره رایگان دریافت کن.') ?></p>
    <a href="<?= site_url('#audit') ?>" class="call-btn inline-flex items-center gap-3 bg-emerald-500 hover:bg-emerald-600 transition px-8 py-4 rounded-xl text-lg font-bold shadow-lg hover:scale-105 active:scale-95">
      تحلیل رایگان سایت
    </a>
    <p class="mt-4"><a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>#consultation-form" class="text-emerald-300 font-bold hover:underline" data-track="consultation_requested">درخواست بررسی برای رفع مشکلات</a></p>
    <?php if (!empty($settings['phone'])): ?>
    <p class="mt-6 text-white/70 text-sm">یا مستقیم تماس بگیرید:
        <a href="tel:<?= e(preg_replace('/\D+/', '', $settings['phone'])) ?>" class="text-emerald-300 font-black hover:underline" style="direction:ltr; display:inline-block;"><?= e($settings['phone']) ?></a>
    </p>
    <?php endif; ?>
    <nav class="mt-10 flex flex-wrap justify-center gap-x-6 gap-y-3 text-sm text-white/65" aria-label="لینک‌های مهم">
      <a class="hover:text-emerald-300" href="<?= site_url('nemoone-kar-tarahi-site-gorgan') ?>">نمونه کارها</a>
      <a class="hover:text-emerald-300" href="<?= site_url('sozalat-motadavel-tarahi-site') ?>">سوالات متداول</a>
      <a class="hover:text-emerald-300" href="<?= site_url('hazine-seo-site-gorgan') ?>">هزینه سئو</a>
      <a class="hover:text-emerald-300" href="<?= site_url('hazine-tarahi-site-gorgan') ?>">هزینه طراحی سایت</a>
    </nav>
    <div class="mt-8 text-sm text-white/50">© <?= e(APP_NAME) ?></div>
    </div>
  </footer>

  <script>
	(function () {
		var bar  = document.getElementById('scrollProgressBar');
		var fill = document.getElementById('scrollProgressFill');
		if (!bar || !fill) return;
		var ticking = false;
		function updateProgress() {
			var scrollTop  = window.scrollY || document.documentElement.scrollTop || 0;
			var docHeight  = document.documentElement.scrollHeight - document.documentElement.clientHeight;
			var progress   = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
			progress = Math.min(100, Math.max(0, progress));
			fill.style.height = progress + '%';
			bar.setAttribute('aria-valuenow', String(Math.round(progress)));
			bar.classList.toggle('is-active', scrollTop > 400);
			ticking = false;
		}
		function onScroll() {
			if (!ticking) {
				window.requestAnimationFrame(updateProgress);
				ticking = true;
			}
		}
		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', onScroll);
		updateProgress();
	})();

	function toggleSeoContent(expand) {
		var wrapper = document.getElementById('seo-text-wrapper');
		var fade    = document.getElementById('seo-fade');
		var btnExp  = document.getElementById('seo-expand-btn');
		var btnCol  = document.getElementById('seo-collapse-btn');

		if (expand) {
			wrapper.style.maxHeight = wrapper.scrollHeight + 'px';
			fade.style.opacity = '0';
			btnExp.style.display = 'none';
			btnCol.style.display = 'inline-flex';
		} else {
			wrapper.style.maxHeight = '320px';
			fade.style.opacity = '1';
			btnExp.style.display = 'inline-flex';
			btnCol.style.display = 'none';
			// اسکرول به بالای سکشن
			setTimeout(function(){
				document.getElementById('seo-content').scrollIntoView({behavior:'smooth', block:'start'});
			}, 100);
		}
	}
    const mobileMenu = document.getElementById("mobileMenu");

	const toggleMenu = () => {
	const isHidden = mobileMenu.classList.contains("opacity-0");
	
	if (isHidden) {
		mobileMenu.classList.remove("opacity-0", "pointer-events-none", "-translate-y-5");
		mobileMenu.classList.add("opacity-100", "translate-y-0");
		menuBtn.setAttribute("aria-expanded", "true");
	} else {
		mobileMenu.classList.add("opacity-0", "pointer-events-none", "-translate-y-5");
		mobileMenu.classList.remove("opacity-100", "translate-y-0");
		menuBtn.setAttribute("aria-expanded", "false");
	}
	};

	if (menuBtn) {
		menuBtn.onclick = toggleMenu;
	}
	document.querySelectorAll("#mobileMenu a").forEach(link => {
		link.addEventListener("click", () => {
			mobileMenu.classList.add("opacity-0", "pointer-events-none", "-translate-y-5");
			mobileMenu.classList.remove("opacity-100", "translate-y-0");
		});
	});
    // Count-up animation for stat numbers (fa digits, runs once, respects reduced motion)
    (function(){
      const faDigits = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
      const toFa = n => String(n).replace(/[0-9]/g, d => faDigits[d]);
      const counters = document.querySelectorAll('[data-count-to]');
      if (!counters.length) return;
      const animateCounter = (el) => {
        const target = parseInt(el.dataset.countTo, 10) || 0;
        const suffix = el.dataset.countSuffix || '';
        const prefersReduced = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        if (prefersReduced) { el.textContent = toFa(target) + suffix; return; }
        const duration = 900;
        const start = performance.now();
        const step = (now) => {
          const progress = Math.min((now - start) / duration, 1);
          const eased = 1 - Math.pow(1 - progress, 3);
          el.textContent = toFa(Math.round(eased * target)) + suffix;
          if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
      };
      const countObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            countObserver.unobserve(entry.target);
          }
        });
      }, { threshold: .5 });
      counters.forEach(el => countObserver.observe(el));
    })();

    // Subtle mouse-tilt parallax on hero product mockup (desktop, fine pointer only)
    (function(){
      const tiltEl = document.querySelector('[data-tilt]');
      if (!tiltEl) return;
      const prefersReduced = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      const finePointer = window.matchMedia && window.matchMedia("(hover: hover) and (pointer: fine)").matches;
      if (prefersReduced || !finePointer) return;
      const wrap = tiltEl.closest('.relative') || tiltEl.parentElement;
      wrap.addEventListener('mousemove', (e) => {
        const rect = tiltEl.getBoundingClientRect();
        const px = (e.clientX - rect.left) / rect.width - .5;
        const py = (e.clientY - rect.top) / rect.height - .5;
        tiltEl.style.transform = `perspective(1000px) rotateY(${px * 6}deg) rotateX(${py * -6}deg)`;
      });
      wrap.addEventListener('mouseleave', () => { tiltEl.style.transform = 'perspective(1000px) rotateY(0) rotateX(0)'; });
    })();

    document.querySelectorAll(".faq-item").forEach(item => {
      item.addEventListener("click", () => {
        const ans = item.querySelector(".faq-answer");
        if (item.classList.contains("active")) {
          item.classList.remove("active");
          ans.style.maxHeight = null;
        } else {
          item.classList.add("active");
          ans.style.maxHeight = ans.scrollHeight + "px";
        }
      });
    });

<?php if (empty($disableSectionReveal)): ?>
    // Section reveal animations: modern, light and performance-friendly
    const reduceMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const sections = document.querySelectorAll("main > section, footer");
    sections.forEach((section, index) => {
      section.classList.add("reveal-section");
      section.dataset.reveal = index % 3 === 0 ? "scale" : (index % 3 === 1 ? "right" : "left");

      const staggerTargets = section.querySelectorAll(".grid, .brand-track, .space-y-4");
      staggerTargets.forEach(group => {
        if (group.children.length > 1) {
          group.classList.add("stagger-ready");
          Array.from(group.children).forEach((child, childIndex) => {
            child.style.setProperty("--stagger-index", Math.min(childIndex, 8));
          });
        }
      });
    });

    if (reduceMotion) {
      sections.forEach(section => section.classList.add("is-visible"));
      document.querySelectorAll(".stagger-ready").forEach(group => group.classList.add("is-visible"));
    } else {
      const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            entry.target.querySelectorAll(".stagger-ready").forEach(group => group.classList.add("is-visible"));
            revealObserver.unobserve(entry.target);
          }
        });
      }, { threshold: .1, rootMargin: "0px 0px -4% 0px" });

      // Anything already in (or near) the viewport at load time shows instantly —
      // no blur/fade delay. Only content below the fold gets the on-scroll reveal.
      const viewportH = window.innerHeight || document.documentElement.clientHeight;
      sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top < viewportH * 0.92) {
          section.classList.add("is-visible", "reveal-instant");
          section.querySelectorAll(".stagger-ready").forEach(group => group.classList.add("is-visible"));
        } else {
          revealObserver.observe(section);
        }
      });
    }
<?php endif; ?>
  </script>
</body>
</html>
