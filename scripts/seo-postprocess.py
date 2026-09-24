#!/usr/bin/env python3
"""Add production SEO metadata to exported Contractor Build HTML pages."""
from __future__ import annotations

import json
import posixpath
import re
import sys
from pathlib import Path

BASE_URL = "https://contractor-build.com"
SITE_NAME = "Contractor Build"


def normalize_url_path(value: str) -> str:
    path = "/" + value.strip("/")
    if path == "/":
        return "/"
    return path + "/"


def canonical_url(path: str) -> str:
    return BASE_URL + ("/" if path == "/" else path)


def first(pattern: str, html: str, default: str = "") -> str:
    match = re.search(pattern, html, flags=re.IGNORECASE | re.DOTALL)
    return match.group(1).strip() if match else default


def breadcrumb_name(segment: str) -> str:
    known = {
        "services": "Services",
        "industries": "Industries",
        "about": "About",
        "results": "Results",
        "resources": "Resources",
        "contact": "Contact",
        "free-marketing-audit": "Free Marketing Audit",
        "privacy-policy": "Privacy Policy",
        "terms": "Terms",
        "accessibility": "Accessibility",
    }
    return known.get(segment, segment.replace("-", " ").title())


def update_schema(html: str, path: str, title: str, description: str) -> str:
    canonical = canonical_url(path)
    pattern = re.compile(r'<script\s+type="application/ld\+json">(.*?)</script>', re.IGNORECASE | re.DOTALL)
    match = pattern.search(html)
    if not match:
        return html

    try:
        data = json.loads(match.group(1))
    except json.JSONDecodeError:
        return html

    graph = data.get("@graph") if isinstance(data, dict) else None
    if not isinstance(graph, list):
        graph = []

    organization = next((node for node in graph if isinstance(node, dict) and node.get("@type") == "Organization"), None)
    if organization is None:
        organization = {"@type": "Organization"}
        graph.insert(0, organization)
    organization.update(
        {
            "@id": BASE_URL + "/#organization",
            "name": SITE_NAME,
            "url": BASE_URL + "/",
        }
    )

    website = next((node for node in graph if isinstance(node, dict) and node.get("@type") == "WebSite"), None)
    if website is None:
        website = {"@type": "WebSite"}
        graph.append(website)
    website.update(
        {
            "@id": BASE_URL + "/#website",
            "url": BASE_URL + "/",
            "name": SITE_NAME,
            "publisher": {"@id": BASE_URL + "/#organization"},
        }
    )

    webpage = next((node for node in graph if isinstance(node, dict) and node.get("@type") == "WebPage"), None)
    if webpage is None:
        webpage = {"@type": "WebPage"}
        graph.append(webpage)
    webpage.update(
        {
            "@id": canonical + "#webpage",
            "url": canonical,
            "name": title,
            "description": description,
            "isPartOf": {"@id": BASE_URL + "/#website"},
            "about": {"@id": BASE_URL + "/#organization"},
        }
    )

    graph = [node for node in graph if not (isinstance(node, dict) and node.get("@type") == "BreadcrumbList")]
    if path != "/":
        segments = [segment for segment in path.strip("/").split("/") if segment]
        items = [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": BASE_URL + "/",
            }
        ]
        for index, segment in enumerate(segments, start=2):
            item_path = "/" + "/".join(segments[: index - 1]) + "/"
            name = title.split("|")[0].strip() if index == len(segments) + 1 else breadcrumb_name(segment)
            items.append(
                {
                    "@type": "ListItem",
                    "position": index,
                    "name": name,
                    "item": BASE_URL + item_path,
                }
            )
        graph.append({"@type": "BreadcrumbList", "itemListElement": items})

    data["@context"] = "https://schema.org"
    data["@graph"] = graph
    rendered = json.dumps(data, ensure_ascii=False, separators=(",", ":"))
    return html[: match.start()] + f'<script type="application/ld+json">{rendered}</script>' + html[match.end() :]


