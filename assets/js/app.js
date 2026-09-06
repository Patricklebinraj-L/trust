/**
 * Om Shanthi Trust — Frontend application
 */
(function () {
  'use strict';

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ---------- Theme ----------
  const html = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const stored = localStorage.getItem('ost-theme');
  if (stored === 'dark' || stored === 'light') {
    html.setAttribute('data-theme', stored);
  } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    html.setAttribute('data-theme', 'dark');
  }
  updateThemeIcon();

  function updateThemeIcon() {
    if (!themeIcon) return;
    const dark = html.getAttribute('data-theme') === 'dark';
    themeIcon.className = dark ? 'bi bi-sun' : 'bi bi-moon-stars';
  }

  themeToggle?.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('ost-theme', next);
    updateThemeIcon();
  });

  // ---------- Header scroll ----------
  const header = document.getElementById('siteHeader');
  const onScroll = () => {
    if (header) header.classList.toggle('scrolled', window.scrollY > 30);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // ---------- Scroll accent balls ----------
  const ballLeft = document.createElement('div');
  ballLeft.className = 'scroll-ball scroll-ball-left';
  ballLeft.setAttribute('aria-hidden', 'true');
  document.body.appendChild(ballLeft);

  const ballRight = document.createElement('div');
  ballRight.className = 'scroll-ball scroll-ball-right';
  ballRight.setAttribute('aria-hidden', 'true');
  document.body.appendChild(ballRight);

  const ballSections = document.querySelectorAll('.section, .hero-v2');
  let ballVisible = false;
  const ballObserver = new IntersectionObserver((entries) => {
    const anyVisible = entries.some((entry) => entry.isIntersecting);
    if (anyVisible !== ballVisible) {
      ballVisible = anyVisible;
      ballLeft.classList.toggle('is-visible', ballVisible);
      ballRight.classList.toggle('is-visible', ballVisible);
    }
  }, { threshold: 0.1, rootMargin: '0px 0px -5% 0px' });

  ballSections.forEach((sec) => ballObserver.observe(sec));

  // ---------- Mobile drawer menu ----------
  const drawer = document.getElementById('mobileDrawer');
  // Ensure drawer starts closed (prevents leaked open state)
  if (drawer) { drawer.classList.remove('is-open'); drawer.setAttribute('aria-hidden','true'); }
  const _bd = document.getElementById('mobileBackdrop');
  if (_bd) { _bd.classList.remove('is-open'); _bd.hidden = true; }
  document.body.classList.remove('menu-open');

  const backdrop = document.getElementById('mobileBackdrop');
  const openBtn = document.getElementById('mobileMenuBtn');
  const closeBtn = document.getElementById('mobileMenuClose');
  const menuIcon = document.getElementById('mobileMenuIcon');

  function openMenu() {
    if (!drawer || !backdrop) return;
    backdrop.hidden = false;
    // force reflow so transition runs
    void backdrop.offsetWidth;
    drawer.classList.add('is-open');
    backdrop.classList.add('is-open');
    document.body.classList.add('menu-open');
    openBtn?.setAttribute('aria-expanded', 'true');
    drawer.setAttribute('aria-hidden', 'false');
    if (menuIcon) menuIcon.className = 'bi bi-x-lg';
  }

  function closeMenu() {
    if (!drawer || !backdrop) return;
    drawer.classList.remove('is-open');
    backdrop.classList.remove('is-open');
    document.body.classList.remove('menu-open');
    openBtn?.setAttribute('aria-expanded', 'false');
    drawer.setAttribute('aria-hidden', 'true');
    if (menuIcon) menuIcon.className = 'bi bi-list';
    // hide backdrop after transition
    setTimeout(() => {
      if (!drawer.classList.contains('is-open')) {
        backdrop.hidden = true;
      }
    }, 300);
  }

  function toggleMenu(e) {
    e.preventDefault();
    e.stopPropagation();
    if (drawer?.classList.contains('is-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  }

  openBtn?.addEventListener('click', toggleMenu);
  closeBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    closeMenu();
  });
  backdrop?.addEventListener('click', closeMenu);

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && drawer?.classList.contains('is-open')) {
      closeMenu();
    }
  });

  // Links navigate normally; close menu first for smoother UX
  drawer?.querySelectorAll('a[href]').forEach((link) => {
    link.addEventListener('click', () => {
      // Allow navigation; just close drawer
      closeMenu();
    });
  });

  // ---------- AOS ----------
  if (typeof AOS !== 'undefined' && !prefersReduced) {
    AOS.init({
      duration: 850,
      easing: 'ease-out-cubic',
      once: true,
      offset: 50,
      delay: 0,
      mirror: false
    });
  }

  // ---------- Counters ----------
  document.querySelectorAll('[data-counter]').forEach((el) => {
    const target = Number(el.dataset.counter || 0);
    if (!target) return;
    const obs = new IntersectionObserver((entries) => {
      if (!entries[0].isIntersecting) return;
      let n = 0;
      const step = Math.max(1, Math.ceil(target / 50));
      const t = setInterval(() => {
        n = Math.min(target, n + step);
        el.textContent = n.toLocaleString();
        if (n >= target) clearInterval(t);
      }, prefersReduced ? 0 : 25);
      obs.disconnect();
    }, { threshold: 0.3 });
    obs.observe(el);
  });

  // ---------- Donation amount selector ----------
  document.querySelectorAll('.amount-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.amount-btn').forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');
      const input = document.getElementById('amount');
      if (input) {
        input.value = btn.dataset.amount || btn.textContent.replace(/[^\d]/g, '');
        input.dispatchEvent(new Event('input'));
      }
    });
  });

  const amountInput = document.getElementById('amount');
  amountInput?.addEventListener('input', () => {
    document.querySelectorAll('.amount-btn').forEach((b) => {
      const val = b.dataset.amount || b.textContent.replace(/[^\d]/g, '');
      b.classList.toggle('active', val === amountInput.value);
    });
  });

  // ---------- Toast notifications ----------
  function showToast(title, message, type, duration) {
    type = type || 'success';
    duration = duration || 5000;
    var container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      container.setAttribute('role', 'region');
      container.setAttribute('aria-label', 'Notifications');
      document.body.appendChild(container);
    }
    var toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.setAttribute('role', 'alert');
    var iconMap = { success: 'bi-check-lg', error: 'bi-exclamation-lg' };
    toast.innerHTML =
      '<div class="toast-icon"><i class="bi ' + (iconMap[type] || 'bi-info-lg') + '"></i></div>' +
      '<div class="toast-body">' +
        '<div class="toast-title">' + title + '</div>' +
        (message ? '<div class="toast-message">' + message + '</div>' : '') +
      '</div>' +
      '<button class="toast-close" aria-label="Dismiss"><i class="bi bi-x"></i></button>' +
      '<div class="toast-progress" style="width:100%"></div>';
    container.appendChild(toast);
    var progress = toast.querySelector('.toast-progress');
    var closeBtn = toast.querySelector('.toast-close');
    var remove = function () {
      toast.classList.add('is-removing');
      setTimeout(function () {
        if (toast.parentNode) toast.parentNode.removeChild(toast);
      }, 350);
    };
    closeBtn.addEventListener('click', remove);
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        toast.classList.add('is-visible');
        if (duration > 0) {
          progress.style.transition = 'width ' + duration + 'ms linear';
          requestAnimationFrame(function () {
            progress.style.width = '0%';
          });
          setTimeout(remove, duration);
        }
      });
    });
  }

  // ---------- Timed donation prompt ----------
  const donationModal = document.getElementById('donationModal');
  if (donationModal) {
    const modalClose = donationModal.querySelectorAll('[data-modal-close]');
    let lastFocused = null;
    const modalTimer = window.setTimeout(() => {
      lastFocused = document.activeElement;
      donationModal.hidden = false;
      document.body.classList.add('modal-open');
      donationModal.classList.add('is-visible');
      donationModal.querySelector('.donation-modal-close')?.focus();
    }, 6000);

    const closeDonationModal = () => {
      window.clearTimeout(modalTimer);
      donationModal.classList.remove('is-visible');
      document.body.classList.remove('modal-open');
      window.setTimeout(() => { donationModal.hidden = true; }, 220);
      lastFocused?.focus?.();
    };

    modalClose.forEach((button) => button.addEventListener('click', closeDonationModal));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && donationModal.classList.contains('is-visible')) closeDonationModal();
    });
  }

  // ---------- Swiper ----------
  if (typeof Swiper !== 'undefined') {
    document.querySelectorAll('.swiper-testimonials').forEach((el) => {
      new Swiper(el, {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        autoplay: prefersReduced ? false : { delay: 5000, disableOnInteraction: true },
        pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
        navigation: {
          nextEl: el.querySelector('.swiper-button-next'),
          prevEl: el.querySelector('.swiper-button-prev')
        },
        breakpoints: {
          768: { slidesPerView: 2 },
          1100: { slidesPerView: 3 }
        }
      });
    });
  }

  // ---------- Gallery filter ----------
  const filterBtns = document.querySelectorAll('[data-filter]');
  const galleryItems = document.querySelectorAll('[data-category]');
  filterBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.filter;
      filterBtns.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');
      galleryItems.forEach((item) => {
        const show = cat === 'all' || item.dataset.category === cat;
        item.style.display = show ? '' : 'none';
      });
    });
  });

  // ---------- Hero video background ----------
  const heroVideos = document.querySelectorAll('.hero-bg-video');
  const videoList = window.heroVideoList || [];
  let videoIndex = 0;
  let activeSlot = 0;

  function playHeroVideo() {
    if (!heroVideos.length || !videoList.length) return;

    const current = heroVideos[activeSlot];
    const next = heroVideos[1 - activeSlot];
    const nextIndex = (videoIndex + 1) % videoList.length;

    next.src = videoList[nextIndex];
    next.load();

    const playPromise = next.play();
    if (playPromise !== undefined) {
      playPromise.then(() => {
        current.classList.remove('active');
        next.classList.add('active');
        activeSlot = 1 - activeSlot;
        videoIndex = nextIndex;
      }).catch(() => {
        videoIndex = nextIndex;
      });
    }
  }

  if (heroVideos.length && videoList.length) {
    const first = heroVideos[0];
    first.src = videoList[0];
    first.load();
    first.play().then(() => {
      videoIndex = 0;
    }).catch(() => {});

    heroVideos.forEach((v) => {
      v.addEventListener('ended', playHeroVideo);
    });
  }

  // ---------- Hero cursor spotlight ----------
  const hero = document.querySelector('.hero-v2');
  if (hero && !prefersReduced) {
    let spotlight = hero.querySelector('.hero-spotlight');
    if (!spotlight) {
      spotlight = document.createElement('div');
      spotlight.className = 'hero-spotlight';
      hero.appendChild(spotlight);
    }
    hero.addEventListener('pointermove', (e) => {
      const r = hero.getBoundingClientRect();
      const x = ((e.clientX - r.left) / r.width) * 100;
      const y = ((e.clientY - r.top) / r.height) * 100;
      hero.style.setProperty('--mx', x + '%');
      hero.style.setProperty('--my', y + '%');
    });
  }

  // ---------- Gentle 3D tilt on cards ----------
  if (!prefersReduced && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('.card, .pillar-card, .program-card').forEach((el) => {
      const max = 6;
      el.addEventListener('pointermove', (e) => {
        const r = el.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width - 0.5;
        const py = (e.clientY - r.top) / r.height - 0.5;
        el.style.transform = `perspective(800px) rotateX(${(-py * max).toFixed(2)}deg) rotateY(${(px * max).toFixed(2)}deg) translateY(-4px)`;
      });
      el.addEventListener('pointerleave', () => {
        el.style.transform = '';
      });
    });
  }

  // ---------- Back to top ----------
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    const toggleBackToTop = () => backToTop.classList.toggle('is-visible', window.scrollY > 420);
    toggleBackToTop();
    window.addEventListener('scroll', toggleBackToTop, { passive: true });
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: prefersReduced ? 'auto' : 'smooth' });
    });
  }

  // ---------- Reveal fallback ----------
  if (typeof AOS === 'undefined') {
    const io = new IntersectionObserver(
      (entries) => entries.forEach((e) => e.isIntersecting && e.target.classList.add('visible')),
      { threshold: 0.12 }
    );
    document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
  }
})();
