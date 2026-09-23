# Hosting Contractor Build

There are two different deployment targets in this repository. Choose the one that matches the goal.

## Option 1: Public visual preview on GitHub Pages

This is the quickest way to inspect and share all 51 public routes. It publishes the generated HTML site, including every service, industry, and core page. It does **not** run WordPress administration, form delivery, the CRM webhook, email, or database-backed publishing.

### Recommended: deploy directly from the branch

The screenshot showing suggested Actions workflows means GitHub has not run a Pages workflow from the repository's default branch yet. The most direct setup is branch deployment; the repository includes `index.html` plus all generated route directories at its root.

1. Push or merge the latest commit into the repository's default branch, usually `main`.
2. Open **Settings → Pages**.
3. Change **Source** from **GitHub Actions** to **Deploy from a branch**.
4. Choose `main` (or the actual default branch) and `/ (root)`.
5. Select **Save**, wait for the deployment to finish, and refresh the Pages settings screen.

### Alternative: deploy with the included workflow

The repository also includes `.github/workflows/deploy-preview-pages.yml`. To use it:

1. Push or merge the workflow into the repository's default branch.
2. Keep **Settings → Pages → Source** set to **GitHub Actions**.
3. Open **Actions**, select **Deploy visual preview to GitHub Pages**, and choose **Run workflow**.
4. If it is not listed, confirm Actions are enabled under **Settings → Actions → General** and that `.github/workflows/deploy-preview-pages.yml` exists on the default branch.
5. Open the deployment URL shown in the workflow's `github-pages` environment.

GitHub normally assigns a URL in the form `https://ACCOUNT.github.io/REPOSITORY/`. A real URL cannot be generated until a GitHub repository exists and the account has authorized the deployment.

## Option 2: Production WordPress hosting

Use this option for editable pages, forms, CRM delivery, email, SEO plugins, redirects, and WordPress administration.

Required access:

- A managed WordPress hosting account or Linux server
- A staging domain or temporary host URL
- WordPress administrator access
- SFTP/SSH or hosting file-manager access
- DNS access when the production domain is ready to move
- SMTP or transactional-email credentials for reliable form delivery

Deployment sequence:

1. Create a staging WordPress installation with HTTPS, PHP 8.1+, and a supported database.
2. Upload `wp-content/themes/contractor-build` to `wp-content/themes/`.
3. Upload `wp-content/plugins/contractor-build-growth` to `wp-content/plugins/`.
4. Activate **Contractor Build Growth System**, then activate **Contractor Build**.
5. Run **Contractor Build → Setup → Create starter pages** once.
6. Save **Settings → Permalinks**, configure a static homepage, and assign the menus.
7. Configure the lead email, CRM webhook, GTM container, SMTP, backups, caching, and security.
8. Complete the launch checklist and legacy URL mapping before pointing production DNS.

## Why there is no public URL yet

The current workspace has no Git remote, no authenticated GitHub session, and no WordPress hosting credentials. Hosting cannot be provisioned securely without control of a destination account. Do not place passwords, tokens, private keys, database credentials, or DNS credentials in a commit or support message; use the host's secret manager or an approved secure-sharing method.
