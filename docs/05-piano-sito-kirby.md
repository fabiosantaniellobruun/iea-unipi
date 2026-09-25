# Piano d'azione: nuovo sito del Corso di Laurea in Ingegneria Edile-Architettura

Piano per sviluppare il sito definitivo con **Kirby**, a partire dal prototipo (`https://fabiosantaniellobruun.github.io/iea-unipi/`).

## Decisioni prese

| Tema | Scelta |
|---|---|
| Sito | Corso di Laurea Magistrale a ciclo unico in Ingegneria Edile-Architettura |
| Traccia per architettura e testi | Prototipo in questo repository, con i testi riscritti |
| Tecnologia | Kirby (CMS senza database, contenuti in file) sul server di Ateneo |
| Pubblicazione dei contenuti | Il cliente usa il pannello di Kirby sul sito stesso: nessun servizio esterno, nessuna build, nessun cron |
| Pubblicazione del codice | Solo lo sviluppatore, via FTPS con VPN di Ateneo |
| Server | Apache con `.htaccess` e `mod_rewrite`, PHP 8.4, cartella `/var/www/clients/client5/web20/web` |
| Ambienti | `dev.iea.ing.unipi.it` come sito di lavoro da mostrare al cliente; a fine lavoro migrazione su `iea.ing.unipi.it`. DNS a cura dell'IT |
| Allegati e immagini | Scaricati dal sito attuale e ospitati nel nuovo |
| Lingue | Italiano e inglese |
| Pagine senza contenuto | Online come pagine vuote |
| Grafica | Colori, font e tono visivo di unipi.it, con componenti adattati a un sito più piccolo |
| Accessibilità | WCAG 2.1 AA |
| Funzioni | Calendario e ricerca; niente analytics, banner cookie né modulo contatti |
| Utenti del pannello | Un solo ruolo per il cliente; lo sviluppatore ha il ruolo di amministratore |
| Scadenza | Nessuna |

### Dipendenze dopo la consegna
Il sito dipende solo dal server di Ateneo. Nessun account esterno, nessun servizio di terze parti, nessuna operazione pianificata.

Manutenzione prevista: aggiornamenti di Kirby qualche volta l'anno, per sicurezza. Si fanno sostituendo la cartella `kirby/` via FTPS; i contenuti non vengono toccati.

---

## Come si costruisce il frontend

Kirby genera le pagine sul server con PHP, a ogni richiesta, con una cache che le rende veloci quanto un sito statico. **Non c'è una fase di build:** i file che si caricano sul server sono gli stessi che si scrivono.

### Struttura dei file
```
site/
  blueprints/     campi del pannello per ogni tipo di pagina (YAML)
    pages/        home, sezione, pagina, bacheca, notizia, evento, calendario, ricerca, contatti
    blocks/       blocchi di contenuto ammessi nelle pagine
    users/        ruolo "redazione" per il cliente
  templates/      un modello PHP per ogni tipo di pagina
  snippets/       componenti riutilizzabili: header, menu, breadcrumb, card, footer, blocchi
  controllers/    logica separata dal markup: liste, filtri, paginazione, calendario
  config/         configurazione generale e per ambiente (dev e produzione)
  languages/      italiano e inglese
assets/
  css/            fogli di stile, senza preprocessori
  js/             poche righe di JavaScript per menu mobile e parti espandibili
  fonts/          Inter e Titillium Web ospitati sul server
content/          i contenuti, un file di testo per pagina e per lingua, con gli allegati accanto
kirby/            il CMS, da non modificare
```

