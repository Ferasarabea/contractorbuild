#!/usr/bin/env python3
"""Rewrite root-relative URLs so GitHub project Pages and file previews both work."""
from __future__ import annotations

import posixpath
import re
import sys
from pathlib import Path

file, url = Path(sys.argv[1]), sys.argv[2]
start = url.strip("/")

def replace(match: re.Match[str]) -> str:
    attribute, target = match.group(1), match.group(2)
    if target == "/":
        relative = posixpath.relpath(".", start or ".") + "/"
    else:
        trailing = target.endswith("/")
        relative = posixpath.relpath(target.lstrip("/"), start or ".")
        if trailing and not relative.endswith("/"):
            relative += "/"
    return f'{attribute}="{relative}"'

html = file.read_text()
html = re.sub(r'(href|src)="(/(?!/)[^"]*)"', replace, html)
file.write_text(html)
