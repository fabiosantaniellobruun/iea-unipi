# Changelog

Registro dei cambiamenti del progetto, dal più recente. Si aggiorna a ogni commit significativo.
Il piano e lo stato delle fasi sono in [docs/05-piano-sito-kirby.md](docs/05-piano-sito-kirby.md).

## 22 novembre 2026
- **Brand guidelines, sezione Applicazioni**: mockup di carta intestata, biglietti, cancelleria, sito web (desktop e mobile), poster, brochure (dal PDF di stampa, in WebP), newsletter, post social e reel, firma email. Rimossi merchandising, note provvisorie e tag WIP.
- **Primo caricamento su dev.iea.ing.unipi.it** (cartella `/web`, FTPS con certificato autofirmato: `VERIFICA_CERTIFICATO=no`). Il sito non risponde ancora: il virtual host è spento dal 17 novembre, chiesto al reparto.
- `carica-dev.sh`: nuovi modi `elenco` (cartelle sul server), `pulisci` (cancella i file di controllo e `version.php`) e `log` (scarica i log, sola lettura).

## 17 novembre 2026 (sera)
- **Barra ORA/LIVE** in alto a sinistra (il giorno di un evento con data, con frecce se sono più di uno) e **toast** delle ultime dalla bacheca in basso a sinistra. I dati vengono da `/it/live.json` (`site/plugins/iea/live.php`, `assets/js/live.js`): si aggiornano dal pannello senza deploy. In sviluppo `?today=AAAA-MM-GG` simula un giorno.
- **Pagina 404** con foto a tutta larghezza (Unsplash, Rufat Mammadov), testo e ricerca.
- **Pagina Crediti** (`/it/crediti`, nel footer): chi ha fatto il sito, tecnologie, strumenti, font e licenze delle immagini.
- **Bacheca**: importato il post «ATI Project Integrated Design Challenge 2026» dal sito attuale.
- Brand guidelines: colonna «Esempio» con i colori veri nella tabella dei contrasti.
- La demo statica include `live.json`.

## 17 novembre 2026
- Piano delle fasi 7-9 e punti aperti aggiornati allo stato attuale.
- **Brand guidelines** (`/it/brand`, non linkata, `noindex`): documento dal file Figma "Variuous" con indice fisso a sinistra, tag WIP sulle sezioni da completare; 34 foto dal Figma (da verificare le licenze); sezione Format con esempi di composizione.
- **Demo statica su GitHub Pages**: `sito/tools/esporta-statico.py` salva il sito come HTML; `pages.yml` la genera in CI a ogni push su `main`. Sostituisce il vecchio prototipo HTML. Include la pagina brand.
- **Figma aggiornato**: panoramica del marchio, scala dei titoli del sito, firma email in Inter con refusi corretti, contrasti, trattamenti delle foto, testo dei rapporti con il marchio, nuova pagina Don'ts (Marchio 7).
- **Script di caricamento** `sito/tools/carica-dev.sh` (lftp, password nel portachiavi): modi `controllo`, `primo`, `aggiorna`; più i file di controllo di PHP in `sito/tools/controllo/`. Non ancora provato: serve la rete di Ateneo.
- `.gitignore`: la licenza di Kirby è `site/config/.license`.

## 29 settembre 2026
- **Home**: le tre card degli accessi occupano tutta la larghezza e hanno una foto (ritaglio 3:2 sul punto di interesse).
- **Contenuti verificati sul portale di Ateneo**: piano di studi (3° e 4° anno, insegnamenti a scelta), graduatorie e OFA nella pagina del primo anno, email in Contatti, dati di qualità e tutorato.

## 25 settembre 2026
- **Sito Kirby**, fasi 0-6: impianto e pagina di prova della grafica; marchio del corso; modello dei contenuti e ruolo redazione; modelli di pagina, bacheca, favicon e manutenzione; migrazione dei contenuti e traduzione inglese; ricerca e calendario con file `.ics`; pannello per la redazione, guida `/admin-help` e recupero password.
- Piano: Kirby al posto di Astro, accesso FTP solo da VPN.

## 23-24 settembre 2026
- Audit del sito attuale, architettura, linee guida redazionali, prototipo HTML con testi riscritti, foglio di revisione per il cliente, pubblicazione del prototipo su GitHub Pages.
