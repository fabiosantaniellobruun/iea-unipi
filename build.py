#!/usr/bin/env python3
"""Genera il prototipo HTML statico da content/ in site/.

Uso:  python3 build.py
Dipendenze: markdown, pyyaml (vedi requirements.txt)

- Menu, sottomenu, breadcrumb e mappa del sito vengono da content/_nav.yml.
- Ogni pagina è un file Markdown con intestazione "chiave: valore" (title, lead, placeholder).
- I post della bacheca stanno in content/bacheca/post/ e generano liste,
  archivio e blocchi della home ({{in_evidenza}}, {{avvisi}}, {{ultime_notizie}}).
- I link interni si scrivono assoluti ("/iscriversi/primo-anno/") e vengono
  resi relativi, così il sito funziona anche aprendo i file dal disco.
"""
import datetime as dt
import html
import os
import re
import shutil

import markdown
import yaml

ROOT = os.path.dirname(os.path.abspath(__file__))
CONTENT = os.path.join(ROOT, "content")
OUT = os.path.join(ROOT, "site")
TODAY = dt.date(2026, 9, 23)  # data di riferimento del prototipo
MESI = "gennaio febbraio marzo aprile maggio giugno luglio agosto settembre ottobre novembre dicembre".split()
CATEGORIE = {
    "avvisi": ("Avvisi", "bacheca/avvisi"),
    "eventi": ("Eventi", "bacheca/eventi"),
    "bandi": ("Bandi e opportunità", "bacheca/bandi-e-opportunita"),
}


def data_it(d):
    return f"{d.day} {MESI[d.month - 1]} {d.year}"


def read_md(path):
    text = open(path, encoding="utf-8").read()
    meta = {}
    if text.startswith("---"):
        _, fm, text = text.split("---", 2)
        # intestazione "chiave: valore", una per riga; il valore può contenere ":"
        for line in fm.strip().splitlines():
            k, v = line.split(":", 1)
            v = v.strip()
            meta[k.strip()] = {"true": True, "false": False}.get(v, v)
    return meta, text


def url_of(page):
    """'il-corso/in-breve' -> 'il-corso/in-breve/'; 'il-corso/index' -> 'il-corso/'; 'index' -> ''."""
    if page == "index":
        return ""
    if page.endswith("/index"):
        return page[: -len("index")]
    return page + "/"


def out_file(url):
    return os.path.join(OUT, url, "index.html")


def rel(from_url, to_url):
    """Link relativo da una pagina a un'altra, con index.html esplicito (funziona da file://)."""
    depth = from_url.count("/")
    anchor = ""
    if "#" in to_url:
        to_url, anchor = to_url.split("#", 1)
        anchor = "#" + anchor
    return "../" * depth + to_url + "index.html" + anchor


def md_to_html(text):
    return markdown.markdown(text, extensions=["tables", "attr_list", "sane_lists", "toc", "md_in_html"])


def post_process(body, url):
    # link interni assoluti -> relativi
    body = re.sub(r'href="/([^"]*)"', lambda m: f'href="{rel(url, m.group(1))}"', body)
    # link esterni: si aprono nella stessa finestra ma sono marcati
    body = re.sub(r'<a href="(https?://[^"]+)"', r'<a class="ext" href="\1"', body)
    # segnaposto redazionali evidenziati
    body = re.sub(r"\[(DA VERIFICARE|DA FORNIRE)([^\]]*)\]", r'<mark class="\1">[\1\2]</mark>', body)
    body = body.replace('class="DA VERIFICARE"', 'class="verificare"').replace('class="DA FORNIRE"', 'class="fornire"')
    return body


# ---------------------------------------------------------------- navigazione
nav = yaml.safe_load(open(os.path.join(CONTENT, "_nav.yml"), encoding="utf-8"))
parents = {}  # page -> sezione
for sec in nav["menu"]:
    parents[sec["page"]] = sec
    for ch in sec.get("children", []):
        parents[ch["page"]] = sec


def label_for(page):
    for sec in nav["menu"]:
        if sec["page"] == page:
            return sec["label"]
        for ch in sec.get("children", []):
            if ch["page"] == page:
                return ch["label"]
    for f in nav["footer"]:
        if f["page"] == page:
            return f["label"]
    return None


def render_menu(url, current_sec):
    items = []
    for sec in nav["menu"]:
        cls = ' class="attivo"' if current_sec is sec else ""
        items.append(f'<li><a{cls} href="{rel(url, url_of(sec["page"]))}">{sec["label"]}</a></li>')
    return "<ul>" + "".join(items) + "</ul>"


def render_subnav(url, page, sec):
    if not sec or not sec.get("children"):
        return ""
    items = []
    for ch in [{"label": "Panoramica", "page": sec["page"]}] + sec["children"]:
        cur = ' aria-current="page"' if ch["page"] == page else ""
        items.append(f'<li><a{cur} href="{rel(url, url_of(ch["page"]))}">{ch["label"]}</a></li>')
    return f'<nav class="subnav" aria-label="{sec["label"]}"><p>{sec["label"]}</p><ul>{"".join(items)}</ul></nav>'


