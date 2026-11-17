# Sito del corso IEA (Ingegneria Edile-Architettura, Università di Pisa)

Sito in Kirby 5 (PHP 8.4) in `sito/`. Piano e stato delle fasi: `docs/05-piano-sito-kirby.md`. Registro dei cambiamenti: `CHANGELOG.md`.
Il prototipo HTML di prima (`build.py`, `content/`, `site/` alla radice) non è più pubblicato.

## Come si lavora
- Server locale: `.claude/launch.json` ("sito-kirby"), porta 8000, dalla radice del repo con `-t sito`.
- Lingue: italiano (`/it`) e inglese (`/en`), indirizzi tradotti con il campo `Slug`.
- Contenuti in `sito/content/` come file `.it.txt` / `.en.txt` con blocchi JSON. Si modificano anche dal pannello, quindi i file del server sono la fonte dopo la messa online.
- La pagina brand (`/it/brand`) è un template (`sito/site/templates/brand.php`) con CSS in `sito/assets/css/brand.css`; unlisted e `noindex`.
- Mai rilanciare `sito/tools/migra-prototipo.php`: riscrive i contenuti modificati dal pannello.
- Segnaposto nei testi: `[DA VERIFICARE: …]` e `[DA FORNIRE: …]`, evidenziati in giallo/arancio.

## Regole
- Commit e push solo quando l'utente lo chiede. Il merge su `main` pubblica la demo su GitHub Pages.
- Credenziali: mai in chat. L'FTP di dev (`polo6adm_deviea`) ha la password nel portachiavi (`iea-dev-ftp`); si usa solo tramite `sito/tools/carica-dev.sh`, da rete di Ateneo/VPN.
- Dopo la messa online non caricare `content/` né `site/accounts/` sul server (cancellerebbe il lavoro della redazione).
- Non rinominare un file cambiando solo maiuscole/minuscole (macOS non le distingue).
- Le foto in `sito/content/brand/` vengono dal Figma e sembrano da banche immagini: il repository è pubblico, verificare le licenze.

## Da sapere
- Ambienti: `config.localhost.php`, `config.dev.iea.ing.unipi.it.php` (errori visibili, `noindex`), `config.iea.ing.unipi.it.php` (produzione, cache).
- Demo statica: `sito/tools/esporta-statico.py` (server locale acceso su 8000); nella demo mancano pannello, ricerca e guida; calendario limitato a luglio 2026 - giugno 2027.
- Figma delle brand guidelines: file `6nWRMqvR7alfUforvlqCyj` ("Variuous"), pagina IEA.
