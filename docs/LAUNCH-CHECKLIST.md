# Production launch checklist

## Content and trust

- Replace every draft legal notice after counsel review.
- Confirm all company contact information and social URLs.
- Publish only approved testimonials, logos, screenshots, and case-study metrics.
- Proofread CTA destinations and form confirmation messaging.
- Confirm every industry page contains genuinely specific, useful content.

## SEO and migration

- Export the production site's URLs, titles, descriptions, headings, canonicals, status codes, internal links, images, and indexation state before cutover.
- Export backlink and landing-page data from available analytics, Search Console, and backlink tools.
- Map each valuable legacy URL to its closest relevant replacement. Use a 301 only where intent and content match.
- Add approved legacy mappings to the `cbg_redirect_map` filter in a small must-use plugin. Never redirect unrelated pages to the homepage.
- Test for redirect chains, loops, accidental 404s, mixed content, and staging hostnames.
- Choose and configure either Yoast SEO or Rank Math.
- Confirm unique titles, descriptions, one clear H1, canonicals, Open Graph data, and index directives.
- Validate XML sitemaps, robots.txt, structured data, and breadcrumbs.
- Remove staging `noindex` only after the production hostname and content are confirmed.
- Submit sitemaps in Google Search Console and monitor crawl/index reports after launch.

## Performance and accessibility

- Serve appropriately sized WebP or AVIF images with dimensions; do not upload oversized hero assets.
- Configure full-page caching, browser caching, compression, and a CDN as appropriate for the host.
- Test key templates in PageSpeed Insights and a real mobile device.
- Keyboard-test navigation, forms, focus states, dialogs, and success/error messages.
- Test color contrast, zoom to 200%, reduced motion, and common screen-reader landmarks.

## Forms, privacy, and analytics

- Test required fields, malformed email, spam honeypot, email delivery, webhook delivery, and success/error redirects.
- Verify consent language with qualified counsel for the regions and communication methods used.
- Configure GTM and test events in preview mode without submitting real customer data to analytics.
- Test `phone_click`, `email_click`, `form_start`, `form_submit`, `audit_request`, and CRM attribution.
- Apply data retention, access controls, backups, and deletion workflows appropriate to collected lead data.

## Security and operations

- Enforce HTTPS, least-privilege admin access, MFA, secure backups, and prompt core/plugin updates.
- Keep WordPress, PHP, the theme, and all plugins supported and patched.
- Configure uptime monitoring, error logging, transactional email delivery, and restore tests.
