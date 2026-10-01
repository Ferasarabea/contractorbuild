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

  // Google forwards eligible ad visitors' calls to the existing business number.
  // Update both the displayed number and the dial target, including fallback links.
  let activePhoneDisplay = phoneDisplay;
  let activePhoneHref = phoneHref;
  if (typeof window.gtag === 'function') {
    window.gtag('config', 'AW-18142369282/oTVfCM6ur4wdEIKs-spD', {
      phone_conversion_number: phoneDisplay,
      phone_conversion_callback: (formattedNumber, dialNumber) => {
        activePhoneDisplay = formattedNumber;
        activePhoneHref = 'tel:' + dialNumber;
        document.querySelectorAll('a[href^="tel:"]').forEach((link) => {
          if (link.getAttribute('href').replace(/\D/g, '') !== '12105506890' &&
              link.getAttribute('href').replace(/\D/g, '') !== '2105506890') return;
          link.href = activePhoneHref;
          const walker = document.createTreeWalker(link, NodeFilter.SHOW_TEXT);
          let textNode;
          while ((textNode = walker.nextNode())) {
            textNode.textContent = textNode.textContent.replace(phoneDisplay, formattedNumber);
          }
          if (link.hasAttribute('aria-label')) {
            link.setAttribute('aria-label', link.getAttribute('aria-label').replace(phoneDisplay, formattedNumber));
          }
        });
      }
    });
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
    const usesFormspree = form.action.startsWith('https://formspree.io/f/');
    if (!usesFormspree && !form.action.startsWith('https://formsubmit.co/')) return;
    const panel = document.createElement('div');
    panel.className = 'field--full';
    panel.setAttribute('role', 'status');
    panel.setAttribute('aria-live', 'polite');
    form.append(panel);
    let submitting = false;
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      if (submitting || !form.reportValidity()) return;
      submitting = true;
      const button = form.querySelector('[type="submit"]');
      const originalLabel = button.textContent;
      button.disabled = true;
      button.textContent = 'Sending…';
      panel.replaceChildren();
      panel.textContent = 'Sending your request…';
      trackEvent('form_submit', { form_name: form.dataset.cbForm });
      const data = Object.fromEntries(new FormData(form));
      delete data._next;
      if (usesFormspree) {
        data.subject = data.subject || data._subject || 'Contractor Build inquiry';
        delete data._subject;
        delete data._template;
      }
      const controller = new AbortController();
      const timeout = setTimeout(() => controller.abort(), 15000);
      try {
        const response = await fetch(usesFormspree ? form.action : form.action.replace('https://formsubmit.co/', 'https://formsubmit.co/ajax/'), {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify(data),
          signal: controller.signal
        });
        if (!response.ok) throw new Error('Delivery failed');
        const result = await response.json();
        if (usesFormspree ? result.ok !== true : (result.success !== true && result.success !== 'true')) throw new Error('Delivery not confirmed');
        panel.textContent = 'Thank you! Your request was accepted. Our team will contact you about the next step.';
        trackEvent('audit_request', { form_name: form.dataset.cbForm });
        // Fire Ads only after the provider confirms receipt, never on a submit click.
        trackEvent('conversion', { send_to: 'AW-18142369282/eldHCKndqIwdEIKs-spD' });
        form.reset();
      } catch {
        panel.replaceChildren();
        const message = document.createElement('p');
        message.textContent = 'We could not confirm delivery. Your details are still here. You can email your request below—review the draft and press Send in your email app—or call 210-550-6890.';
        const email = document.createElement('a');
        email.className = 'btn btn--dark';
        email.textContent = 'Email my request';
        const body = Object.entries(data).filter(([key]) => !key.startsWith('_')).map(([key, value]) => `${key.replaceAll('_', ' ')}: ${value}`).join('\n') + `\n\nPage: ${window.location.origin}${window.location.pathname}`;
        email.href = `mailto:contractorbuild0@gmail.com?subject=${encodeURIComponent(data.subject || data._subject || 'Contractor Build inquiry')}&body=${encodeURIComponent(body)}`;
        email.addEventListener('click', () => trackEvent('email_click', { form_name: form.dataset.cbForm }));
        const call = document.createElement('a');
        call.href = activePhoneHref;
        call.textContent = ` Call ${activePhoneDisplay}`;
        call.addEventListener('click', () => trackEvent('phone_click', { form_name: form.dataset.cbForm }));
        panel.append(message, email, call);
        trackEvent('form_error', { form_name: form.dataset.cbForm });
      } finally {
        clearTimeout(timeout);
        submitting = false;
        button.disabled = false;
        button.textContent = originalLabel;
      }
    });
  });
});
