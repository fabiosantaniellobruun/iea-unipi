# Fase 1: inventario e audit dei contenuti

Sito analizzato: `https://iea.ing.unipi.it/it/`, crawl del 23 settembre 2026.
Testi integrali estratti: [`fonte/testi-estratti.md`](fonte/testi-estratti.md). Dati strutturati: [`fonte/pagine.json`](fonte/pagine.json).

## 1. Numeri

| | |
|---|---|
| URL interni scaricati | 56 |
| Pagine istituzionali | 23 |
| Pagine della bacheca (liste ed eventi) | 33, di cui 8 eventi raggiungibili da due URL diversi |
| Pagine istituzionali distinte (senza duplicati) | 20 |
| Voci del menu che portano a siti esterni | 4 (Calendario esami, Biblioteca, DSU, Figure di riferimento con URL assoluto) |
| Documenti allegati (PDF, DOC) | 52 link |

Il sito è piccolo. Il problema non è la quantità di contenuti ma come sono organizzati, le ripetizioni e le informazioni superate.

## 2. Mappa attuale

```
Home
├── Presentazione                       (la pagina indice mostra il testo di "Cos'è IEA?")
│   ├── Cos'è IEA?
│   ├── Perché a Pisa?
│   ├── Come si accede?                 (sovrapposta ad "Accesso al corso")
│   ├── Cosa studierò?
│   ├── Ma in pratica?
│   ├── Dopo cosa potrò fare?
│   └── Figure di riferimento
├── Didattica                           (la pagina indice mostra il testo di "Accesso al corso")
│   ├── Come accedere al corso
│   ├── Regolamenti e Manifesti
│   ├── Piani di Studio
│   ├── Orario delle lezioni e calendario accademico
│   ├── Calendario esami                → sito esterno Scuola di Ingegneria
│   ├── Tirocini formativi
│   │   └── Info per le aziende e gli enti
│   ├── Esame di Laurea
│   ├── Opportunità per gli studenti    (contiene solo un bando scaduto)
│   ├── Altre attività formative        (Master e Summer School)
│   └── Figure di riferimento           (stessa pagina di Presentazione)
├── Bacheca                             (lista eventi iCagenda)
│   ├── Seminari e conferenze
│   ├── Bandi e concorsi
│   ├── Mostre
│   ├── Iniziative
│   └── Avvisi
└── Link utili                          (la pagina indice mostra il testo di "Scuola di Ingegneria")
    ├── Scuola di Ingegneria
    ├── Biblioteca di Ingegneria        → sito esterno
    ├── Università di Pisa
    ├── D.S.U.                          → sito esterno
    └── Altri enti
```

## 3. Inventario pagine istituzionali

| # | Pagina | URL | Parole | Destinatari | Esito |
|---|---|---|---|---|---|
| 1 | Home | `/it` | 74 | tutti | Riorganizzare |
| 2 | Cos'è IEA? | `/presentazione/cos-e-iea` | 208 | futuri studenti | Unire (con 3 e 6) |
| 3 | Perché a Pisa? | `/presentazione/perche-a-pisa` | 114 | futuri studenti | Unire (con 2 e 6) |
| 4 | Come si accede? | `/presentazione/come-si-accede` | 117 | futuri studenti | Unire (con 9) |
| 5 | Cosa studierò? | `/presentazione/cosa-studiero` | 127 | futuri studenti | Riscrivere |
| 6 | Ma in pratica? | `/presentazione/ma-in-pratica` | 143 | futuri studenti | Unire (con 2 e 3) |
| 7 | Dopo cosa potrò fare? | `/presentazione/cosa-potro-fare` | 145 | futuri studenti | Riscrivere, integrare con 16 |
| 8 | Figure di riferimento | `/presentazione/figure-di-riferimento` | 167 | tutti | Spostare in Contatti |
| 9 | Accesso al corso | `/didattica/accesso-al-corso` | 618 | futuri studenti | Riscrivere, separare parte stabile e parte annuale |
| 10 | Regolamenti e manifesti | `/didattica/regolamenti-e-manifesti` | 229 | iscritti | Riscrivere, archivio in fondo |
| 11 | Piano di studi | `/didattica/piani-di-studio` | 448 | iscritti, futuri studenti | Riscrivere in tabelle |
| 12 | Orario e calendario | `/didattica/orario-delle-lezioni-calendario-accademico` | 260 | iscritti | Riscrivere |
| 13 | Tirocini formativi | `/didattica/tirocini-formativi` | 187 | iscritti | Riscrivere, aggiungere procedura |
| 14 | Info per aziende ed enti | `/didattica/tirocini-formativi/info-per-le-aziende-e-gli-enti` | 252 | aziende | Spostare in area dedicata |
| 15 | Esame di laurea | `/didattica/esame-di-laurea` | 409 | laureandi | Riscrivere per passaggi |
| 16 | Altre attività formative | `/didattica/attivita-formative` | 165 | iscritti, laureati | Dividere: Summer School in Opportunità, Master in Dopo la laurea |
| 17 | Opportunità per gli studenti | `/didattica/opportunita-per-gli-studenti` | 111 | iscritti | Ricostruire come pagina stabile |
| 18 | Link utili: Scuola di Ingegneria | `/link-utili/scuola-di-ingegneria` | 26 | iscritti | Eliminare, link distribuiti nelle pagine pertinenti |
| 19 | Link utili: Università di Pisa | `/link-utili/universita-di-pisa` | 12 | tutti | Eliminare, come sopra |
| 20 | Link utili: Altri enti | `/link-utili/altri-enti` | 23 | laureati | Spostare in Dopo la laurea |

