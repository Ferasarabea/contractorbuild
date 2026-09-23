# Content completion and QA record

The canonical starter library creates the complete public information architecture in a fresh WordPress installation. The static inspection build uses the same library, so service and industry copy can be reviewed before WordPress hosting is connected.

## Public inventory

- 1 homepage
- 2 collection pages: Services and Industries
- 21 individual service pages
- 19 individual industry pages
- 8 additional core pages: About, Results, Resources, Contact, Free Marketing Audit, Privacy Policy, Terms, and Accessibility
- **51 total public URLs**

The machine-readable audit is in `docs/URL-INVENTORY.csv`. It reports URL, HTTP status, unique title, H1, meta description, content-completion state, internal links, CTA, schema, indexability, word count, and broken internal links.

## Intentionally unpublished content

- **Individual case studies:** none are published because verified client names, screenshots, timelines, and results were not supplied. The admin includes a structured case-study template and warning; the Results page shows only published, verified entries.
- **Resource articles:** no filler articles were created. The Resources hub and nine editorial categories are ready for useful, reviewed articles.
- **Testimonials, ratings, client logos, awards, certifications, staff biographies, addresses, phone numbers, email addresses, and social profiles:** omitted until verified or configured by an administrator.

## Information still required before production

- Legal business name and counsel-approved Privacy Policy and Terms
- Public phone, email, address (only if appropriate), and social URLs
- CRM webhook and field mapping, lead-notification recipient, and transactional-email service
- GTM/container and consent configuration
- Verified case-study source data and publication approvals
- Production host, staging URL, DNS access, and final domain decision
- Legacy URL crawl and redirect approvals

## Link and content verification

Run `./scripts/export-site.sh`. The command generates all public routes, rewrites links for project-level GitHub Pages, and fails if an internal link points outside the inventory. The current inventory contains no broken internal links; every route has a CTA, internal links, metadata, schema, and an indexable status.
