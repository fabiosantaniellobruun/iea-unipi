<?php
/** Pagina di prova della grafica (Fase 1): mostra tutti i componenti con contenuti di esempio. */
$colors = [
  ['Primary, blu reale', '--color-primary', '#225DD7', 'Link, pulsanti, contenuti in evidenza', '5,81:1'],
  ['Secondary, blu notte', '--color-secondary', '#1A315D', 'Titoli, menu, intestazioni', '12,79:1'],
  ['Support', '--color-support', '#F0F0F0', 'Fondi di sezione', ''],
  ['Testo', '--color-text', '#000000', 'Testo corrente', '21:1'],
  ['Testo secondario', '--color-text-muted', '#666666', 'Sottotitoli, breadcrumb, date', '5,74:1'],
];
?>
<?php snippet('layout', slots: true) ?>
<div class="container page-body styleguide">

  <section aria-labelledby="sg-colori">
    <h2 id="sg-colori">Colori</h2>
    <p>Manuale, p. 17. Il contrasto indicato è quello del colore come testo su fondo bianco.</p>
    <ul class="swatches">
      <?php foreach ($colors as [$name, $var, $hex, $use, $ratio]): ?>
      <li>
        <span class="swatch" style="background: var(<?= $var ?>)"></span>
        <strong><?= $name ?></strong><br>
        <code><?= $hex ?></code><?= $ratio ? ' · ' . $ratio : '' ?><br>
        <span class="card__meta"><?= $use ?></span>
      </li>
      <?php endforeach ?>
    </ul>
  </section>

  <section aria-labelledby="sg-tipo">
    <h2 id="sg-tipo">Tipografia</h2>
    <p>Manuale, pp. 18-19: Inter per i titoli, Titillium Web per il testo. Le dimensioni passano da quelle "MAX" (desktop) a quelle "SM" (mobile) sotto i 1025 px.</p>
    <p class="display-m">Display M</p>
    <p class="h1-sample">Headline 1 · titolo di pagina</p>
    <p class="h2-sample">Headline 2 · sezione</p>
    <p class="h3-sample">Headline 3 · sottosezione</p>
    <p class="h4-sample">Headline 4 · titoli di card e fisarmoniche</p>
    <p>Body L. Il Corso di Laurea Magistrale a ciclo unico in Ingegneria Edile-Architettura unisce le competenze tecniche dell'ingegneria edile alla formazione culturale e progettuale dell'architettura. Un <a href="#">link interno</a>, un <a href="https://www.unipi.it/" rel="external">link esterno</a> e un testo in <strong>grassetto</strong>.</p>
  </section>

  <section aria-labelledby="sg-pulsanti">
    <h2 id="sg-pulsanti">Pulsanti</h2>
    <p><a class="button" href="#">Iscriviti alla prova</a> <a class="button button--outline" href="#">Leggi il bando</a></p>
  </section>

  <section aria-labelledby="sg-blocchi">
    <h2 id="sg-blocchi">Blocchi di contenuto</h2>
    <p>Questi sono i blocchi che la redazione può usare nelle pagine dal pannello.</p>

    <h3>Avviso</h3>
    <div class="notice"><p><span class="notice__label">Ricorda:</span> se non paghi la tassa entro il 23 agosto 2026 non puoi partecipare alla prova.</p></div>

    <h3>Scheda informativa</h3>
    <dl class="infocard">
      <dt>Durata</dt><dd>5 anni, ciclo unico</dd>
      <dt>Crediti</dt><dd>300 CFU (crediti formativi universitari)</dd>
      <dt>Accesso</dt><dd>Programmato, con prova di ammissione nazionale</dd>
    </dl>

    <h3>Sezioni espandibili</h3>
    <div class="accordion">
      <details><summary>Chi può fare domanda</summary><div class="accordion__body"><p>Chi è iscritto allo stesso corso in un'altra università italiana e chiede il trasferimento.</p></div></details>
      <details><summary>Come fare domanda</summary><div class="accordion__body"><p>Registrati sul portale Alice e iscriviti al concorso.</p></div></details>
    </div>

    <h3>Allegati</h3>
    <?php snippet('attachment-list', ['items' => [['Bando a.a. 2026/27', 'PDF', '412 kB'], ['Regolamento didattico', 'PDF', '1,2 MB']]]) ?>

    <h3>Tabella</h3>
    <table>
      <thead><tr><th scope="col">Insegnamento</th><th scope="col">CFU</th></tr></thead>
      <tbody>
        <tr><td>Analisi matematica 1</td><td>6</td></tr>
        <tr><td>Disegno dell'architettura 1</td><td>12</td></tr>
      </tbody>
    </table>
  </section>

  <section aria-labelledby="sg-card">
    <h2 id="sg-card">Card della bacheca</h2>
    <ul class="cards">
      <li><article class="card">
        <p class="card__meta"><span class="tag">Eventi</span> 22 settembre 2026</p>
        <h3 class="card__title"><a href="#">"COSTRUZIONE": conferenza di apertura dell'anno accademico</a></h3>
        <p>Gli architetti Michele Grazzini e Andrea Tonazzini aprono l'anno accademico.</p>
      </article></li>
      <li><article class="card">
        <p class="card__meta"><span class="tag">Bandi e opportunità</span> Scadenza 19 ottobre 2026</p>
        <h3 class="card__title"><a href="#">Premio di Architettura Elite Bathroom Greentech 2026</a></h3>
        <p>Progetta un bagno pubblico per la città di Pisa.</p>
      </article></li>
      <li><article class="card">
        <p class="card__meta"><span class="tag">Avvisi</span> 21 settembre 2026</p>
        <h3 class="card__title"><a href="#">Le lezioni del 2026/27 iniziano il 21 settembre</a></h3>
        <p>Lunedì 21 settembre 2026 iniziano le lezioni del primo semestre.</p>
      </article></li>
    </ul>
    <nav class="pagination" aria-label="Pagine della bacheca">
      <ul>
        <li><a href="#" aria-current="page">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">Successiva</a></li>
      </ul>
    </nav>
  </section>

  <section aria-labelledby="sg-ricerca">
    <h2 id="sg-ricerca">Ricerca</h2>
    <form class="search-form" role="search" action="#">
      <label class="visually-hidden" for="sg-q">Cerca nel sito</label>
      <input id="sg-q" type="search" name="q" placeholder="Cerca…">
      <button type="submit"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/></svg><span class="visually-hidden">Cerca</span></button>
    </form>
  </section>

  <section class="band" aria-labelledby="sg-fascia">
    <div class="container">
      <h2 id="sg-fascia">Fascia su fondo Support</h2>
      <p>Per mettere in risalto una sezione della pagina, ad esempio "Il corso in sintesi" in home.</p>
    </div>
  </section>
</div>
<?php endsnippet() ?>