### Dal prototipo ai modelli di pagina
Ogni tipo di pagina del prototipo diventa un **blueprint** (i campi che il cliente vede nel pannello) e un **template** (l'HTML generato).

| Tipo di pagina | Blueprint | Template |
|---|---|---|
| Home | Frase di presentazione, scheda del corso, scelta dei contenuti in evidenza | Presentazione, accessi per destinatari, avvisi, in evidenza, ultime notizie |
| Indice di sezione | Introduzione | Introduzione e card delle sottopagine, generate in automatico |
| Pagina di contenuto | Titolo, frase introduttiva, corpo a blocchi | Titolo, introduzione, blocchi, menu di sezione, breadcrumb |
| Bacheca e categorie | Nessun campo: liste generate | Liste filtrate per categoria, paginazione, archivio per anno |
| Notizia, evento, bando | Campi fissi, definiti nel secondo giro di istruzioni | Dettaglio con data, luogo, scadenza, allegati |
| Calendario | Nessun campo | Vista mensile e lista dei prossimi eventi, file `.ics` |
| Ricerca | Nessun campo | Risultati della ricerca interna |
| Contatti | Campi strutturati (persone, uffici, orari) | Schede contatto |

### Contenuti a blocchi controllati
Le pagine istituzionali usano un campo a blocchi limitato ai soli blocchi previsti:
- testo, titolo, elenco, tabella, immagine, allegato;
- avviso ("Ricorda:");
- scheda informativa (le tabelle a due colonne del prototipo).

Ogni blocco ha il suo snippet con lo stile unipi.it. Il cliente può scrivere e riordinare i contenuti, ma non può rompere la grafica né inserire HTML libero.

### Componenti (snippets)
Header con menu principale, menu mobile, breadcrumb, menu di sezione, card, lista di notizie, paginazione, blocchi di contenuto, footer. Ogni snippet produce HTML semantico e accessibile:
- navigazione da tastiera;
- elemento selezionato visibile;
- salto al contenuto;
- `aria-current` sulla voce attiva;
- tabelle con intestazioni.

La navigazione principale si genera dalla struttura delle pagine in `content/`. L'ordine delle voci si cambia dal pannello.

### CSS e JavaScript
- **CSS senza strumenti di build:** variabili CSS per colori, font e spaziature di unipi.it, file separati per base, layout e componenti. Chiunque erediti il sito li modifica con un editor di testo.
- **JavaScript minimo:** menu mobile e parti espandibili. Tutto funziona anche senza JavaScript.
- **Immagini:** Kirby genera versioni ridimensionate e in formato WebP, con `srcset` per i vari schermi.
- **Font:** ospitati sul server, nessuna chiamata a Google Fonts.

### Lingue
Ogni pagina ha un file per lingua (`.it.txt`, `.en.txt`). Il pannello mostra un selettore di lingua. Gli indirizzi sono `/it/...` e `/en/...`. Le etichette fisse dell'interfaccia (menu, pulsanti) sono in `site/languages/`.

### Ricerca e calendario
- **Ricerca:** funzione integrata di Kirby sui contenuti, eseguita sul server. Nessun servizio esterno.
- **Calendario:** un controller costruisce la griglia del mese a partire dagli eventi. Il file `.ics` è una seconda versione dello stesso template (`calendario.ics.php`).

### Ambienti
`site/config/` contiene una configurazione per `dev.iea.ing.unipi.it` e una per `iea.ing.unipi.it`:
- su dev: messaggi di errore visibili, `noindex`, cache disattivata;
- in produzione: errori nascosti, cache attiva.

---

## Fase 0: preparazione e accessi
- Chiedere all'IT il sito `dev.iea.ing.unipi.it` con la sua cartella, PHP 8.4 e il DNS.
- Chiedere all'IT le estensioni PHP richieste da Kirby (`gd` o `imagick`, `mbstring`, `ctype`, `curl`, `filter`, `hash`, `iconv`, `json`, `openssl`, `session`, `SimpleXML`, `zip`) e il limite di caricamento dei file (`upload_max_filesize`), da portare ad almeno 20 MB per i PDF.
- Chiedere all'IT se la cartella del sito è inclusa nei backup e con che frequenza.
- Verificare quale licenza di Kirby si applica al corso e acquistarla prima della messa online.
- Ambiente di sviluppo locale: PHP 8.4 con il server integrato (`php -S localhost:8000 kirby/router.php`).
- Git solo come strumento dello sviluppatore per il codice (`site/`, `assets/`). La cartella `content/` resta sul server e non va nel repository.

**Risultato:** accesso FTPS a dev, Kirby installato e raggiungibile.

## Fase 1: analisi dello stile di unipi.it
- **Elementi di base:** colori (primario `#225DD7`, secondario `#1A315D`, supporto `#F0F0F0`), font Inter per i titoli e Titillium Web per il testo, gerarchia dei titoli, spaziature, griglia, punti di passaggio da mobile a desktop. Fonte: manuale di immagine coordinata, verificato sul sito.
- **Inventario dei componenti di unipi.it:** header, menu, menu mobile, breadcrumb, card, liste di news, tabelle, blocchi di avviso, footer, pulsanti.
- **Verifica di contrasto** rispetto a WCAG AA.

**Risultato:** pagina HTML statica di prova con elementi di base e componenti, da approvare prima dello sviluppo.

## Fase 2: modello dei contenuti
- Blueprint delle pagine istituzionali e dei blocchi ammessi.
- Blueprint della bacheca: struttura minima di partenza (titolo, testo, immagine, data); la struttura definitiva si decide nel secondo giro di istruzioni.
- Ruolo "redazione" per il cliente: può creare, modificare e pubblicare notizie ed eventi e modificare i testi delle pagine; non può cambiare struttura, menu, impostazioni e utenti.
- Organizzazione degli allegati: ogni file sta nella cartella della pagina che lo usa.

**Risultato:** blueprint in bozza, provati nel pannello su dev.

## Fase 3: componenti e modelli di pagina
- Snippet dei componenti della Fase 1.
- Template per tutti i tipi di pagina.
- CSS e JavaScript come descritto sopra.
- Pagine d'errore (404) e pagina di manutenzione.

**Risultato:** tutte le tipologie di pagina con la grafica definitiva su dev, da approvare.

## Fase 4: migrazione dei contenuti
- **Testi:** uno script converte i Markdown del prototipo (`content/` di questo repository) nel formato di Kirby, con i blocchi al posto del testo libero.
- **Allegati e immagini:** uno script li scarica dal sito attuale (oltre 50 tra PDF e DOC, più le locandine) e li mette nelle cartelle delle pagine, con nomi coerenti e senza duplicati.
- **Bacheca:** 31 post, 20 recenti e 11 d'archivio ([mappa](04-bacheca.md)).
- **Pagine vuote:** Orientamento, Tutorato, Rappresentanti degli studenti, Qualità del corso.
- **Segnalazioni:** risolvere `[DA VERIFICARE]` e `[DA FORNIRE]` con il [foglio di revisione](revisione-contenuti.xlsx).
- **Inglese:** recuperare i testi di `/en/` dal sito attuale e confrontarli con la nuova struttura.

**Risultato:** sito completo di contenuti su dev.

## Fase 5: ricerca e calendario
- Pagina di ricerca con risultati per lingua.
- Calendario mensile, lista dei prossimi eventi, file `.ics` per evento e per l'intero calendario.

**Risultato:** ricerca e calendario funzionanti e accessibili.

## Fase 6: pannello per il cliente
- Etichette e istruzioni nei campi del pannello, in italiano.
- Testo alternativo obbligatorio per le immagini, limiti di peso dei file.
- Anteprima delle pagine prima della pubblicazione.
- Guida breve e illustrata: creare, modificare e cancellare una notizia o un evento, caricare un allegato, cosa fare se qualcosa non va.

**Risultato:** il cliente pubblica una notizia di prova in autonomia su dev.

## Fase 7: verifiche finali
- **Accessibilità WCAG 2.1 AA:** controlli automatici (axe o pa11y); controlli manuali con tastiera e lettore di schermo (NVDA, VoiceOver); contrasti; ingrandimento al 200%.
- **Compatibilità e velocità:** browser principali, Lighthouse.
- **Revisione dei contenuti** con il cliente.
- **Dichiarazione di accessibilità** sul form AgID, da verificare se la gestisce l'Ateneo.
- **Privacy:** pagina dedicata o rimando a quella di Ateneo.

**Risultato:** verbale di verifica e via libera del cliente.

## Fase 8: messa online
- **Copia da dev a produzione:** l'intera cartella del sito, contenuti compresi, una sola volta. Da quel momento il cliente lavora solo su `iea.ing.unipi.it`.
- **Configurazione di produzione:** cache attiva, errori nascosti, rimozione del `noindex`, sitemap.
- **Redirect dai vecchi indirizzi** con `.htaccess`, dalla tabella in [02-architettura.md](02-architettura.md).
- **Sito attuale:** decidere se tenerne una copia statica in archivio o spegnerlo.
- **Dopo il lancio:** dev resta per le modifiche future allo sviluppo, con contenuti di prova.

## Fase 9: consegna
- Account di amministratore del pannello al referente del corso.
- Documentazione tecnica: struttura dei file, come aggiornare Kirby, come aggiungere un tipo di pagina.
- Sessione di formazione sul pannello per chi pubblicherà.

---

## Ordine e dipendenze
- **Fasi 0 e 1:** partono subito, in parallelo.
- **Fase 2:** si chiude dopo il secondo giro di istruzioni sulla bacheca.
- **Fase 3:** dopo l'approvazione della pagina di prova della Fase 1.
- **Fasi 4, 5 e 6:** dopo la 2 e la 3.
- **Fasi 7, 8 e 9:** in sequenza, alla fine.

Rilasci intermedi da mostrare al cliente su `dev.iea.ing.unipi.it`:
1. pagina di prova della grafica (fine Fase 1);
2. sito navigabile con tutte le tipologie di pagina (fine Fase 3);
3. sito completo e pannello pronto per il cliente (fine Fase 6);
4. messa online.

## Punti ancora aperti
1. Estensioni PHP, limite di caricamento dei file e backup: da confermare con l'IT.
2. Licenza di Kirby: quale si applica e chi la acquista.
3. Struttura della bacheca e del calendario: secondo giro di istruzioni.
4. Chi scrive o traduce i testi inglesi che non esistono nel sito attuale.
5. Cosa fare del sito attuale dopo la messa online.
