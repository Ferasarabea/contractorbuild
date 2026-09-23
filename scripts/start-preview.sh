#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
HOST="${CB_PREVIEW_HOST:-127.0.0.1}"
PORT="${CB_PREVIEW_PORT:-8080}"

echo "Starting the Contractor Build theme preview at http://${HOST}:${PORT}"
echo "Press Ctrl+C to stop the server."
exec php -S "${HOST}:${PORT}" "${ROOT}/scripts/preview-router.php"
