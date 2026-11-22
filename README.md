<p align="center">
  <img src="docs/cover.jpg" alt="IEA, Università di Pisa: «Il nuovo sito del corso», con lo schema della home del sito (menu, barra LIVE, riquadro di apertura, tre card, avviso in basso) e le etichette In sviluppo, Kirby 5, Italiano · English, PHP 8.4" width="880">
</p>

# Sito del corso di Ingegneria Edile-Architettura

Sito del Corso di Laurea Magistrale a ciclo unico in Ingegneria Edile-Architettura dell'Università di Pisa. È in sviluppo con [Kirby](https://getkirby.com) 5 (PHP 8.4, contenuti in file, nessun database), in italiano e in inglese, con un pannello per la redazione del corso.

**Demo:** <https://fabiosantaniellobruun.github.io/iea-unipi/>. È una copia statica del sito Kirby, rigenerata a ogni push su `main`: non ha pannello, ricerca né guida per la redazione, e il calendario copre solo luglio 2026 - giugno 2027. Serve a mostrare il lavoro in corso.

## Stato

Il piano per fasi è in [docs/05-piano-sito-kirby.md](docs/05-piano-sito-kirby.md); il registro dei cambiamenti in [CHANGELOG.md](CHANGELOG.md). Al 17 novembre 2026:

| Fase | Stato |
|---|---|
| 0-6: impianto, marchio, modello dei contenuti, modelli di pagina, migrazione dei contenuti, ricerca e calendario, pannello per la redazione | Fatte, in locale |
| 7: verifiche finali (accessibilità WCAG 2.1 AA, browser, revisione dei contenuti) | Da fare su dev; in locale fatti il controllo automatico con axe e quello di link e allegati |
| 8: messa online (`dev.iea.ing.unipi.it`, poi `iea.ing.unipi.it`) | In preparazione: manca il primo caricamento su dev |
| 9: consegna (account, documentazione, formazione) | Da fare |

## Cosa fa

- **Bacheca** con tre categorie (Avvisi, Eventi, Bandi e opportunità) e archivio che si aggiorna da solo.
- **Calendario** navigabile per mese, con feed `.ics` per l'iscrizione da Apple Calendario, Outlook o Google Calendar.
- **Ricerca** interna, che trova anche senza accenti e con singolare e plurale.
- **Barra ORA/LIVE** per gli eventi del giorno e notifiche con le ultime dalla bacheca: si aggiornano dal pannello, senza nuovo caricamento.
- **Pannello per la redazione** con un ruolo dedicato, guida illustrata e recupero della password.
- **Italiano e inglese**, con indirizzi tradotti.
- Nessun analytics, banner cookie o modulo di contatto.

## Provarlo in locale

Servono PHP 8.4 e Composer.

```
cd sito
composer install
php -d upload_max_filesize=20M -d post_max_size=24M -d memory_limit=256M -S localhost:8000 kirby/router.php
```

Il sito è su <http://localhost:8000>, il pannello su <http://localhost:8000/panel> (al primo accesso chiede di creare l'utente amministratore). Struttura, tipi di pagina, contenuti, ricerca e manutenzione sono descritti in [sito/README.md](sito/README.md).

## Come è fatto il repository

| Percorso | Contenuto |
|---|---|
| `sito/` | Il sito Kirby: codice, grafica, contenuti e strumenti. `kirby/` e `vendor/` non sono nel repository: si installano con `composer install` |
| `docs/` | Documenti di progetto (vedi sotto) |
| `.github/workflows/pages.yml` | Genera la demo statica con `sito/tools/esporta-statico.py` e la pubblica su GitHub Pages a ogni push su `main`; si può avviare anche a mano dalla scheda *Actions* |
| `CHANGELOG.md` | Registro dei cambiamenti, dal più recente |
| `build.py`, `content/`, `site/`, `templates/` | Il prototipo HTML di settembre, che ha fissato architettura e testi. Non è più pubblicato; resta come riferimento e come sorgente della migrazione iniziale dei contenuti |

Dopo la messa online i contenuti vivono sul server e si modificano dal pannello: il codice si carica via FTPS dalla rete di Ateneo (VPN) con `sito/tools/carica-dev.sh`, mai la cartella `content/`.

## Documenti di progetto

| File | Contenuto |
|---|---|
| [docs/01-inventario-e-audit.md](docs/01-inventario-e-audit.md) | Inventario del sito attuale e problemi rilevati |
| [docs/02-architettura.md](docs/02-architettura.md) | Nuova architettura, flussi, tabella di corrispondenza |
| [docs/linee-guida-redazionali.md](docs/linee-guida-redazionali.md) | Regole di scrittura dal manuale di immagine coordinata |
| [docs/03-pagine-campione.md](docs/03-pagine-campione.md) | Prime pagine campione con note di revisione |
| [docs/04-bacheca.md](docs/04-bacheca.md) | Mappa di tutti i post della bacheca e dei contenuti nascosti |
| [docs/revisione-contenuti.xlsx](docs/revisione-contenuti.xlsx) | Foglio per le segnalazioni del cliente, una riga per pagina |
| [docs/05-piano-sito-kirby.md](docs/05-piano-sito-kirby.md) | Piano d'azione e stato delle fasi del sito con Kirby |
| [docs/06-stile-unipi.md](docs/06-stile-unipi.md) | Analisi dello stile di unipi.it: colori, tipografia, componenti (schermate in [docs/stile/](docs/stile/)) |
| [docs/fonte/](docs/fonte/) | Testi estratti dal sito attuale e script di estrazione |

Nei testi del sito, `[DA VERIFICARE: …]` segnala un'informazione dubbia o superata e `[DA FORNIRE: …]` un contenuto mancante; sul sito compaiono evidenziati finché la redazione non li risolve.
