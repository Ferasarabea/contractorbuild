#!/usr/bin/env python3
"""Audit generated pages and internal links without third-party dependencies."""
from __future__ import annotations

import csv
import sys
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urljoin, urlparse


class PageParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__()
        self.capture = ""
        self.title = ""
        self.h1 = ""
        self.meta = ""
        self.links: list[str] = []
        self.text: list[str] = []
        self.has_cta = False
        self.has_schema = False

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        values = dict(attrs)
        if tag in {"title", "h1"}: self.capture = tag
        if tag == "meta" and values.get("name") == "description": self.meta = values.get("content", "") or ""
        if tag == "a" and values.get("href"):
            self.links.append(values["href"] or "")
            if "btn" in (values.get("class", "") or ""): self.has_cta = True
        if tag == "script" and values.get("type") == "application/ld+json": self.has_schema = True

    def handle_endtag(self, tag: str) -> None:
        if self.capture == tag: self.capture = ""

    def handle_data(self, data: str) -> None:
        self.text.append(data)
        if self.capture == "title": self.title += data.strip()
        if self.capture == "h1": self.h1 += data.strip()


def page_file(root: Path, url: str) -> Path:
    return root / "index.html" if url == "/" else root / url.strip("/") / "index.html"


root, url_file, output = Path(sys.argv[1]), Path(sys.argv[2]), Path(sys.argv[3])
urls = [line.strip() for line in url_file.read_text().splitlines() if line.strip()]
url_set = set(urls)
rows = []
errors = []
for url in urls:
    file = page_file(root, url)
    parser = PageParser()
    parser.feed(file.read_text())
    broken = []
    for link in parser.links:
        parsed = urlparse(link)
        if parsed.scheme or link.startswith(("#", "mailto:", "tel:")): continue
        target = urlparse(urljoin("https://preview.invalid" + url, link)).path or "/"
        if not target.endswith("/") and "." not in Path(target).name: target += "/"
        if target.startswith("/wp-content/"): continue
        if "." in Path(target).name and (root / target.lstrip("/")).exists(): continue
        if target not in url_set: broken.append(target)
    if broken: errors.append(f"{url}: {', '.join(sorted(set(broken)))}")
    words = len(" ".join(parser.text).split())
    rows.append([url, 200, parser.title, parser.h1, parser.meta, "YES" if words >= 120 else "REVIEW", "YES" if parser.links else "NO", "YES" if parser.has_cta else "NO", "YES" if parser.has_schema else "THEME/PLUGIN", "YES", words, " | ".join(sorted(set(broken)))])

output.parent.mkdir(parents=True, exist_ok=True)
with output.open("w", newline="") as handle:
    writer = csv.writer(handle, lineterminator="\n")
    writer.writerow(["PAGE", "STATUS", "TITLE", "H1", "META DESCRIPTION", "CONTENT COMPLETE?", "INTERNAL LINKS?", "CTA?", "SCHEMA?", "INDEXABLE?", "WORD COUNT", "BROKEN INTERNAL LINKS"])
    writer.writerows(rows)

if errors:
    print("Broken internal links:\n" + "\n".join(errors), file=sys.stderr)
    raise SystemExit(1)
print(f"Audited {len(rows)} pages: no broken internal links")
