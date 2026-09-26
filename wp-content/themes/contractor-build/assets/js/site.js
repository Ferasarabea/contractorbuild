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

  // Keep a direct-call conversion path visible throughout the site while
  // preserving the Growth Plan button as the primary form-based CTA.
  const phoneDisplay = '210-550-6890';
  const phoneHref = 'tel:+12105506890';

  document.querySelectorAll('.nav-audit').forEach((link) => {
    link.href = phoneHref;
    link.textContent = `Call ${phoneDisplay}`;
    link.setAttribute('aria-label', `Call Contractor Build at ${phoneDisplay}`);
  });

  document.querySelectorAll('.footer-email').forEach((link) => {
    link.href = phoneHref;
    link.innerHTML = `CALL ${phoneDisplay} <span>↗</span>`;
    link.setAttribute('aria-label', `Call Contractor Build at ${phoneDisplay}`);
  });

  document.querySelectorAll('.mobile-cta').forEach((link) => {
    link.href = phoneHref;
    link.innerHTML = `Call ${phoneDisplay} <span>→</span>`;
    link.setAttribute('aria-label', `Call Contractor Build at ${phoneDisplay}`);
  });

  if (/\/contact\/?$/.test(window.location.pathname)) {
    const contactCard = document.querySelector('.entry-content .card');
    if (contactCard) {
      contactCard.innerHTML = `<p><strong>Call:</strong> <a href="${phoneHref}">${phoneDisplay}</a><br><strong>Email:</strong> <a href="mailto:contractorbuild0@gmail.com">contractorbuild0@gmail.com</a></p><p>Prefer to send details first? Use the form below and our team will review your request.</p>`;
    }
  }

  // FormSubmit redirects here only after a successful audit request. Track the
  // conversion on the return page rather than on the submit click so failed or
  // abandoned submissions do not become Google Ads conversion signals.
  const currentUrl = new URL(window.location.href);
  if (currentUrl.searchParams.get('submitted') === '1') {
    trackEvent('audit_request', { form_name: 'audit' });
    currentUrl.searchParams.delete('submitted');
    const cleanUrl = currentUrl.pathname + (currentUrl.search ? currentUrl.search : '') + currentUrl.hash;
    window.history.replaceState({}, document.title, cleanUrl);
  }

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

  // Keep audit CTAs reliable on both the production domain and the GitHub project Pages preview.
  document.querySelectorAll('a[href*="free-marketing-audit"]').forEach((link) => {
    link.addEventListener('click', (event) => {
      const isGithubProjectPreview = window.location.hostname === 'ferasarabea.github.io';
      const destination = isGithubProjectPreview ? '/contractorbuild/free-marketing-audit/' : '/free-marketing-audit/';
      event.preventDefault();
      window.location.assign(destination);
    });
  });

  document.querySelectorAll('a[href^="tel:"]').forEach((link) => {
    link.addEventListener('click', () => trackEvent('phone_click', {
      link_url: link.href,
      phone_number: phoneDisplay
    }));
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
    });
  });
});