## 4. Problemi rilevati

### 4.1 Architettura e navigazione
1. **Pagine indice duplicate.** "Presentazione", "Didattica" e "Link utili" non hanno un contenuto proprio: mostrano il testo della prima sottopagina. Esistono quindi due URL per la stessa pagina.
2. **Informazioni sull'accesso in due posti diversi e non allineate.** "Come si accede?" elenca le materie della prova in modo diverso da "Accesso al corso": manca "comprensione del testo" e compare "cultura generale".
3. **"Figure di riferimento" compare in due menu**, e nel menu Didattica punta a un URL assoluto con `www`.
4. **Menu organizzato per struttura interna** (Presentazione, Didattica), non per chi usa il sito. Chi vuole iscriversi deve passare da due sezioni.
5. **Nessuna pagina Contatti.** Indirizzi ed email della segreteria sono solo nel footer e sparsi nelle pagine.
6. **Nessuna area per aziende ed enti**: la loro pagina è un sottolivello di "Tirocini formativi", dentro "Didattica".
7. **"Link utili" è un contenitore generico** di link esterni, in parte obsoleti, invece di link contestuali nelle pagine dove servono.
8. **Bacheca con doppio sistema**: eventi (iCagenda) e articoli (Bandi, Mostre, Iniziative) con layout diversi; ogni evento è raggiungibile da due URL.
9. **Voci che portano a siti esterni senza preavviso** (Calendario esami, Biblioteca, DSU).

### 4.2 Contenuti
1. **Homepage: anteprime poco utili.** I sei box della presentazione tagliano la prima frase a metà ("....") e ripetono sei volte "Leggi tutto". Le etichette non coincidono con i titoli delle pagine ("Cosa potrò fare poi?" contro "Dopo cosa potrò fare?"), con il refuso "Perchè". La homepage non offre accessi rapidi per chi è già iscritto, a parte orario e calendario.
2. **Ripetizioni.** La descrizione del corso (5 anni, ciclo unico, numero programmato, riconoscimento europeo) si ripete in almeno 4 pagine.
3. **Linguaggio burocratico**, lontano dal registro indicato dal manuale: "l'iscrizione è subordinata al superamento", "in linea di massima", "si consiglia pertanto", "ai sensi dell'art. 9, comma 4, della legge n. 341/1990".
4. **Linguaggio non inclusivo**: "lo studente", "il laureato", "gli studenti", ovunque.
5. **Informazioni annuali mescolate a quelle stabili** (posti, date, tasse dentro testi descrittivi).
6. **Date e scadenze in maiuscolo o dentro paragrafi**, non evidenziate.
7. **Link non descrittivi**: sette link "LINK" nella pagina orari, "seguente link", URL scritti per esteso.
8. **Email nascoste da JavaScript**: senza JavaScript chi legge vede "Questo indirizzo email è protetto dagli spambots".

### 4.3 Informazioni superate, incoerenti o da verificare
| Pagina | Problema |
|---|---|
| Opportunità per gli studenti | Unico contenuto: bando Erasmus italiano scaduto il 6 novembre 2025. La tabella dei posti è vuota. |
| Orario e calendario | Appelli straordinari con anni incoerenti: "da lunedì 26 ottobre 2025 a sabato 28 novembre 2026", "da lunedì 22 marzo 2026 a venerdì 30 aprile 2026". Probabilmente 2026 e 2027. |
| Regolamenti e manifesti | Il regolamento più recente è del 2017/18. Il link "2012-2013" punta al file 2011-12. Formati misti PDF e DOC. |
| Piano di studi | "Diritto urbanistico…" e "Fisica tecnica ambientale" compaiono sia al 2° sia al 3° anno (forse insegnamenti annuali divisi su due anni). "Geotecnica e geologia" ripetuto al 4° anno. Voce orfana "Course Catalogue - UNIPI (6 CFU)" tra gli insegnamenti a scelta. Refuso "leglislazione". |
| Figure di riferimento | L'email del Presidente (Prof. Luca Lanini) decodificata dal codice è `mg.bevilacqua@ing.unipi.it`: probabilmente è di un presidente precedente. |
| Esame di laurea | Il regolamento delle tesi è un allegato a un verbale del 2016. |
| Link utili | Link "Occupazione aule magne" fissato su una data del 2017; link HTTP; Centro servizi informatici "polo6" probabilmente dismesso. |
| Tirocini formativi | Riferimento normativo D.I. 142/1998. Nessuna istruzione pratica per chi studia (come trovare un tirocinio, come attivarlo). |
| Altre attività formative | "Data di creazione: 28 Maggio 2025" visibile nel testo. |
| Bacheca: Posti vacanti | Refuso "Ingeneria". |

## 5. Contenuti mancanti
Contenuti che un sito di corso di laurea normalmente offre e che qui mancano. Nel prototipo diventano segnaposto `[DA FORNIRE]`, senza testo inventato.
- Pagina Contatti unica (segreteria didattica, orari, sede, presidenza).
- Mobilità internazionale Erasmus+ (citata in "Cosa studierò?" ma senza pagina).
- Orientamento e open day per chi deve ancora iscriversi.
- Tutorato, rappresentanti degli studenti, assicurazione della qualità.
- Riferimento all'esame di Stato per architetto (c'è solo quello per ingegnere, nei link utili).
- Versione inglese: esiste `/en/`, non analizzata.
