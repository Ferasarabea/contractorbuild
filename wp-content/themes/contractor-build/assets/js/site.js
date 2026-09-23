document.addEventListener('DOMContentLoaded', () => {
  const button = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.primary-nav');
  if (button && nav) {
    button.addEventListener('click', () => {
      const open = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', String(!open));
      button.textContent = open ? 'MENU' : 'CLOSE';
      nav.classList.toggle('is-open', !open);
    });
  }

  document.querySelectorAll('a[href^="tel:"]').forEach((link) => {
    link.addEventListener('click', () => window.dataLayer?.push({ event: 'phone_click', link_url: link.href }));
  });
  document.querySelectorAll('a[href^="mailto:"]').forEach((link) => {
    link.addEventListener('click', () => window.dataLayer?.push({ event: 'email_click', link_url: link.href }));
  });

  document.querySelectorAll('[data-cb-form]').forEach((form) => {
    let started = false;
    form.addEventListener('focusin', () => {
      if (!started) {
        started = true;
        window.dataLayer?.push({ event: 'form_start', form_name: form.dataset.cbForm });
      }
    });
    form.addEventListener('submit', () => {
      window.dataLayer?.push({ event: 'form_submit', form_name: form.dataset.cbForm });
      if (form.dataset.cbForm === 'audit') {
        window.dataLayer?.push({ event: 'audit_request', form_name: form.dataset.cbForm });
      }
    });
  });
});
