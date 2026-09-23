#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUTPUT="${1:-${ROOT}/contractor-build-preview.html}"
PORT="${CB_EXPORT_PORT:-8099}"
TEMP_HTML="$(mktemp)"
SERVER_LOG="$(mktemp)"

cleanup() {
	if [[ -n "${SERVER_PID:-}" ]]; then
		kill "${SERVER_PID}" 2>/dev/null || true
	fi
	rm -f "${TEMP_HTML}" "${SERVER_LOG}"
}
trap cleanup EXIT

CB_PREVIEW_HOST=127.0.0.1 CB_PREVIEW_PORT="${PORT}" "${ROOT}/scripts/start-preview.sh" >"${SERVER_LOG}" 2>&1 &
SERVER_PID=$!

for _ in {1..30}; do
	if curl -fsS "http://127.0.0.1:${PORT}/" -o "${TEMP_HTML}" 2>/dev/null; then
		break
	fi
	sleep 0.1
done

if [[ ! -s "${TEMP_HTML}" ]]; then
	cat "${SERVER_LOG}" >&2
	echo "Unable to render the preview." >&2
	exit 1
fi

python3 - "${ROOT}" "${TEMP_HTML}" "${OUTPUT}" <<'PY'
from pathlib import Path
import sys

root, source, output = map(Path, sys.argv[1:])
html = source.read_text()
css = (root / "wp-content/themes/contractor-build/style.css").read_text()
js = (root / "wp-content/themes/contractor-build/assets/js/site.js").read_text()
html = html.replace('<link rel="stylesheet" href="/wp-content/themes/contractor-build/style.css">', f"<style>\n{css}\n</style>")
html = html.replace('<script defer src="/wp-content/themes/contractor-build/assets/js/site.js"></script>', f"<script>\n{js}\n</script>")
html = html.replace('href="/free-marketing-audit/"', 'href="#preview-note"')
html = html.replace('href="/contact/"', 'href="#preview-note"')
html = html.replace('href="/services/', 'href="#services" data-preview-target="/services/')
html = html.replace('href="/industries/', 'href="#industries" data-preview-target="/industries/')
html = html.replace('</body>', '<div id="preview-note" style="padding:16px;background:#101820;color:white;text-align:center">Static visual preview. Forms and inner pages require WordPress hosting.</div></body>')
output.write_text(html)
print(f"Exported self-contained preview: {output}")
PY
