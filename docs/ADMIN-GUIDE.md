# Contractor Build administration guide

## Initial setup

1. Activate **Contractor Build Growth System** and the **Contractor Build** theme.
2. Open **Contractor Build → Setup** and select **Create starter pages** once.
3. In **Settings → Reading**, choose a static homepage. Create a blank page called Home and assign it; the theme's `front-page.php` supplies the designed homepage.
4. In **Appearance → Menus**, create and assign Primary and Footer menus.
5. In **Settings → Permalinks**, select `Post name` and save.

## Editing content

- **Services** are managed from the Services menu. Title, excerpt, body, and featured image are editable with Gutenberg.
- **Industries** use a separate content type and reusable industry template. Every page should address unique search behavior, services, seasonality, buying cycle, conversion needs, and recommended channel mix. Do not publish pages created only by swapping an industry name.
- **Case Studies** include an editor warning. Do not publish client names, screenshots, testimonials, or metrics without verification and approval.
- **Resources** use standard Posts plus Resource Categories.
- **Pages** control About, Contact, audit, and legal content.

## Adding a service

1. Select **Services → Add New**.
2. Write a distinct title, concise excerpt, and useful body content.
3. Include the problem, approach, workstreams, measurement, and next step.
4. Add contextual links to relevant industries and resources.
5. Configure metadata in Yoast SEO or Rank Math, if installed.

## Adding an industry

Research the industry's buying journey before drafting. Cover customer urgency, project value, sales cycle, seasonality, key services, service radius, trust requirements, organic-search opportunity, paid-media opportunity, landing-page needs, and lead follow-up. Link only to relevant services.

## Forms and lead routing

The `[contractor_build_audit_form]` shortcode renders the audit form. It includes a nonce, honeypot, server-side required-field validation, email validation, UTM capture, referrer and landing-page capture, email delivery, and an optional JSON webhook.

Configure the notification email and webhook at **Contractor Build → Setup**. For production, add edge-level bot protection or a privacy-conscious CAPTCHA if spam volume warrants it.

## SEO plugin compatibility

Use either Yoast SEO or Rank Math, not both. The plugin suppresses its own basic JSON-LD when either is active to avoid duplicate graphs. Use the selected SEO plugin for titles, descriptions, canonicals, social metadata, robots directives, and XML sitemaps.

## Analytics

Add a GTM container ID under **Contractor Build → Setup**. The front end initializes `dataLayer` and emits `phone_click` and `email_click`. Configure GTM to listen for the following intended events:

- `phone_click`
- `email_click`
- `form_start`
- `form_submit`
- `audit_request`
- `consultation_request`

Do not place account IDs directly in theme files.