def render_breadcrumb(url, page, sec, title, extra=None):
    if page == "index":
        return ""
    crumbs = [f'<a href="{rel(url, "")}">Home</a>']
    if sec and sec["page"] != page:
        crumbs.append(f'<a href="{rel(url, url_of(sec["page"]))}">{sec["label"]}</a>')
    if extra:
        crumbs.append(f'<a href="{rel(url, url_of(extra[1]))}">{extra[0]}</a>')
    crumbs.append(f"<span>{html.escape(title)}</span>")
    return '<nav class="breadcrumb" aria-label="Percorso">' + " › ".join(crumbs) + "</nav>"


def render_footer(url):
    links = " · ".join(f'<a href="{rel(url, url_of(f["page"]))}">{f["label"]}</a>' for f in nav["footer"])
    return f"""<footer>
<p><strong>Corso di Laurea Magistrale a ciclo unico in Ingegneria Edile-Architettura</strong><br>
Università di Pisa · Dipartimento di Ingegneria dell'Energia, dei Sistemi, del Territorio e delle Costruzioni (DESTEC)</p>
<p>{links}</p>
<p>Social: <a class="ext" href="https://www.facebook.com/ieapisa/">Facebook</a> · <a class="ext" href="https://www.instagram.com/ieapisa/">Instagram</a> · <a class="ext" href="https://www.linkedin.com/in/iea-pisa-70b98a143/">LinkedIn</a></p>
</footer>"""


TEMPLATE = open(os.path.join(ROOT, "templates", "page.html"), encoding="utf-8").read()


def write_page(url, page, title, lead, body, extra_crumb=None):
    sec = parents.get(page)
    if extra_crumb and not sec:
        sec = parents.get(extra_crumb[1])
    body = post_process(body, url)
    lead_html = f'<p class="lead">{html.escape(lead)}</p>' if lead else ""
    out = (TEMPLATE
           .replace("{{title}}", html.escape(title))
           .replace("{{css}}", rel(url, "")[: -len("index.html")] + "assets/style.css")
           .replace("{{home}}", rel(url, ""))
           .replace("{{menu}}", render_menu(url, sec))
           .replace("{{breadcrumb}}", render_breadcrumb(url, page, sec, title, extra_crumb))
           .replace("{{subnav}}", render_subnav(url, page, sec))
           .replace("{{lead}}", lead_html)
           .replace("{{content}}", body)
           .replace("{{footer}}", render_footer(url))
           .replace("{{layout}}", "con-subnav" if (sec and sec.get("children")) else "senza-subnav"))
    os.makedirs(os.path.dirname(out_file(url)), exist_ok=True)
    open(out_file(url), "w", encoding="utf-8").write(out)


# ---------------------------------------------------------------- bacheca
def load_posts():
    posts = []
    d = os.path.join(CONTENT, "bacheca", "post")
    for fn in sorted(os.listdir(d)):
        if not fn.endswith(".md"):
            continue
        meta, text = read_md(os.path.join(d, fn))
        meta["slug"] = fn[:-3]
        meta["url"] = f"bacheca/post/{meta['slug']}/"
        meta["text"] = text
        meta["date"] = meta["data"] if isinstance(meta["data"], dt.date) else dt.date.fromisoformat(str(meta["data"]))
        posts.append(meta)
    posts.sort(key=lambda p: p["date"], reverse=True)
    return posts


def post_item(p, from_url, show_cat=True):
    cat = CATEGORIE[p["categoria"]][0]
    info = [data_it(p["date"])]
    if show_cat:
        info.append(cat)
    if p.get("scadenza"):
        s = p["scadenza"] if isinstance(p["scadenza"], dt.date) else dt.date.fromisoformat(str(p["scadenza"]))
        info.append(("scaduto il " if s < TODAY else "scadenza ") + data_it(s))
    sub = f'<br><span class="sommario">{html.escape(p["sommario"])}</span>' if p.get("sommario") else ""
    return (f'<li><span class="meta">{" · ".join(info)}</span><br>'
            f'<a href="{rel(from_url, p["url"])}">{html.escape(p["title"])}</a>{sub}</li>')


def post_list(posts, from_url, show_cat=True):
    if not posts:
        return "<p>Nessun contenuto al momento.</p>"
    return '<ul class="post-list">' + "".join(post_item(p, from_url, show_cat) for p in posts) + "</ul>"


def attivo(p):
    """Un post è attivo se il suo evento o la sua scadenza non sono passati, o se è degli ultimi 12 mesi."""
    ref = p.get("scadenza") or p.get("data_evento")
    if ref:
        ref = ref if isinstance(ref, dt.date) else dt.date.fromisoformat(str(ref))
        return ref >= TODAY - dt.timedelta(days=30)
    return p["date"] >= TODAY - dt.timedelta(days=365)


