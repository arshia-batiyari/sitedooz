<?php $settings = $settings ?? get_settings(); ?>
  <footer class="site-footer">
    <div class="site-footer-inner">
      <div class="site-footer-brand">
        <a href="<?= site_url() ?>" class="site-footer-logo" aria-label="<?= e(APP_NAME) ?>">
          <img src="<?= asset_url('images/logo-optimized.webp') ?>" width="120" height="48" alt="لوگوی سایت دوز">
        </a>
        <p><?= e($settings['footer_description'] ?? 'سایت دوز وب‌سایت، سئو و بهینه‌سازی را به‌صورت یک مسیر رشد جلو می‌برد.') ?></p>
        <a class="nav-audit" href="<?= site_url('#audit') ?>">تحلیل رایگان سایت</a>
      </div>
      <nav aria-label="خدمات">
        <h2>خدمات</h2>
        <a href="<?= site_url('tarahi-site-sherkati-gorgan') ?>">طراحی سایت شرکتی</a>
        <a href="<?= site_url('tarahi-site-foroushgahi-gorgan') ?>">فروشگاه اینترنتی</a>
        <a href="<?= site_url('seo-sherkati-gorgan') ?>">سئو سایت شرکتی</a>
        <a href="<?= site_url('seo-foroushgahi-gorgan') ?>">سئو سایت فروشگاهی</a>
      </nav>
      <nav aria-label="صفحات سایت">
        <h2>سایت دوز</h2>
        <a href="<?= site_url('nemoone-kar-tarahi-site-gorgan') ?>">نمونه‌کارها</a>
        <a href="<?= site_url('blog') ?>">وبلاگ</a>
        <a href="<?= site_url('about-us') ?>">درباره ما</a>
        <a href="<?= site_url('sozalat-motadavel-tarahi-site') ?>">سوالات متداول</a>
        <a href="<?= site_url('hazine-tarahi-site-gorgan') ?>">هزینه طراحی سایت</a>
        <a href="<?= site_url('hazine-seo-site-gorgan') ?>">هزینه سئو</a>
      </nav>
      <div>
        <h2>تماس</h2>
        <?php if (!empty($settings['phone'])): ?>
        <a href="tel:<?= e(preg_replace('/\D+/', '', $settings['phone'])) ?>" class="site-footer-phone"><?= e($settings['phone']) ?></a>
        <?php endif; ?>
        <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>">صفحه تماس</a>
        <a href="<?= site_url('moshavere-tarahi-site-gorgan') ?>#consultation-form" data-track="consultation_requested">درخواست بررسی برای رفع مشکلات</a>
      </div>
    </div>
    <div class="site-footer-bar">© <?= e(APP_NAME) ?></div>
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
