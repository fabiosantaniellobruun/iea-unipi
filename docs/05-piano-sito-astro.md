# Piano d'azione: nuovo sito del Corso di Laurea in Ingegneria Edile-Architettura

Piano per sviluppare il sito definitivo a partire dal prototipo (`https://fabiosantaniellobruun.github.io/iea-unipi/`).

## Decisioni prese

| Tema | Scelta |
|---|---|
| Sito | Corso di Laurea Magistrale a ciclo unico in Ingegneria Edile-Architettura |
| Traccia per architettura e testi | Prototipo in questo repository, con i testi riscritti |
| Tecnologia | Astro con output statico; Decap CMS per news e post; le modifiche strutturali restano nel codice |
| Repository | GitHub |
| Login a Decap | Account GitHub per chi pubblica, con autenticazione OAuth tramite un Cloudflare Worker |
| Pubblicazione | GitHub Action: build di Astro e caricamento via FTP sicuro a ogni modifica. Prima su `dev.iea.ing.unipi.it`, poi in produzione |
| Allegati e immagini | Scaricati dal sito attuale e ospitati nel nuovo |
| Lingue | Italiano e inglese |
| Pagine senza contenuto | Online come pagine vuote |
| Grafica | Colori, font e tono visivo di unipi.it, con componenti adattati a un sito più piccolo |
| Accessibilità | WCAG 2.1 AA |
| Funzioni | Calendario e ricerca; niente analytics, banner cookie né modulo contatti |
| Utenti del CMS | Un solo ruolo, pubblicazione diretta senza approvazione |
| Scadenza | Nessuna |

### Come funziona il login a Decap
1. Chi pubblica le news ha un account GitHub, con qualunque email, ed è collaboratore con permesso di scrittura sul repository.
2. Su `/admin` preme "Accedi con GitHub".
3. Decap chiama un Cloudflare Worker che gestisce lo scambio OAuth con GitHub. Il Worker custodisce il segreto dell'app OAuth; nel browser non passa nessuna credenziale.
4. Ogni salvataggio in Decap è un commit sul repository e avvia la pubblicazione automatica.

Il Worker è separato da Cloudflare Pages e dall'hosting: il sito resta sull'hosting del cliente. Il piano gratuito di Cloudflare basta.

---

## Fase 0: preparazione e accessi
**Obiettivo:** avere tutto il necessario per lavorare e pubblicare su `dev`.
- Nuovo repository per il sito Astro. Questo repository resta come riferimento del prototipo.
- Verificare l'hosting:
  - protocollo FTPS o SFTP e porta;
  - percorso della cartella pubblica per `dev` e per la produzione;
  - supporto di `.htaccess`;
  - spazio disponibile.
- Configurare il DNS di `dev.iea.ing.unipi.it`, probabilmente con una richiesta all'IT di Ateneo.
- Creare l'account GitHub del cliente e aggiungerlo come collaboratore.
- Creare l'app OAuth su GitHub e l'account Cloudflare per il Worker.
- Salvare le credenziali FTP come secrets del repository, mai nel codice.

**Risultato:** checklist degli accessi completa.

## Fase 1: analisi dello stile di unipi.it
**Obiettivo:** tradurre lo stile di Ateneo in regole riusabili.
- **Elementi di base (design token):** colori (primario `#225DD7`, secondario `#1A315D`, supporto `#F0F0F0`), font Inter per i titoli e Titillium Web per il testo, gerarchia dei titoli, spaziature, griglia, punti di passaggio da mobile a desktop. Fonte: manuale di immagine coordinata, verificato sul sito.
- **Inventario dei componenti di unipi.it:** header, menu, menu mobile, breadcrumb, card, liste di news, tabelle, blocchi di avviso, footer, pulsanti, pagine di dettaglio.
- **Abbinamento ai modelli del prototipo:** home, indice di sezione, pagina di contenuto, lista e dettaglio della bacheca, calendario, contatti.
- **Verifica di contrasto e accessibilità** delle scelte cromatiche rispetto a WCAG AA.
- **Font ospitati sul server del sito,** non caricati da Google Fonts, per non inviare dati a terzi.

