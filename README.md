# Contractor Build WordPress Platform

A lightweight, conversion-focused WordPress implementation for Contractor Build. The repository contains:

- `wp-content/themes/contractor-build` — custom block-ready theme, templates, patterns, styles, and accessible navigation.
- `wp-content/plugins/contractor-build-growth` — portable business functionality: content types, lead forms, tracking, schema, settings, and redirects.
- `docs` — launch, administration, SEO, migration, and analytics guidance.

## Local installation

1. Install WordPress 6.5+ and PHP 8.1+.
2. Copy this repository's `wp-content` folders into the WordPress installation.
3. Activate **Contractor Build Growth System**, then activate **Contractor Build**.
4. Visit **Settings → Permalinks** once and save.
5. Visit **Contractor Build → Setup** and select **Create starter pages**.
6. Assign the generated navigation menu to **Primary Navigation** and configure a static homepage if WordPress did not do so automatically.

The theme intentionally avoids a page builder, external font requests, jQuery dependencies, and hard-coded analytics account IDs.

See [`docs/ADMIN-GUIDE.md`](docs/ADMIN-GUIDE.md) and [`docs/LAUNCH-CHECKLIST.md`](docs/LAUNCH-CHECKLIST.md) before launch.

## Preview without WordPress

If WordPress, Docker, or MySQL are not available, run the zero-dependency theme preview:

```bash
./scripts/start-preview.sh
```

Then open <http://127.0.0.1:8080>. Set `CB_PREVIEW_HOST=0.0.0.0` or `CB_PREVIEW_PORT=9000` when a remote workspace requires a forwarded host or different port. This preview renders the committed homepage template and assets; forms, admin editing, permalinks, email, and CRM delivery require a real WordPress installation.

## Hosting access

This repository does not include hosting credentials or provision public infrastructure. For a public URL, deploy the two `wp-content` packages into a managed WordPress host or an existing WordPress server, point the domain to that host, activate the plugin and theme, and complete the setup and launch checklists. Never commit hosting, SFTP, database, DNS, or analytics credentials to this repository.
