# Fase 2: proposta di architettura

## 1. Principi
1. **Menu organizzato per destinatari e attività**, non per organi interni. Ogni voce principale risponde a una domanda: "Cos'è questo corso?", "Come entro?", "Cosa devo fare adesso che studio qui?", "Come mi laureo?", "Cosa succede?", "Chi contatto?".
2. **Una informazione, un posto.** Ogni contenuto ha una sola pagina di riferimento; le altre pagine rimandano a quella.
3. **Parte stabile separata dalla parte annuale.** Posti, date, tasse e bandi vivono in un blocco "Anno accademico 2026/27" dentro la pagina, facile da aggiornare.
4. **Massimo due livelli di profondità.** Ogni sezione ha una pagina indice vera, con un'introduzione e i link alle sottopagine.
5. **Link esterni dentro le pagine, nel punto in cui servono**, dichiarati come esterni. Nessuna voce di menu porta fuori dal sito senza avviso.

## 2. Destinatari, in ordine di priorità
| Destinatari | Cosa cercano |
|---|---|
| Chi vuole iscriversi (studenti e studentesse delle superiori, famiglie, chi cambia corso) | Cos'è il corso, perché sceglierlo, come si entra, date e posti |
| Chi è iscritto | Orari, esami, piano di studi, regolamenti, tirocini, mobilità |
| Chi si sta laureando | Procedura di tesi e di laurea, voto, scadenze |
| Chi si è laureato | Esame di Stato, ordini professionali, master, dottorato |
| Aziende, studi ed enti | Come ospitare un tirocinio |
| Tutti | Notizie, eventi, contatti |

## 3. Mappa proposta

```
Home
├── Il corso
│   ├── Il corso in breve               ← Cos'è IEA? + Perché a Pisa? + Ma in pratica?
│   ├── Cosa studierai                  ← Cosa studierò? + sintesi del piano di studi
│   └── Dopo la laurea                  ← Dopo cosa potrò fare? + Master + Altri enti + Esame di Stato
├── Iscriversi
│   ├── Iscriversi al primo anno        ← Come si accede? + Accesso al corso (parte stabile)
│   │     └── blocco "a.a. 2026/27": posti, scadenze, prova, tassa
│   └── Iscriversi agli anni successivi ← bando posti vacanti (oggi solo in bacheca)
├── Studiare
│   ├── Piano di studi                  ← Piani di studio
│   ├── Orari, calendario ed esami      ← Orario e calendario + link Calendario esami
│   ├── Regolamenti                     ← Regolamenti e manifesti (vigente in alto, archivio sotto)
│   ├── Tirocini                        ← Tirocini formativi (parte studenti)
│   └── Opportunità                     ← Opportunità per gli studenti + Summer School + Erasmus
├── Laurearsi                           ← Esame di laurea (per passaggi)
├── Bacheca
│   ├── Avvisi
│   ├── Eventi                          ← Seminari e conferenze + Mostre
│   └── Bandi e opportunità             ← Bandi e concorsi + Iniziative
└── Contatti                            ← segreteria didattica, sede, orari, figure di riferimento

Footer
├── Aziende ed enti                     ← Info per aziende ed enti (tirocini)
├── Servizi di Ateneo                   ← link utili superstiti: Biblioteca, DSU, Scuola di Ingegneria, Alice
├── Social
└── Mappa del sito
```

Menu principale: **Il corso · Iscriversi · Studiare · Laurearsi · Bacheca · Contatti** (6 voci).

### Perché così
- **"Iscriversi" diventa una sezione.** Oggi le informazioni sull'accesso sono divise tra Presentazione e Didattica, e il bando per gli anni successivi è sepolto nella bacheca. È il flusso più importante per chi arriva dall'esterno.
- **"Laurearsi" sta nel menu principale.** Riguarda ogni anno una coorte intera, ha scadenze rigide (6 mesi, 30 giorni, 15 giorni) e oggi è la settima voce di un sottomenu.
- **"Contatti" diventa una pagina.** Oggi manca: le informazioni sono divise tra footer, "Figure di riferimento" e il testo di altre pagine.
- **"Link utili" sparisce.** I link ancora validi vanno nelle pagine che li usano (prenotazione esami in "Orari, calendario ed esami", Ordini professionali in "Dopo la laurea"); i servizi generali vanno nel footer.
- **Aziende ed enti nel footer, non nel menu.** Sono pochi e cercano una pagina precisa; un link visibile nel footer e da "Tirocini" basta. *Alternativa*: se il corso vuole investire nei rapporti con le aziende, la voce può salire nel menu principale.
- **Bacheca con 3 categorie invece di 5.** "Mostre" ha un solo contenuto, "Iniziative" due. Seminari, conferenze e mostre sono tutti eventi con data e luogo; bandi e iniziative sono tutte opportunità con scadenza.

