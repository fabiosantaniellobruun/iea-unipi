# Sito del Corso di Laurea in Ingegneria Edile-Architettura

Sito definitivo realizzato con [Kirby](https://getkirby.com) 5, secondo il piano in [`../docs/05-piano-sito-kirby.md`](../docs/05-piano-sito-kirby.md).

## Requisiti
- PHP 8.4 con le estensioni richieste da Kirby
- Composer, solo sul computer dello sviluppatore

## Avvio in locale
```
composer install
php -S localhost:8000 kirby/router.php
```
- Sito: http://localhost:8000
- Pannello: http://localhost:8000/panel (al primo accesso chiede di creare l'utente amministratore)
- Guida di stile con tutti i componenti: http://localhost:8000/it/styleguide

## Struttura
| Cartella | Contenuto |
|---|---|
| `site/blueprints/` | Campi del pannello: pagine (`pages/`), blocchi (`blocks/`), ruolo della redazione (`users/`) |
| `site/templates/` | Un modello per tipo di pagina |
| `site/snippets/` | Componenti: layout, header, breadcrumb, menu di sezione, footer, blocchi |
| `site/config/` | Configurazione comune e per ambiente (`localhost`, `dev.iea.ing.unipi.it`, `iea.ing.unipi.it`) |
| `site/languages/` | Italiano e inglese, con le etichette fisse dell'interfaccia |
| `site/plugins/iea/` | Funzioni di supporto |
| `assets/css/` | `tokens.css` (colori, font, spaziature dal manuale di Ateneo, più il viola del corso), `fonts.css`, `base.css`, `layout.css`, `components.css`. Nessuna build |
| `assets/IEA Brand/` | Marchi del corso (IEA, orizzontale e verticale) e marchio di Ateneo |
| `assets/fonts/` | Inter e Titillium Web (SIL Open Font License) |
| `content/` | Contenuti: una cartella per pagina, un file per lingua |

`kirby/` e `vendor/` non sono nel repository: si installano con `composer install`.

## Contenuti
Durante lo sviluppo `content/` è nel repository, con contenuti di prova e poi con quelli migrati dal prototipo. Dopo la messa online i contenuti vivono solo sul server e vengono esclusi dal repository.

## Pubblicazione
Si caricano via FTPS (con VPN di Ateneo) le cartelle `kirby/`, `vendor/`, `site/`, `assets/` e i file `index.php` e `.htaccess`. `content/` si carica solo la prima volta e al passaggio in produzione: poi è gestita dal pannello.

## Da completare
- Licenza di Kirby: da acquistare prima della messa online (`site/license.txt`).
