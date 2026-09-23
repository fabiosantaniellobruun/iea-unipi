# Prototipo IEA: navigazione e contenuti

Prototipo del nuovo sito del Corso di Laurea Magistrale a ciclo unico in Ingegneria Edile-Architettura dell'Università di Pisa. Serve a valutare **architettura, flussi e testi**: la grafica è volutamente minima.

## Come vederlo
Apri `site/index.html` nel browser. I link funzionano anche aprendo i file dal disco.

## Come modificarlo
- **Testi:** file Markdown in `content/`, uno per pagina. In testa a ogni file: `title` (titolo), `lead` (frase introduttiva), `placeholder: true` per le pagine da completare.
- **Menu, sottomenu, breadcrumb e mappa del sito:** tutti da `content/_nav.yml`.
- **Bacheca:** un file per post in `content/bacheca/post/`, con `data`, `categoria` (`avvisi`, `eventi`, `bandi`), e facoltativi `scadenza`, `data_evento`, `luogo`, `evidenza: true`, `sezione: archivio`, `sommario`, `originale`. Le liste, l'archivio e i blocchi della home si generano da soli.
- **Link interni:** scrivili assoluti, per esempio `/iscriversi/primo-anno/`; la build li rende relativi.
- **Segnaposto:** `[DA VERIFICARE: …]` per informazioni dubbie o superate, `[DA FORNIRE: …]` per contenuti mancanti. Nel prototipo compaiono evidenziati in rosso e in blu.

Dopo ogni modifica rigenera il sito:

```
pip install -r requirements.txt
python3 build.py
```

## Documenti di progetto
| File | Contenuto |
|---|---|
| [docs/01-inventario-e-audit.md](docs/01-inventario-e-audit.md) | Inventario del sito attuale e problemi rilevati |
| [docs/02-architettura.md](docs/02-architettura.md) | Nuova architettura, flussi, tabella di corrispondenza |
| [docs/linee-guida-redazionali.md](docs/linee-guida-redazionali.md) | Regole di scrittura dal manuale di immagine coordinata |
| [docs/03-pagine-campione.md](docs/03-pagine-campione.md) | Prime pagine campione con note di revisione |
| [docs/04-bacheca.md](docs/04-bacheca.md) | Mappa di tutti i post della bacheca e dei contenuti nascosti |
| [docs/fonte/](docs/fonte/) | Testi estratti dal sito attuale e script di estrazione |