# ---------------------------------------------------------------- build
def build():
    if os.path.exists(OUT):
        shutil.rmtree(OUT)
    os.makedirs(os.path.join(OUT, "assets"))
    shutil.copy(os.path.join(ROOT, "templates", "style.css"), os.path.join(OUT, "assets", "style.css"))
    # prototipo: fuori dai motori di ricerca
    shutil.copy(os.path.join(ROOT, "templates", "robots.txt"), os.path.join(OUT, "robots.txt"))

    posts = load_posts()
    recenti = [p for p in posts if p.get("sezione") != "archivio"]
    # home: ogni post compare in un solo blocco (avvisi > in evidenza > ultime)
    home_avvisi = [p for p in recenti if p["categoria"] == "avvisi" and attivo(p)][:3]
    home_evidenza = [p for p in recenti if p.get("evidenza") and p not in home_avvisi][:3]
    home_ultime = [p for p in recenti if p not in home_avvisi and p not in home_evidenza][:4]
    blocks = {
        "{{in_evidenza}}": lambda u: post_list(home_evidenza, u),
        "{{avvisi}}": lambda u: post_list(home_avvisi, u, False),
        "{{ultime_notizie}}": lambda u: post_list(home_ultime, u),
        "{{bacheca_avvisi}}": lambda u: post_list([p for p in recenti if p["categoria"] == "avvisi"], u, False),
        "{{bacheca_eventi}}": lambda u: post_list([p for p in recenti if p["categoria"] == "eventi"], u, False),
        "{{bacheca_bandi}}": lambda u: post_list([p for p in recenti if p["categoria"] == "bandi"], u, False),
        "{{bacheca_tutti}}": lambda u: post_list(recenti, u),
        "{{bacheca_archivio}}": lambda u: archivio_html(posts, u),
        "{{mappa}}": lambda u: mappa_html(u),
    }

    pages = []
    for dirpath, _, files in os.walk(CONTENT):
        for fn in files:
            if not fn.endswith(".md") or "/bacheca/post" in dirpath.replace(os.sep, "/"):
                continue
            page = os.path.relpath(os.path.join(dirpath, fn), CONTENT)[:-3].replace(os.sep, "/")
            pages.append(page)

    for page in sorted(pages):
        meta, text = read_md(os.path.join(CONTENT, page + ".md"))
        url = url_of(page)
        body = md_to_html(text)
        for k, fn in blocks.items():
            if k in body:
                body = body.replace(f"<p>{k}</p>", fn(url)).replace(k, fn(url))
        if meta.get("placeholder"):
            body = '<p class="placeholder-note">Pagina prevista nella nuova architettura, da completare.</p>' + body
        write_page(url, page, meta.get("title", label_for(page) or page), meta.get("lead", ""), body)

    for p in posts:
        url = p["url"]
        info = [f"<strong>Pubblicato:</strong> {data_it(p['date'])}", f"<strong>Categoria:</strong> {CATEGORIE[p['categoria']][0]}"]
        for key, lab in (("data_evento", "Data"), ("luogo", "Luogo"), ("scadenza", "Scadenza")):
            if p.get(key):
                v = p[key]
                v = data_it(v if isinstance(v, dt.date) else dt.date.fromisoformat(str(v))) if key != "luogo" else html.escape(str(v))
                info.append(f"<strong>{lab}:</strong> {v}")
        head = '<p class="post-meta">' + "<br>".join(info) + "</p>"
        foot = f'<p class="fonte">Post originale: <a class="ext" href="{p["originale"]}">{p["originale"]}</a></p>' if p.get("originale") else ""
        cat = CATEGORIE[p["categoria"]]
        write_page(url, cat[1], p["title"], p.get("sommario", ""), head + md_to_html(p["text"]) + foot, extra_crumb=cat)

    print(f"{len(pages)} pagine, {len(posts)} post -> {os.path.relpath(OUT, ROOT)}/")


def archivio_html(posts, u):
    arch = [p for p in posts if p.get("sezione") == "archivio"]
    out, anno = [], None
    for p in arch:
        if p["date"].year != anno:
            if anno is not None:
                out.append("</ul>")
            anno = p["date"].year
            out.append(f'<h2>{anno}</h2><ul class="post-list">')
        out.append(post_item(p, u))
    if out:
        out.append("</ul>")
    return "".join(out) or "<p>Archivio vuoto.</p>"


def mappa_html(u):
    out = [f'<ul><li><a href="{rel(u, "")}">Home</a></li>']
    for sec in nav["menu"]:
        out.append(f'<li><a href="{rel(u, url_of(sec["page"]))}">{sec["label"]}</a>')
        if sec.get("children"):
            out.append("<ul>" + "".join(f'<li><a href="{rel(u, url_of(c["page"]))}">{c["label"]}</a></li>' for c in sec["children"]) + "</ul>")
        out.append("</li>")
    for f in nav["footer"]:
        out.append(f'<li><a href="{rel(u, url_of(f["page"]))}">{f["label"]}</a></li>')
    return "".join(out) + "</ul>"


if __name__ == "__main__":
    build()