**Risultato:** documento con elementi di base e componenti, più una pagina HTML statica di prova da approvare prima dello sviluppo.

## Fase 2: modello dei contenuti
**Obiettivo:** definire come sono organizzati i contenuti in Astro e in Decap.
- **Pagine istituzionali:** Markdown con titolo, frase introduttiva e stato "vuota". Si modificano nel codice, non in Decap.
- **Navigazione:** un unico file dati, come nel prototipo, che genera menu, breadcrumb e mappa del sito.
- **Bacheca:** struttura minima di partenza (titolo, testo, immagine, data). La struttura definitiva (categorie, eventi con luogo e orario, bandi con scadenza, "in evidenza") si definisce nel secondo giro di istruzioni.
- **Calendario:** dipende dalla struttura della bacheca. Gli eventi possono essere post con una data evento, oppure una raccolta separata.
- **Lingue:** percorsi `/it/` e `/en/`. In Decap ogni news ha versione italiana e inglese.
- **Allegati:** struttura delle cartelle e regole per i nomi dei file.

**Risultato:** struttura dei contenuti documentata e configurazione di Decap in bozza.

## Fase 3: base del progetto Astro
- Progetto Astro con output statico e TypeScript.
- Layout, navigazione dal file dati, breadcrumb, gestione delle lingue.
- Controlli automatici a ogni modifica: build, verifica dei link interni, controllo di accessibilità (axe o pa11y) sulle pagine principali.
- Istruzione `noindex` e `robots.txt` restrittivo su `dev`.

**Risultato:** sito vuoto ma navigabile, con i controlli attivi.

## Fase 4: pubblicazione automatica su dev
- **GitHub Action:** build di Astro, poi caricamento via FTP sicuro su `dev.iea.ing.unipi.it`.
  - carica solo i file cambiati;
  - un solo caricamento alla volta, per non sovrapporli;
  - si può lanciare anche a mano.
- **Quando scatta:**
  - a ogni modifica al codice sul branch di sviluppo;
  - a ogni salvataggio del cliente in Decap;
  - una volta al giorno, per aggiornare calendario ed eventi passati.
- **Ripristino:** ogni build pubblicata resta salvata come artifact, così si può ricaricare una versione precedente.
- **Branch:** durante lo sviluppo `dev` pubblica su `dev.iea.ing.unipi.it`; dopo la messa online `main` pubblica in produzione.

**Risultato:** ogni modifica è online su `dev` in pochi minuti.

## Fase 5: componenti e grafica
- Componenti della Fase 1 in Astro, con HTML semantico e accessibile:
  - navigazione da tastiera;
  - indicazione visibile dell'elemento selezionato;
  - salto al contenuto;
  - testi alternativi per le immagini;
  - tabelle con intestazioni corrette.
- Versioni mobile e desktop.
- Home con presentazione del corso, avvisi, contenuti in evidenza e ultime notizie.
- Liste della bacheca con pagine numerate e archivio per anno.

**Risultato:** tutte le tipologie di pagina con la grafica definitiva, da approvare.

## Fase 6: migrazione dei contenuti
- **Testi:** importare i testi riscritti del prototipo (`content/`).
- **File:** uno script scarica dal sito attuale allegati e immagini (oltre 50 tra PDF e DOC, più le locandine). Lo script:
  - li rinomina in modo coerente e toglie i duplicati;
  - ottimizza le immagini (WebP, più dimensioni);
  - aggiorna i link nei testi.
- **Bacheca:** importare i 31 post, 20 recenti e 11 d'archivio, con gli allegati (mappa in [04-bacheca.md](04-bacheca.md)).
- **Pagine vuote:** Orientamento, Tutorato, Rappresentanti degli studenti, Qualità del corso.
- **Segnalazioni:** risolvere `[DA VERIFICARE]` e `[DA FORNIRE]` con il [foglio di revisione](revisione-contenuti.xlsx). I segnaposto non devono comparire in produzione.
- **Inglese:** recuperare i testi di `/en/` dal sito attuale e confrontarli con la nuova struttura.

