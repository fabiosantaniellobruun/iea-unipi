# Fase 1: analisi dello stile di unipi.it

Fonti, in ordine di priorità:
1. manuale di immagine coordinata (`UNIPI_Brand style guide red.pdf`): riferimento per colori, tipografia e marchio;
2. sito `www.unipi.it` (WordPress con Elementor), per ciò che il manuale non specifica: componenti, spaziature, griglia. Analizzato il 25 settembre 2026 dai file del tema e da schermate della home e di una pagina interna.

Schermate di riferimento in [`stile/`](stile/): [home](stile/unipi-home.png), [pagina interna](stile/unipi-pagina-interna.png), [footer](stile/unipi-footer.png).

## 1. Colori

**Fonte principale: manuale, p. 17 "Colori istituzionali".** I valori coincidono con le variabili del tema di unipi.it, che però usa i nomi al contrario (chiama "primary" il blu notte). Nel nuovo sito valgono i nomi del manuale.

| Nome nel manuale | Valore | Uso indicato dal manuale | Contrasto su bianco |
|---|---|---|---|
| Primary color, blu reale | `#225DD7` | Evidenziare contenuti aggiornati, campagne informative, servizi dedicati. Su unipi.it: link, pulsanti, link rapidi | 5,81 |
| Secondary color, blu notte | `#1A315D` | Intestazioni, menu principali, sezioni ufficiali. Su unipi.it: titoli, voci di menu, filetti, icone | 12,79 |
| Support color | `#F0F0F0` | Distinguere informazioni, azioni e funzionalità senza appesantire. Su unipi.it: fondi di sezione | |
| Blu Pantone 541 | quadricromia C100 M57 Y9 K38 | Solo per il marchio ufficiale | |

Colori aggiuntivi, fuori dalla palette del manuale:
| Valore | Fonte | Uso | Contrasto su bianco |
|---|---|---|---|
| `#000000` | Tema di unipi.it; manuale, p. 41 (firma email) | Testo corrente | 21,00 |
| `#1F1F1F` grigio antracite | Manuale, p. 41 (firma email) | Alternativa al nero per testi lunghi | 16,48 |
| `#666666` | Tema di unipi.it | Testi secondari, sottotitoli, breadcrumb | 5,74 |
| `#BEBEBE`, `#DDDDDD` | Tema di unipi.it | Separatori decorativi | 1,86 e 1,36 |

**Verifica WCAG 2.1 AA:**
- tutti gli abbinamenti di testo superano 4,5:1, anche su fondo `#F0F0F0` (blu reale 5,10; grigio 5,04; blu notte 11,23);
- testo bianco su blu reale 5,81 e su blu notte 12,79: pulsanti e fasce colorate sono conformi;
- i grigi `#BEBEBE` e `#DDDDDD` non raggiungono il 3:1 richiesto per i bordi dei componenti interattivi. Nel nuovo sito si usano solo come separatori decorativi; bordi di campi, pulsanti e fisarmoniche usano il blu notte, come fa unipi.it.

## 2. Tipografia

**Fonte principale: manuale, pp. 18-19 "Tipografia e gerarchia dei testi".** Inter per titoli e display, Titillium Web per il testo. Il manuale indica le dimensioni per desktop ("MAX") e per smartphone e tablet ("SM").

| Livello del manuale | Font | Desktop (MAX) | Mobile (SM) | Uso nel sito del corso |
|---|---|---|---|---|
| Display L | Inter | 100 px | 56 px | Non usato (riservato alla home di Ateneo) |
| Display M | Inter | 72 px | 48 px | Titolo della home del corso |
| Headline 1 | Inter | 56 px | 32 px | Titolo delle pagine (h1) |
| Headline 2 | Inter | 40 px | 28 px | h2 |
| Headline 3 | Inter | 32 px | 24 px | h3 |
| Headline 4 | Inter | 22 px | 20 px | h4, titoli delle card e delle fisarmoniche |
| Body L | Titillium Web | 18 px | non indicato nel manuale; 16 px come su unipi.it | Testo corrente |