def inject_head_metadata(html: str, path: str) -> str:
    canonical = canonical_url(path)

    if path == "/":
        home_title = "Contractor Marketing Agency for Home Services | Contractor Build"
        home_meta = (
            "Contractor Build helps contractors and home-service businesses grow with websites, SEO, "
            "Google Ads, Local Services Ads, tracking, and follow-up."
        )
        html = re.sub(r"<title>.*?</title>", f"<title>{home_title}</title>", html, count=1, flags=re.IGNORECASE | re.DOTALL)
        html = re.sub(
            r'<meta\s+name="description"\s+content="[^"]*"\s*/?>',
            f'<meta name="description" content="{home_meta}">',
            html,
            count=1,
            flags=re.IGNORECASE,
        )

    title = first(r"<title>(.*?)</title>", html, SITE_NAME)
    description = first(r'<meta\s+name="description"\s+content="([^"]*)"', html)

    # Keep the pass idempotent when developers run the exporter repeatedly.
    removals = [
        r'\s*<link\s+rel="canonical"[^>]*>',
        r'\s*<link\s+rel="icon"[^>]*>',
        r'\s*<meta\s+property="og:(?:title|description|type|url|site_name)"[^>]*>',
        r'\s*<meta\s+name="twitter:(?:card|title|description)"[^>]*>',
    ]
    for pattern in removals:
        html = re.sub(pattern, "", html, flags=re.IGNORECASE)

    start = path.strip("/") or "."
    favicon = posixpath.relpath("favicon.svg", start)
    tags = (
        f'<link rel="canonical" href="{canonical}">'
        f'<link rel="icon" href="{favicon}" type="image/svg+xml">'
        f'<meta property="og:title" content="{title}">'
        f'<meta property="og:description" content="{description}">'
        f'<meta property="og:type" content="website">'
        f'<meta property="og:url" content="{canonical}">'
        f'<meta property="og:site_name" content="{SITE_NAME}">'
        f'<meta name="twitter:card" content="summary">'
        f'<meta name="twitter:title" content="{title}">'
        f'<meta name="twitter:description" content="{description}">'
    )

    robots = re.search(r'<meta\s+name="robots"[^>]*>', html, flags=re.IGNORECASE)
    if robots:
        html = html[: robots.end()] + tags + html[robots.end() :]
    else:
        html = html.replace("</head>", '<meta name="robots" content="index,follow">' + tags + "</head>", 1)

    return update_schema(html, path, title, description)


def clean_launch_copy(html: str) -> str:
    replacements = {
        '<div class="card"><p>Phone, email, address, and social profiles appear here only after an administrator configures them.</p></div>': (
            '<div class="card"><h3>Email Contractor Build</h3>'
            '<p><a href="mailto:contractorbuild0@gmail.com">contractorbuild0@gmail.com</a></p>'
            '<p>For a marketing review, use the form below so we can understand your business, service area, and goals.</p></div>'
        ),
        '<p>Configured business contact details appear below. If none are shown, use the secure form.</p>': (
            '<p>Email us directly or use the form below to tell us about your business, market, and growth goals.</p>'
        ),
        '<p><strong>Review required before production:</strong> this operational template must be reviewed for the company’s actual jurisdiction, vendors, retention practices, and contact information.</p>': (
            '<p><strong>Effective September 24, 2026.</strong> This notice explains how Contractor Build handles information submitted through this website and the service providers used to deliver the site and respond to requests.</p>'
        ),
        '<p>The production site should describe the analytics and advertising technologies actually enabled and provide consent controls where required.</p>': (
            '<p>Contractor Build may add analytics or advertising measurement tools as the site evolves. When those tools are enabled, this notice and any required consent controls will be updated to reflect the technologies in use.</p>'
        ),
        '<p>This notice may be updated as practices or legal requirements change. The production version should display an effective date and a configured privacy contact.</p>': (
            '<p>This notice may be updated as practices or legal requirements change. Privacy questions or requests can be sent to <a href="mailto:contractorbuild0@gmail.com">contractorbuild0@gmail.com</a>.</p>'
        ),
        '<p><strong>Review required before production:</strong> qualified counsel should adapt these terms to the company’s actual entity, jurisdiction, services, and contact information.</p>': (
            '<p><strong>Effective September 24, 2026.</strong> These terms govern use of the Contractor Build website. Service engagements are governed by separate written agreements.</p>'
        ),
        '<p>The site is provided on an as-available basis to the extent permitted by law. The final production terms should contain limitations appropriate to the governing jurisdiction.</p>': (
            '<p>The site is provided on an as-available basis to the extent permitted by law. Contractor Build does not guarantee rankings, lead volume, revenue, profitability, or uninterrupted availability of third-party platforms.</p>'
        ),
    }
    for old, new in replacements.items():
        html = html.replace(old, new)
    return html


def main() -> None:
    if len(sys.argv) != 3:
        raise SystemExit("usage: seo-postprocess.py FILE URL_PATH")

    file = Path(sys.argv[1])
    path = normalize_url_path(sys.argv[2])
    html = file.read_text(encoding="utf-8")
    html = clean_launch_copy(html)
    html = html.replace("https://ferasarabea.github.io/contractorbuild/", BASE_URL + "/")
    html = inject_head_metadata(html, path)
    file.write_text(html, encoding="utf-8")


if __name__ == "__main__":
    main()
