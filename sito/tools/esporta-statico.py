#!/usr/bin/env python3
"""Salva il sito Kirby in locale come HTML statico, da pubblicare su GitHub Pages come demo.

Uso (dalla cartella sito/, con il server locale acceso su localhost:8000):
    python3 tools/esporta-statico.py ../demo

Segue i link interni da /it e /en e aggiunge la pagina delle brand guidelines (/it/brand, non linkata dal sito). Nella demo non ci sono pannello, guida per la redazione e ricerca;
il calendario e la bacheca restano come erano al momento dell'esportazione.
"""
import os
import re
import shutil
import sys
import urllib.error
import urllib.parse
import urllib.request

SRC = "http://localhost:8000"
BASE = "/iea-unipi"
PUBLIC_HOST = "fabiosantaniellobruun.github.io" + BASE

MONTHS = ("2026-07", "2027-06")  # il calendario ha un link al mese dopo e prima di ogni mese, senza fine: se ne copia solo un intervallo
SKIP = re.compile(r"^/(panel|admin-help|api)(/|$)|/(cerca|search)(\.json)?$|\.json$")
ATTR = re.compile(r'\b(href|src|action|poster)="([^"]*)"')
SRCSET = re.compile(r'\bsrcset="([^"]*)"')
CSS_URL = re.compile(r'url\(\s*["\']?([^"\')]+)["\']?\s*\)')


def clean(url):
    """Percorso interno senza dominio né ancora, o None se il link è esterno"""
    url = url.replace("&amp;", "&").strip()
    if url.startswith(SRC):
        url = url[len(SRC):] or "/"
    if not url.startswith("/") or url.startswith("//"):
        return None
    return url.split("#")[0]


def local_file(url):
    """Dove salvare un indirizzo: pagine in cartella/index.html, ?page=N come cartella page-N"""
    parsed = urllib.parse.urlsplit(url)
    path = urllib.parse.unquote(parsed.path).rstrip("/") or ""
    page = re.fullmatch(r"page=(\d+)", parsed.query)
    month = re.fullmatch(r"month=(\d{4}-\d{2})", parsed.query)
    if page:
        path += f"/page-{page.group(1)}"
    elif month:
        path += f"/month-{month.group(1)}"
    return path


def public_href(url):
    """Indirizzo dentro la demo, con il prefisso della pagina di progetto"""
    clean_url = clean(url)
    if clean_url is None:
        return url.replace(SRC, BASE) if url.startswith(SRC) else url
    parsed = urllib.parse.urlsplit(clean_url)
    path = local_file(clean_url)
    anchor = "#" + urllib.parse.urlsplit(url).fragment if "#" in url else ""
    if not os.path.splitext(path)[1]:
        path += "/"
    return BASE + urllib.parse.quote(path or "/") + anchor


def fetch(url):
    request = urllib.request.Request(SRC + urllib.parse.quote(url, safe="/?=&%:@"))
    with urllib.request.urlopen(request, timeout=20) as response:
        return response.read(), response.headers.get_content_type()


def links(html):
    found = [m.group(2) for m in ATTR.finditer(html)]
    for m in SRCSET.finditer(html):
        found += [item.split()[0] for item in m.group(1).split(",") if item.strip()]
    return found


def rewrite(html):
    # ricerca, pannello e link all'amministrazione non esistono nella demo
    html = re.sub(r'<a class="search-link".*?</a>', "", html, flags=re.S)
    html = re.sub(r'<button class="search-toggle".*?</button>', "", html, flags=re.S)
    html = re.sub(r'<form class="header-search".*?</form>', "", html, flags=re.S)
    html = re.sub(r'<div class="search-panel".*?(?=</header>)', "", html, flags=re.S)
    html = re.sub(r'<li><a href="[^"]*/panel">[^<]*</a></li>', "", html)

    html = ATTR.sub(lambda m: f'{m.group(1)}="{public_href(m.group(2))}"', html)
    html = SRCSET.sub(
        lambda m: 'srcset="' + ", ".join(
            " ".join([public_href(item.split()[0]), *item.split()[1:]]) for item in m.group(1).split(",") if item.strip()
        ) + '"',
        html,
    )
    # indirizzi rimasti (calendari .ics, tag Open Graph): dominio pubblico al posto di quello locale
    html = html.replace("localhost%3A8000", urllib.parse.quote(PUBLIC_HOST, safe="")).replace("localhost:8000", PUBLIC_HOST)
    return html


def main(out):
    queue = ["/it", "/en", "/it/brand"]
    seen, failed, count = set(), [], 0

    if os.path.isdir(out):
        shutil.rmtree(out)
    os.makedirs(out)

    while queue:
        url = queue.pop(0)
        month = re.search(r"[?&]month=(\d{4}-\d{2})", url)
        if url in seen or SKIP.search(urllib.parse.urlsplit(url).path) or (month and not MONTHS[0] <= month.group(1) <= MONTHS[1]):
            continue
        seen.add(url)
        print(f"{len(seen):5d} {url}", flush=True)

        try:
            body, kind = fetch(url)
        except (urllib.error.URLError, OSError) as error:
            failed.append((url, str(error)))
            continue

        base_path = local_file(url)
        if kind == "text/html":
            html = body.decode("utf-8")
            for link in links(html):
                target = clean(link)
                if target:
                    queue.append(target)
            target_file = os.path.join(out, base_path.lstrip("/"), "index.html")
            body = rewrite(html).encode("utf-8")
        else:
            if kind == "text/css":
                css = body.decode("utf-8")
                for ref in CSS_URL.findall(css):
                    if not ref.startswith(("data:", "http")):
                        queue.append(urllib.parse.urljoin(url, ref))
            target_file = os.path.join(out, urllib.parse.urlsplit(base_path).path.lstrip("/"))

        os.makedirs(os.path.dirname(target_file), exist_ok=True)
        with open(target_file, "wb") as file:
            file.write(body)
        count += 1

    # GitHub Pages: niente Jekyll, e un 404 che rimanda alla home
    open(os.path.join(out, ".nojekyll"), "w").close()
    open(os.path.join(out, "index.html"), "w").write(
        f'<!doctype html><meta charset="utf-8"><meta name="robots" content="noindex"><title>IEA</title>'
        f'<meta http-equiv="refresh" content="0; url={BASE}/it/"><a href="{BASE}/it/">Ingegneria Edile-Architettura</a>'
    )
    print(f"{count} file salvati in {out}")
    for url, error in failed:
        print("NON SCARICATO:", url, error)


if __name__ == "__main__":
    main(os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else "../demo"))
