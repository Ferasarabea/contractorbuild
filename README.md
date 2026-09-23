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

If `localhost` belongs to a remote container or cannot be forwarded, create a self-contained file instead:

```bash
./scripts/export-preview.sh
```

Open [`contractor-build-preview.html`](contractor-build-preview.html) directly in Chrome, Safari, Firefox, or Edge for a single-file homepage check. To generate the complete 51-page inspection site and QA inventory, run:

```bash
./scripts/export-site.sh
```

The complete export includes service archives and pages, industry archives and pages, About, Resources, Results, Contact, audit, privacy, terms, and accessibility pages. Open `index.html` through a local server or publish the repository with GitHub Pages so relative navigation works exactly as deployed.

## Hosting access

This repository does not include hosting credentials or provision public infrastructure. For a public URL, deploy the two `wp-content` packages into a managed WordPress host or an existing WordPress server, point the domain to that host, activate the plugin and theme, and complete the setup and launch checklists. Never commit hosting, SFTP, database, DNS, or analytics credentials to this repository.

For a shareable static design preview, the included GitHub Pages workflow publishes `contractor-build-preview.html` automatically after the repository is pushed to GitHub and Pages is configured to use GitHub Actions. For a complete editable WordPress deployment, follow [`docs/HOSTING.md`](docs/HOSTING.md).

GitHub Pages can also publish without Actions because the complete generated site begins at [`index.html`](index.html). In **Settings → Pages**, change **Source** to **Deploy from a branch**, choose the repository's default branch and `/ (root)`, then save.
