document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('[data-header]');
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.primary-nav');
  const megaButton = document.querySelector('.mega-toggle');
  const megaMenu = document.querySelector('.mega-menu');

  const trackEvent = (eventName, parameters = {}) => {
    if (typeof window.gtag === 'function') {
      window.gtag('event', eventName, parameters);
    } else {
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({ event: eventName, ...parameters });
    }
  };

  const closeNavigation = () => {
    menuButton?.setAttribute('aria-expanded', 'false');
    nav?.classList.remove('is-open');
    document.body.classList.remove('nav-open');
  };

  menuButton?.addEventListener('click', () => {
    const open = menuButton.getAttribute('aria-expanded') === 'true';
    menuButton.setAttribute('aria-expanded', String(!open));
    nav?.classList.toggle('is-open', !open);
    document.body.classList.toggle('nav-open', !open);
  });

  megaButton?.addEventListener('click', () => {
    const open = megaButton.getAttribute('aria-expanded') === 'true';
    megaButton.setAttribute('aria-expanded', String(!open));
    megaMenu?.classList.toggle('is-open', !open);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      megaButton?.setAttribute('aria-expanded', 'false');
      megaMenu?.classList.remove('is-open');
      closeNavigation();
      menuButton?.focus();
    }
  });

  nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeNavigation));
  window.addEventListener('resize', () => {
    if (window.innerWidth > 900) closeNavigation();
  }, { passive: true });

  const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 12);
  updateHeader();
  window.addEventListener('scroll', updateHeader, { passive: true });

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const reveals = document.querySelectorAll('.reveal');
  if (reducedMotion || !('IntersectionObserver' in window)) {
    reveals.forEach((element) => element.classList.add('is-visible'));
  } else {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    reveals.forEach((element) => observer.observe(element));
  }

  document.querySelectorAll('a[href^="tel:"]').forEach((link) => {
    link.addEventListener('click', () => trackEvent('phone_click', { link_url: link.href }));
  });
  document.querySelectorAll('a[href^="mailto:"]').forEach((link) => {
    link.addEventListener('click', () => trackEvent('email_click', { link_url: link.href }));
  });
  document.querySelectorAll('[data-cb-form]').forEach((form) => {
    let started = false;
    form.addEventListener('focusin', () => {
      if (!started) {
        started = true;
        trackEvent('form_start', { form_name: form.dataset.cbForm });
      }
    });
    form.addEventListener('submit', () => {
      trackEvent('form_submit', { form_name: form.dataset.cbForm });
      if (form.dataset.cbForm === 'audit') trackEvent('audit_request', { form_name: form.dataset.cbForm });
    });
  });
});
