#!/usr/bin/env python3
"""Inject Contractor Build GA4 tracking into exported HTML pages."""
from __future__ import annotations

import re
import sys
from pathlib import Path

MEASUREMENT_ID = "G-3QR1BNJK69"

GOOGLE_TAG = f'''<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={MEASUREMENT_ID}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){{dataLayer.push(arguments);}}
  gtag('js', new Date());
  gtag('config', '{MEASUREMENT_ID}');
</script>
'''


def main() -> None:
    if len(sys.argv) != 2:
        raise SystemExit("usage: analytics-postprocess.py FILE")

    file = Path(sys.argv[1])
    html = file.read_text(encoding="utf-8")

    # Keep export runs idempotent and prevent duplicate page_view events.
    html = re.sub(
        r'\s*<!-- Google tag \(gtag\.js\) -->\s*'
        r'<script async src="https://www\.googletagmanager\.com/gtag/js\?id=G-[A-Z0-9]+"></script>\s*'
        r'<script>.*?gtag\(\s*[\'\"]config[\'\"]\s*,\s*[\'\"]G-[A-Z0-9]+[\'\"]\s*\);.*?</script>\s*',
        "\n",
        html,
        flags=re.IGNORECASE | re.DOTALL,
    )

    if "</head>" not in html:
        raise SystemExit(f"missing </head> in {file}")

    html = html.replace("</head>", GOOGLE_TAG + "</head>", 1)
    file.write_text(html, encoding="utf-8")


if __name__ == "__main__":
    main()
