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
| `site/blueprints/` | Campi del pannello: pagine (`pages/`), blocchi ammessi (`blocks/`), tipi di file (`files/`), campi e sezioni riusati (`fields/`, `sections/`), ruolo della redazione (`users/`) |
| `site/models/` | Metodi in più per alcuni tipi di pagina (per esempio la categoria di un post) |
| `site/templates/` | Un modello per tipo di pagina |
| `site/controllers/` | Elenchi calcolati: home (avvisi, in evidenza, ultime), bacheca, categorie, archivio |
| `site/snippets/` | Componenti: layout, header, breadcrumb, menu di sezione, footer, card, elenchi di post, paginazione, immagini, allegati, blocchi, pagina di manutenzione |
| `site/config/` | Configurazione comune e per ambiente (`localhost`, `dev.iea.ing.unipi.it`, `iea.ing.unipi.it`) |
| `site/languages/` | Italiano e inglese, con le etichette fisse dell'interfaccia |
| `site/plugins/iea/` | Funzioni di supporto |
| `assets/css/` | `tokens.css` (colori, font, spaziature dal manuale di Ateneo, più il viola del corso), `fonts.css`, `base.css`, `layout.css`, `components.css`. Nessuna build |
| `assets/IEA Brand/` | Marchi del corso (IEA, orizzontale e verticale) e marchio di Ateneo |
| `assets/fonts/` | Inter e Titillium Web (SIL Open Font License) |
| `assets/favicon.svg`, `assets/apple-touch-icon.png`, `favicon.ico` | Favicon: la IEA del marchio in bianco su viola |
| `tools/` | Script di migrazione dal prototipo e traduzione inglese; non va sul server |
| `content/` | Contenuti: una cartella per pagina, un file per lingua |

`kirby/` e `vendor/` non sono nel repository: si installano con `composer install`.

## Tipi di pagina
| Tipo | Uso |
|---|---|
| `home` | Presentazione, accessi per chi visita il sito, il corso in sintesi, contenuti in evidenza |
| `sezione` | Il corso, Iscriversi, Studiare: introduzione e card delle pagine della sezione |
| `default` | Pagina di contenuto a blocchi (anche Laurearsi, Contatti e le pagine del footer) |
| `bacheca` | Elenco di tutti i post; da qui la redazione scrive i post |
| `bacheca-categoria`, `bacheca-archivio` | Avvisi, Eventi, Bandi e opportunità, Archivio: elenchi generati |
| `post` | Post della bacheca. Pubblicato vuol dire "unlisted": visibile sul sito ma non nei menu |
| `mappa` | Mappa del sito, generata dal menu e dai link del footer |
| `error` | Pagina non trovata (404), con i link per ripartire |

Un post resta in bacheca se è stato pubblicato nell'ultimo anno o se il suo evento o la sua scadenza non sono ancora passati; poi passa da solo in Archivio. In home ogni post compare in un solo blocco: avvisi, poi in evidenza (scelti dalla home), poi ultime dalla bacheca.

Il ruolo `redazione` modifica i testi di tutte le pagine e gestisce i post; non crea, sposta, rinomina o cancella pagine, e non tocca impostazioni e utenti.

## Contenuti
I contenuti vengono dal prototipo (`../content`, Markdown) con lo script di migrazione:
```
php tools/migra-prototipo.php
```
- Riscrive in `content/` la home, le pagine del menu e del footer e i post della bacheca; non tocca le impostazioni del sito, la pagina d'errore e la guida di stile.
- La traduzione inglese è in `tools/prototipo-en/`, con la stessa struttura del prototipo; nei link si possono lasciare le ancore italiane.
- Gli allegati del sito attuale si scaricano una volta sola in `tools/.cache/` (esclusa da Git) e vanno nella cartella della prima pagina che li usa. Il loro indirizzo stabile è `/it/pagina/nome-file.pdf` (opzione `content.fileRedirects`).
- Le segnalazioni `[DA VERIFICARE]` e `[DA FORNIRE]` restano nei testi e sul sito sono evidenziate in giallo e arancione, finché la redazione non le risolve dal pannello.
- Lo script conserva gli Uuid di pagine e file: si può rilanciare. Dopo che la redazione ha iniziato a lavorare nel pannello, però, rilanciarlo sovrascriverebbe le sue modifiche.

Durante lo sviluppo `content/` è nel repository, con contenuti di prova e poi con quelli migrati dal prototipo. Dopo la messa online i contenuti vivono solo sul server e vengono esclusi dal repository.

## Pubblicazione
Si caricano via FTPS (con VPN di Ateneo) le cartelle `kirby/`, `vendor/`, `site/`, `assets/` e i file `index.php` e `.htaccess`. `tools/` resta sul computer dello sviluppatore. Insieme a `index.php` e `.htaccess` va caricato anche `favicon.ico`.

## Manutenzione
Durante un aggiornamento si può mostrare ai visitatori una pagina "Sito in manutenzione" (codice 503): nel file di configurazione dell'ambiente (`site/config/config.<dominio>.php`) si aggiunge `'iea.maintenance' => true`. Chi ha fatto l'accesso al pannello continua a vedere il sito normalmente. A lavoro finito si toglie la riga. `content/` si carica solo la prima volta e al passaggio in produzione: poi è gestita dal pannello.

## Da completare
- Licenza di Kirby: da acquistare prima della messa online (`site/license.txt`).