## 4. Flussi principali

**Futuro studente o studentessa**
Home → "Scopri il corso" → Il corso in breve → Cosa studierai → Iscriversi al primo anno → portale Alice (esterno)

**Chi è iscritto e cerca l'orario**
Home → accesso rapido "Orari ed esami" → Orari, calendario ed esami → calendario per anno (esterno)

**Laureando o laureanda**
Home → Laurearsi → passo 1: richiesta tesi (modulo) → passo 2: domanda su Alice → passo 3: discussione → voto

**Studio o azienda che vuole ospitare un tirocinio**
Home → footer "Aziende ed enti" → convenzione sul portale tirocini (esterno)

**Chi vuole trasferirsi da un altro corso**
Home → Iscriversi → Iscriversi agli anni successivi → bando (esterno)

## 5. Homepage proposta
1. Intestazione: nome del corso, una frase che dice cos'è.
2. Tre accessi per destinatario: *Vuoi iscriverti?* · *Studi già qui?* · *Ti stai laureando?*
3. Accessi rapidi per chi è iscritto: Orari, Calendario esami, Piano di studi, Prenotazione esami (esterno), Contatti.
4. In evidenza: massimo 3 elementi (oggi: inizio lezioni, conferenza "COSTRUZIONE", bando posti vacanti).
5. Ultimi dalla bacheca: 4 elementi con data e categoria.
6. Il corso in cifre: 5 anni, 300 CFU, classe LM-4 c.u., accesso programmato, titolo riconosciuto nell'Unione europea.

## 6. Tabella di corrispondenza

| Pagina attuale | Destinazione | Azione |
|---|---|---|
| Home | Home | Ristrutturare |
| Presentazione (indice) | Il corso (indice nuovo) | Sostituire con un vero indice |
| Cos'è IEA? | Il corso in breve | Unire |
| Perché a Pisa? | Il corso in breve | Unire |
| Ma in pratica? | Il corso in breve | Unire |
| Cosa studierò? | Cosa studierai | Riscrivere |
| Dopo cosa potrò fare? | Dopo la laurea | Riscrivere e integrare |
| Figure di riferimento | Contatti | Spostare |
| Come si accede? | Iscriversi al primo anno | Unire |
| Didattica (indice) | Studiare (indice nuovo) | Sostituire con un vero indice |
| Come accedere al corso | Iscriversi al primo anno | Unire, separare parte annuale |
| Regolamenti e manifesti | Regolamenti | Riscrivere |
| Piani di studio | Piano di studi | Riscrivere in tabelle |
| Orario e calendario | Orari, calendario ed esami | Riscrivere, correggere date |
| Calendario esami (esterno) | Orari, calendario ed esami | Link contestuale |
| Tirocini formativi | Tirocini | Riscrivere |
| Info per aziende ed enti | Aziende ed enti | Spostare nel footer |
| Esame di laurea | Laurearsi | Riscrivere per passaggi |
| Opportunità per gli studenti | Opportunità | Ricostruire; il bando scaduto va in archivio bacheca |
| Altre attività formative | Opportunità (Summer School) + Dopo la laurea (Master) | Dividere |
| Link utili (indice e sottopagine) | Pagine pertinenti + footer | Eliminare |
| Altri enti | Dopo la laurea | Spostare |
| Biblioteca, DSU (esterni) | Footer "Servizi di Ateneo" | Spostare |
| Bacheca: Seminari e conferenze | Bacheca: Eventi | Unire |
| Bacheca: Mostre | Bacheca: Eventi | Unire |
| Bacheca: Bandi e concorsi | Bacheca: Bandi e opportunità | Unire |
| Bacheca: Iniziative | Bacheca: Bandi e opportunità | Unire |
| Bacheca: Avvisi | Bacheca: Avvisi | Mantenere |
| Bacheca: bando posti vacanti | Iscriversi agli anni successivi | Promuovere a pagina stabile |
| (manca) | Contatti | Creare |
| (manca) | Iscriversi agli anni successivi | Creare |

## 7. Da decidere
1. Aziende ed enti: nel footer (proposta) o nel menu principale?
2. Contenuti mancanti (Erasmus+, orientamento, tutorato, rappresentanti): segnaposto nel prototipo o esclusi?
