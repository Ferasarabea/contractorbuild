#!/usr/bin/env python3
"""Use the PNG favicon format explicitly supported by Google Search."""
from __future__ import annotations

import posixpath
import re
import sys
from pathlib import Path


def main() -> None:
    if len(sys.argv) != 3:
        raise SystemExit("usage: google-search-favicon.py FILE URL_PATH")

    file = Path(sys.argv[1])
    url = sys.argv[2]
    start = url.strip("/") or "."
    favicon = posixpath.relpath("favicon.png", start)

    html = file.read_text(encoding="utf-8")
    replacement = f'<link rel="icon" href="{favicon}" type="image/png">'
    html, count = re.subn(
        r'<link\s+rel="icon"\s+href="[^"]+"\s+type="image/svg\+xml">',
        replacement,
        html,
        count=1,
        flags=re.IGNORECASE,
    )
    if count != 1:
        raise SystemExit(f"expected one SVG favicon link in {file}, replaced {count}")

    file.write_text(html, encoding="utf-8")


if __name__ == "__main__":
    main()
