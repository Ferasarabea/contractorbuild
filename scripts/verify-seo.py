#!/usr/bin/env python3
"""Fail a build when a public Contractor Build page is not ready for indexing."""
from __future__ import annotations

import json
import sys
import xml.etree.ElementTree as ET
from collections import defaultdict
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urlparse

BASE_URL = "https://contractor-build.com"


class SEOParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__()
        self.capture_title = False
        self.capture_h1 = False
        self.capture_schema = False
        self.title_parts: list[str] = []
        self.h1_parts: list[str] = []
        self.h1_count = 0
        self.description = ""
        self.robots = ""
        self.canonical = ""
        self.og_title = ""
        self.og_description = ""
        self.og_url = ""
        self.favicon = ""
        self.schema_buffer: list[str] = []
        self.schemas: list[dict] = []

    @property
    def title(self) -> str:
        return "".join(self.title_parts).strip()

    @property
    def h1(self) -> str:
        return " ".join(part for part in self.h1_parts if part).strip()

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        values = {key.lower(): (value or "") for key, value in attrs}
        tag = tag.lower()
        if tag == "title":
            self.capture_title = True
        elif tag == "h1":
            self.capture_h1 = True
            self.h1_count += 1
        elif tag == "meta":
            name = values.get("name", "").lower()
            prop = values.get("property", "").lower()
            if name == "description":
                self.description = values.get("content", "")
            elif name == "robots":
                self.robots = values.get("content", "")
            elif prop == "og:title":
                self.og_title = values.get("content", "")
            elif prop == "og:description":
                self.og_description = values.get("content", "")
            elif prop == "og:url":
                self.og_url = values.get("content", "")
        elif tag == "link":
            rel = values.get("rel", "").lower()
            if rel == "canonical":
                self.canonical = values.get("href", "")
            elif "icon" in rel:
                self.favicon = values.get("href", "")
        elif tag == "script" and values.get("type", "").lower() == "application/ld+json":
            self.capture_schema = True
            self.schema_buffer = []

    def handle_endtag(self, tag: str) -> None:
        tag = tag.lower()
        if tag == "title":
            self.capture_title = False
        elif tag == "h1":
            self.capture_h1 = False
        elif tag == "script" and self.capture_schema:
            raw = "".join(self.schema_buffer).strip()
            if raw:
                try:
                    data = json.loads(raw)
                    if isinstance(data, dict):
                        self.schemas.append(data)
                except json.JSONDecodeError:
                    pass
            self.capture_schema = False
            self.schema_buffer = []

    def handle_data(self, data: str) -> None:
        if self.capture_title:
            self.title_parts.append(data)
        if self.capture_h1:
            stripped = data.strip()
            if stripped:
                self.h1_parts.append(stripped)
        if self.capture_schema:
            self.schema_buffer.append(data)


def page_file(root: Path, url: str) -> Path:
    return root / "index.html" if url == "/" else root / url.strip("/") / "index.html"


def expected_url(path: str) -> str:
    return BASE_URL + ("/" if path == "/" else path)


def schema_nodes(parser: SEOParser) -> list[dict]:
    nodes: list[dict] = []
    for schema in parser.schemas:
        graph = schema.get("@graph")
        if isinstance(graph, list):
            nodes.extend(node for node in graph if isinstance(node, dict))
        else:
            nodes.append(schema)
    return nodes


def find_schema_node(nodes: list[dict], type_name: str) -> dict | None:
    for node in nodes:
        node_type = node.get("@type")
        if node_type == type_name or (isinstance(node_type, list) and type_name in node_type):
            return node
    return None


