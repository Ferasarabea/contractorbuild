#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PORT="${CB_EXPORT_SITE_PORT:-8098}"
LOG="$(mktemp)"
URLS="$(mktemp)"

cleanup() {
	if [[ -n "${SERVER_PID:-}" ]]; then kill "${SERVER_PID}" 2>/dev/null || true; fi
	rm -f "${LOG}" "${URLS}"
}
trap cleanup EXIT

php -r '
define("ABSPATH", getcwd() . "/");
function add_action() {}
function esc_html($v){return htmlspecialchars($v, ENT_QUOTES, "UTF-8");}
function esc_url($v){return $v;}
function home_url($v="/"){return $v;}
require "wp-content/plugins/contractor-build-growth/includes/content-library.php";
require "wp-content/plugins/contractor-build-growth/includes/setup.php";
echo "/\n/services/\n/industries/\n";
foreach (array_keys(cbg_service_library()) as $slug) echo "/services/$slug/\n";
foreach (array_keys(cbg_industry_library()) as $slug) echo "/industries/$slug/\n";
foreach (array_keys(cbg_core_page_library()) as $slug) if ($slug !== "home") echo "/$slug/\n";
' > "${URLS}"

CB_PREVIEW_HOST=127.0.0.1 CB_PREVIEW_PORT="${PORT}" "${ROOT}/scripts/start-preview.sh" >"${LOG}" 2>&1 &
SERVER_PID=$!
for _ in {1..40}; do curl -fsS "http://127.0.0.1:${PORT}/" >/dev/null 2>&1 && break; sleep 0.1; done

while IFS= read -r url; do
	[[ -n "${url}" ]] || continue
	if [[ "${url}" == "/" ]]; then destination="${ROOT}/index.html"; else destination="${ROOT}${url}index.html"; fi
	mkdir -p "$(dirname "${destination}")"
	curl -fsS "http://127.0.0.1:${PORT}${url}" -o "${destination}"
	python3 "${ROOT}/scripts/rewrite-static-links.py" "${destination}" "${url}"
done < "${URLS}"

python3 "${ROOT}/scripts/audit-site.py" "${ROOT}" "${URLS}" "${ROOT}/docs/URL-INVENTORY.csv"
echo "Exported $(wc -l < "${URLS}" | tr -d ' ') public pages and docs/URL-INVENTORY.csv"