**Risultato:** sito completo di contenuti su `dev`.

## Fase 7: ricerca e calendario
- **Ricerca:** Pagefind. Crea l'indice durante la build, funziona senza server e rispetta la privacy. Un indice per lingua.
- **Calendario:** vista a lista (prossimi eventi) e vista mensile generate in fase di build, con file `.ics` per ogni evento. Il dettaglio dipende dalla struttura della bacheca (Fase 2).

**Risultato:** ricerca e calendario funzionanti e accessibili.

## Fase 8: Decap CMS
- Raccolte, campi (definiti nella Fase 2), cartella delle immagini, due lingue.
- Backend GitHub con autenticazione tramite il Cloudflare Worker della Fase 0.
- Anteprima delle news con la grafica del sito.
- Pubblicazione diretta, senza passaggio di approvazione.
- Regole nei campi: testo alternativo obbligatorio per le immagini e limiti di peso.
- Guida breve e illustrata per il cliente: creare, modificare e cancellare una news, tempi di pubblicazione, cosa fare se qualcosa non va.

**Risultato:** il cliente pubblica una news di prova in autonomia su `dev`.

## Fase 9: verifiche finali
- **Accessibilità WCAG 2.1 AA:** controlli automatici; controlli manuali con tastiera e lettore di schermo (NVDA, VoiceOver); contrasti; ingrandimento al 200%.
- **Compatibilità e velocità:** browser principali, Lighthouse.
- **Revisione dei contenuti** con il cliente, con il foglio di revisione aggiornato ai link di `dev`.
- **Dichiarazione di accessibilità** sul form AgID, obbligatoria per le università. Da verificare se la gestisce l'Ateneo.
- **Privacy:** pagina dedicata o rimando a quella di Ateneo. Senza analytics e con i font sul server del sito il banner cookie non serve.

**Risultato:** verbale di verifica e via libera del cliente.

## Fase 10: messa online
- **Redirect dai vecchi indirizzi:** tabella dai vecchi URL ai nuovi, in buona parte già pronta ([02-architettura.md](02-architettura.md)). Con `.htaccess` se l'hosting lo supporta, altrimenti con pagine HTML di reindirizzamento.
- **Produzione:** pubblicazione da `main`, rimozione del `noindex`, sitemap, cambio del DNS.
- **Sito attuale:** decidere se tenerne una copia statica in archivio o spegnerlo.
- **Consegna:** documentazione tecnica e sessione di formazione su Decap per il cliente.

---

## Ordine e dipendenze
- **Fasi 0 e 1:** partono subito, in parallelo.
- **Fase 2:** si chiude dopo il secondo giro di istruzioni sulla bacheca.
- **Fasi 3 e 4:** dopo la 0. La 4 dipende dagli accessi FTP e dal DNS di `dev`.
- **Fase 5:** dopo l'approvazione della pagina di prova della Fase 1.
- **Fasi 6, 7 e 8:** dopo la 2 e la 5. La 8 dipende dal Worker della Fase 0.
- **Fasi 9 e 10:** in sequenza, alla fine.

Rilasci intermedi da mostrare al cliente:
1. pagina di prova della grafica (fine Fase 1);
2. sito navigabile su `dev` (fine Fase 5);
3. sito con i contenuti e news pubblicate dal CMS (fine Fase 8);
4. messa online.

## Punti ancora aperti
1. Dettagli dell'hosting: FTPS o SFTP, supporto di `.htaccess`, cartelle, DNS di `dev`.
2. Struttura della bacheca e del calendario: secondo giro di istruzioni.
3. Chi scrive o traduce i testi inglesi che non esistono nel sito attuale.
4. Cosa fare del sito attuale dopo la messa online.