def main() -> None:
    if len(sys.argv) != 3:
        raise SystemExit("usage: verify-seo.py ROOT URL_FILE")

    root = Path(sys.argv[1])
    url_file = Path(sys.argv[2])
    urls = [line.strip() for line in url_file.read_text().splitlines() if line.strip()]
    errors: list[str] = []
    warnings: list[str] = []
    titles: defaultdict[str, list[str]] = defaultdict(list)
    descriptions: defaultdict[str, list[str]] = defaultdict(list)

    for url in urls:
        file = page_file(root, url)
        if not file.exists():
            errors.append(f"{url}: generated HTML file is missing")
            continue

        html = file.read_text(encoding="utf-8")
        parser = SEOParser()
        parser.feed(html)
        canonical = expected_url(url)

        if not parser.title:
            errors.append(f"{url}: missing title")
        else:
            titles[parser.title].append(url)
            if not 25 <= len(parser.title) <= 70:
                warnings.append(f"{url}: title length is {len(parser.title)} characters")

        if not parser.description:
            errors.append(f"{url}: missing meta description")
        else:
            descriptions[parser.description].append(url)
            if not 70 <= len(parser.description) <= 180:
                warnings.append(f"{url}: meta description length is {len(parser.description)} characters")

        if parser.h1_count != 1:
            errors.append(f"{url}: expected exactly one H1, found {parser.h1_count}")
        if not parser.h1:
            errors.append(f"{url}: H1 is empty")

        robots = {item.strip().lower() for item in parser.robots.split(",") if item.strip()}
        if "noindex" in robots or "index" not in robots:
            errors.append(f"{url}: robots directive is not indexable ({parser.robots or 'missing'})")

        if parser.canonical != canonical:
            errors.append(f"{url}: canonical is {parser.canonical or 'missing'}, expected {canonical}")
        if parser.og_url != canonical:
            errors.append(f"{url}: og:url is {parser.og_url or 'missing'}, expected {canonical}")
        if parser.og_title != parser.title:
            errors.append(f"{url}: og:title does not match page title")
        if parser.og_description != parser.description:
            errors.append(f"{url}: og:description does not match meta description")
        if not parser.favicon:
            errors.append(f"{url}: favicon link is missing")

        if "ferasarabea.github.io/contractorbuild" in html:
            errors.append(f"{url}: contains old GitHub Pages production URL")
        if "http://contractor-build.com" in html:
            errors.append(f"{url}: contains insecure production URL")

        nodes = schema_nodes(parser)
        organization = find_schema_node(nodes, "Organization")
        website = find_schema_node(nodes, "WebSite")
        webpage = find_schema_node(nodes, "WebPage")
        if not organization or organization.get("url") != BASE_URL + "/":
            errors.append(f"{url}: Organization schema is missing the production URL")
        if not website or website.get("url") != BASE_URL + "/":
            errors.append(f"{url}: WebSite schema is missing the production URL")
        if not webpage or webpage.get("url") != canonical:
            errors.append(f"{url}: WebPage schema URL is missing or non-canonical")
        if url != "/" and not find_schema_node(nodes, "BreadcrumbList"):
            errors.append(f"{url}: BreadcrumbList schema is missing")

    for title, pages in titles.items():
        if title and len(pages) > 1:
            errors.append(f"duplicate title on {', '.join(pages)}: {title}")
    for description, pages in descriptions.items():
        if description and len(pages) > 1:
            errors.append(f"duplicate meta description on {', '.join(pages)}")

    sitemap = root / "wp-sitemap.xml"
    if not sitemap.exists():
        errors.append("wp-sitemap.xml is missing")
    else:
        try:
            tree = ET.parse(sitemap)
            ns = {"sm": "http://www.sitemaps.org/schemas/sitemap/0.9"}
            locations = [node.text or "" for node in tree.findall(".//sm:loc", ns)]
            expected_locations = [expected_url(url) for url in urls]
            if locations != expected_locations:
                errors.append("wp-sitemap.xml URLs do not exactly match the generated canonical URL inventory")
            for location in locations:
                parsed = urlparse(location)
                if parsed.scheme != "https" or parsed.netloc != "contractor-build.com":
                    errors.append(f"sitemap contains non-production URL: {location}")
        except ET.ParseError as exc:
            errors.append(f"wp-sitemap.xml is invalid XML: {exc}")

    robots_file = root / "robots.txt"
    if not robots_file.exists():
        errors.append("robots.txt is missing")
    else:
        robots_text = robots_file.read_text(encoding="utf-8")
        if "User-agent: *" not in robots_text:
            errors.append("robots.txt is missing the global user-agent group")
        if "Sitemap: https://contractor-build.com/wp-sitemap.xml" not in robots_text:
            errors.append("robots.txt is missing the production sitemap declaration")

    if warnings:
        print("SEO warnings:")
        for warning in warnings:
            print(f"- {warning}")

    if errors:
        print("Pre-index SEO verification failed:", file=sys.stderr)
        for error in errors:
            print(f"- {error}", file=sys.stderr)
        raise SystemExit(1)

    print(f"Pre-index SEO verification passed for {len(urls)} canonical pages")


if __name__ == "__main__":
    main()
