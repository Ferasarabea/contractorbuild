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

FORM_OPEN = '<form class="lead-form" action="https://formsubmit.co/contractorbuild0@gmail.com" method="POST">'
TRACKED_FORM_OPEN = '<form class="lead-form" action="https://formsubmit.co/contractorbuild0@gmail.com" method="POST" data-cb-form="audit">'
SUCCESS_REDIRECT = '<input type="hidden" name="_next" value="https://contractor-build.com/free-marketing-audit/?submitted=1">'


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

    # Static GitHub Pages uses FormSubmit instead of the WordPress form handler.
    # Mark those forms for form_start/form_submit tracking and return only
    # successful submissions to a URL that site.js turns into audit_request.
    if FORM_OPEN in html:
        html = html.replace(FORM_OPEN, TRACKED_FORM_OPEN + SUCCESS_REDIRECT)

    file.write_text(html, encoding="utf-8")


if __name__ == "__main__":
    main()