Pesi disponibili indicati dal manuale: Inter regular (400), medium (500), semibold (600); Titillium Web regular (400), semibold (600), bold (700). Nel sito:
- titoli in Inter 500 per Display e Headline 1, 600 per Headline 2-4 (come su unipi.it);
- testo in Titillium Web 400, grassetti in 600 o 700;
- interlinea del testo 26 px su desktop e 24 px su mobile (valori di unipi.it); titoli con interlinea di circa 1,1-1,2;
- paragrafi con 24 px di spazio sotto;
- breadcrumb ed etichette in Titillium Web 600 maiuscolo, 13 px, come su unipi.it (non previsti dal manuale);
- font ospitati sul server del sito (licenza SIL Open Font License), senza chiamate a Google Fonts.

## 3. Griglia e spaziature

- Contenitore massimo di unipi.it: 1680 px. Le pagine interne hanno la colonna di testo larga circa 920 px, centrata.
- Nuovo sito: contenitore di 1280 px (sito più piccolo, meno colonne), colonna di testo al massimo di 760 px (circa 75 caratteri per riga, leggibilità migliore).
- Punti di passaggio: 767 px (mobile) e 1024 px (tablet), gli stessi di unipi.it.
- Margini laterali: 16 px su mobile, 24 px su tablet, 40 px su desktop.
- Scala di spaziature: 4, 8, 16, 24, 32, 48, 64, 96 px.

## 4. Componenti di unipi.it

| Componente | Come appare su unipi.it | Adattamento per il sito del corso |
|---|---|---|
| Barra dei link rapidi | Riga in alto, link piccoli in blu reale, allineati a destra (Future/i studenti, Studenti, ...) | Link rapidi per chi è iscritto: Orari ed esami, Portale Alice, Prenotazione esami, Contatti |
| Header | Fondo bianco, marchio di Ateneo a sinistra, menu principale in Inter 600 blu notte, icona di ricerca, selettore di lingua ITA | Marchio di Ateneo con il nome del corso accanto; menu con le 6 voci approvate; ricerca; selettore ITA/ENG |
| Titolo di pagina | Titolo grande in blu notte, sotto il breadcrumb in maiuscolo piccolo e grigio con icona della casa | Identico |
| Testo | Colonna centrata, etichette in grassetto, link in blu reale | Identico, con colonna più stretta |
| Fisarmoniche | Titolo in Inter 600 blu notte, icona "+" a destra, filetto blu notte sotto | Blocco "sezione espandibile", realizzato con `<details>` e `<summary>` (accessibile senza JavaScript) |
| Allegati | Titolo "Attachments:" ed elenco puntato di link | Blocco "allegati" con nome, formato e peso del file |
| Card delle notizie | Immagine, titolo, data | Card con data, categoria, titolo e sommario; immagine facoltativa |
| Footer | Fondo bianco, 4 colonne in Titillium Web (indirizzo, contatti, link istituzionali, trasparenza), icone social blu notte, filetto | Colonne: corso e dipartimento, contatti della segreteria, link del sito (Aziende ed enti, Servizi di Ateneo, Mappa del sito), link istituzionali (Accessibilità, Privacy); icone social |
| Ricerca | Campo con bordo blu notte e icona lente | Identico |

## 5. Marchio
- Il marchio ufficiale (sigillo e logotipo) non si ridisegna né si ricostruisce: va usato il file vettoriale fornito dall'Ateneo.
- Colori ammessi per il marchio: blu Pantone 541, bianco, nero.
- Il nome del corso va accanto al marchio, separato da un filetto verticale come su unipi.it, rispettando l'area di rispetto.

**Da ottenere dall'Ateneo:** il file SVG ufficiale del marchio a sviluppo orizzontale. Nella pagina di prova c'è un segnaposto.

## 6. Design token del nuovo sito
I valori sopra sono in `sito/assets/css/tokens.css`, come variabili CSS. Tutti gli altri fogli di stile usano solo queste variabili.
