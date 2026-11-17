<?php
/**
 * Brand guidelines del corso: documento di riferimento, in italiano, non collegato dal sito e fuori dai motori di ricerca.
 * Fonte: file Figma "Variuous" (pagina IEA); le parti mancanti sono completate con le linee guida dell'Università di Pisa
 * e segnate [DA VERIFICARE] per la revisione.
 */
$kirby->response()->header('X-Robots-Tag', 'noindex, nofollow');

// indice: sezione => [titolo, [sottosezioni]]
$toc = [
    'perche-brand' => ['Perché fare brand', []],
    'essenza'      => ['1. Essenza', ['posizionamento' => 'Posizionamento', 'manifesto' => 'Manifesto', 'valori' => 'Valori', 'personalita' => 'Personalità e tono di voce', 'target' => 'Target', 'come-si-scrive' => 'Come si scrive', 'esempi-di-tono' => 'Esempi di tono', 'inclusivita' => 'Inclusività', 'best-practice' => 'Best practice']],
    'marchio'      => ['2. Marchio', ['panoramica' => 'Panoramica', 'configurazioni' => 'Configurazioni', 'schemi-colore' => 'Schemi colore', 'posizionamento-marchio' => 'Posizionamento', 'marchio-ateneo' => 'Insieme al marchio di Ateneo', 'marchio-donts' => 'Cosa non fare']],
    'tipografia'   => ['3. Tipografia', ['font-principale' => 'Font principale', 'font-secondario' => 'Font secondario', 'tipografia-web' => 'Utilizzo su web']],
    'colore'       => ['4. Colore', ['palette' => 'Palette', 'colore-utilizzo' => 'Utilizzo', 'accessibilita' => 'Accessibilità']],
    'immagini'     => ['5. Immagini', ['stile' => 'Stile', 'linguaggio' => 'Linguaggio', 'tagli' => 'Tagli e inquadrature', 'soggetti' => 'Soggetti', 'trattamenti' => 'Trattamenti', 'rapporto-marchio' => 'Rapporto con il marchio']],
    'format'       => ['6. Format', ['concept' => 'Concept', 'composizione' => 'Composizione', 'format-applicazione' => 'Applicazione']],
    'applicazioni' => ['7. Applicazioni', ['documenti' => 'Documenti', 'cancelleria' => 'Cancelleria', 'sito-web' => 'Sito web', 'poster' => 'Poster', 'brochure' => 'Brochure', 'merchandising' => 'Merchandising', 'newsletter' => 'Newsletter', 'social-media' => 'Social media', 'firma-email' => 'Firma email']],
];
$titles = [];
foreach ($toc as $id => [$title, $subs]) {
    $titles[$id] = $title;
    $titles += $subs;
}
// sezioni su cui si deve ancora lavorare: nell'indice portano il tag WIP
$wip = ['format', 'format-applicazione', 'applicazioni'];
$h2 = fn (string $id) => '<h2 id="' . $id . '">' . $titles[$id] . '</h2>';
$h3 = fn (string $id) => '<h3 id="' . $id . '">' . $titles[$id] . '</h3>';

$logo = fn (string $file) => brandUrl($file);
$photo = function (string $name, int $width = 800) {
    $file = page('home')?->image($name);
    return $file ? $file->thumb(['width' => $width, 'height' => intdiv($width * 2, 3), 'crop' => true])->url() : null;
};
$img = function (string $name, string $class = '') use ($page) {
    $file = $page->image("bg-$name.jpg");
    return $file ? '<img' . ($class ? ' class="' . $class . '"' : '') . ' src="' . $file->url() . '" alt="' . $file->alt()->esc() . '" width="' . $file->width() . '" height="' . $file->height() . '" loading="lazy">' : '';
};
$photos = ['persone' => $photo('iscriviti.jpg'), 'unilife' => $photo('studi.jpg'), 'laureati' => $photo('laureati.jpg')];

$values = [
    ['Responsabilità', 'Affidabile', 'Riflette impegno e dedizione, mettendo sempre al centro le necessità studentesche e sociali.'],
    ['Rispetto', 'Empatico', 'Pone al centro l\'ascolto e la valorizzazione dell\'individualità, trattando ogni argomento con sensibilità e considerazione.'],
    ['Fiducia', 'Rassicurante', 'Costruisce un legame con il pubblico basato sulla correttezza e sulla verificabilità delle informazioni.'],
    ['Trasparenza', 'Chiaro', 'Semplice e diretto, garantisce che le informazioni siano facilmente comprensibili, accessibili a tutti e mai ambigue.'],
    ['Integrità', 'Autentico', 'Un tono diretto e sincero che riflette trasparenza e onestà, evitando ambiguità o informazioni fuorvianti.'],
    ['Professionalità', 'Esperto', 'Informato e competente, che mostra padronanza del tema e riflette un approccio professionale.'],
    ['Sostenibilità', 'Informato', 'Scientifico, dimostra una profonda comprensione delle questioni ambientali e trasmette informazioni con autorità, sottolineando l\'importanza di azioni sostenibili.'],
    ['Inclusività', 'Accogliente', 'Una comunicazione calorosa e invitante, che pone l\'enfasi sull\'unità, sul rispetto e sulla celebrazione delle diversità.'],
    ['Equità', 'Imparziale', 'Riflette equilibrio e giustizia, evitando favoritismi o pregiudizi.'],
];

$headline = [
    ['Titolo', '72 px', 'Bold (700)', 'bg-type--display'],
    ['H1', '56 px', 'Demi-bold (600)', 'bg-type--h1'],
    ['H2', '40 px', 'Demi-bold (600)', 'bg-type--h2'],
    ['H3', '32 px', 'Demi-bold (600)', 'bg-type--h3'],
    ['H4', '22 px', 'Demi-bold (600)', 'bg-type--h4'],
];
$bodies = [['Corpo grande', '22 px', 'Regular (400)', 'bg-type--l'], ['Corpo', '18 px', 'Regular (400)', 'bg-type--m'], ['Corpo piccolo', '14 px', 'Regular (400)', 'bg-type--s']];

$colors = [
    ['Accento', '#742EFF', 'accent', 'Colore del corso: marchio ed enfasi.'],
    ['Secondario', '#1A315D', 'secondary', 'Blu notte di Ateneo: titoli, menu, sfondi scuri.'],
    ['Supporto', '#E8EAEF', 'support', 'Fondi chiari che distinguono blocchi e riquadri.'],
    ['Testo', '#171718', 'text', 'Testo corrente e marchio in nero.'],
];

$contrasts = [
    ['Testo #171718 su Supporto #E8EAEF', '14,88:1', 'AAA', 'Testo di ogni dimensione'],
    ['Testo #171718 su bianco', '17,92:1', 'AAA', 'Testo di ogni dimensione'],
    ['Bianco su Secondario #1A315D', '12,79:1', 'AAA', 'Testo di ogni dimensione, sfondi scuri'],
    ['Secondario #1A315D su Supporto #E8EAEF', '10,63:1', 'AAA', 'Testo di ogni dimensione'],
    ['Accento #742EFF su bianco', '5,87:1', 'AA', 'Titoli grandi, enfasi, link: non per testi lunghi o piccoli'],
    ['Accento #742EFF su Supporto #E8EAEF', '4,88:1', 'AA', 'Titoli grandi ed enfasi'],
    ['Accento #742EFF su Testo #171718', '3,05:1', 'Solo grandi', 'Solo testi grandi e grafica, da evitare'],
    ['Accento #742EFF su Secondario #1A315D', '2,18:1', 'No', 'Mai: non è leggibile'],
];

$donts = [
    ['Non deformare', 'bg-dont--stretch'],
    ['Non ruotare', 'bg-dont--rotate'],
    ['Non cambiare i colori', 'bg-dont--hue'],
    ['Non aggiungere ombre o effetti', 'bg-dont--shadow'],
    ['Non usare l\'accento come sfondo', 'bg-dont--accent'],
    ['Non scendere sotto la dimensione minima', 'bg-dont--tiny'],
];
$shots = ['Impostati', 'Scatti scuri', 'Inquadrature non significative', 'Soggetti non definiti', 'Punti di ripresa estremi', 'Distorsioni ed effetti di colore', 'Immagini di bassa qualità', 'Controluce'];
$note = fn (string $text) => '<p class="bg-note">[DA VERIFICARE: ' . $text . ']</p>';
?>
<?php snippet('layout', slots: true) ?>
  <?php slot('sidebar') ?>
  <nav class="bg-nav" aria-label="Indice delle brand guidelines">
    <p class="bg-nav__title">V01 · 2026</p>
    <ol>
      <?php foreach ($toc as $id => [$title, $subs]): ?>
      <li>
        <a href="#<?= $id ?>"><?= $title ?><?= in_array($id, $wip, true) ? ' <span class="bg-wip" title="In lavorazione">WIP</span>' : '' ?></a>
        <?php if ($subs): ?>
        <ol>
          <?php foreach ($subs as $subId => $subTitle): ?>
          <li><a href="#<?= $subId ?>"><?= $subTitle ?><?= in_array($subId, $wip, true) ? ' <span class="bg-wip" title="In lavorazione">WIP</span>' : '' ?></a></li>
          <?php endforeach ?>
        </ol>
        <?php endif ?>
      </li>
      <?php endforeach ?>
    </ol>
  </nav>
  <?php endslot() ?>

  <?php slot() ?>
  <?= css('assets/css/brand.css') ?>
  <div class="bg">

    <section aria-labelledby="perche-brand">
      <?= $h2('perche-brand') ?>
      <blockquote class="bg-quote">
        <p>“A brand is a person's gut feeling about a product, service, or organization.”</p>
        <footer>Marty Neumeier</footer>
      </blockquote>
      <blockquote class="bg-quote">
        <p>“Brands are results, established in the mind, built over time. Repeated actions create strong patterns and people's minds like patterns. The clearer the pattern, the more identifiable the brand.”</p>
        <footer>Koto Studio</footer>
      </blockquote>
      <p>Mantenere la coerenza delle comunicazioni, del tono di voce e degli asset visivi è di fondamentale importanza per aiutare gli utenti a riconoscere il nostro marchio e a creare fiducia. Le linee guida del marchio aiutano il nostro team a fornire contenuti coerenti in tutti i nostri punti di contatto: brochure, testi sul sito web e sui social media, newsletter e pubblicità.</p>
      <div class="bg-grid bg-grid--2">
        <div class="bg-card">
          <h3>Capire il concept di IEA</h3>
          <p>Le sezioni di questo documento contengono sia informazioni tecniche, sia i principi chiave alla base del brand IEA. Comprendere i principi di un brand significa poter utilizzare al meglio gli strumenti e gli elementi di cui si compone.</p>
        </div>
        <div class="bg-card">
          <h3>Scoprire gli elementi visivi</h3>
          <p>Troverai il brand IEA scomposto in tutti i suoi elementi, e declinato in tutte le sue versioni. In ogni sezione vedrai come trattare gli elementi in base ai razionali che li hanno definiti, inoltre avrai gli strumenti per capire come applicarli nei possibili contesti digitali.</p>
        </div>
      </div>
    </section>

    <!-- 1. ESSENZA -->
    <section aria-labelledby="essenza">
      <?= $h2('essenza') ?>
      <p class="bg-intro">Posizionamento, manifesto, valori, personalità e tono di voce, target, inclusività e best practice.</p>

      <?= $h3('posizionamento') ?>
      <div class="bg-grid bg-grid--2">
        <div class="bg-card"><h4>Visione</h4><p>Coltivare una nuova generazione di professionisti ispirati dalla trasformazione responsabile dell'ambiente costruito, capaci di immaginare, progettare e gestire la città e il paesaggio del futuro, guidati da valori di innovazione, inclusività e impatto positivo per la comunità.</p></div>
        <div class="bg-card"><h4>Missione</h4><p>Formare progettisti e innovatori capaci di integrare tecnica e creatività per generare soluzioni architettoniche e ingegneristiche sostenibili, nel rispetto della storia, del territorio e delle esigenze della società contemporanea.</p></div>
      </div>

      <?= $h3('manifesto') ?>
      <p>Crediamo nell'architettura e nell'ingegneria come strumenti di progresso umano, con al centro sostenibilità, sicurezza, etica e bellezza per progettare spazi che parlino alle persone, tutelino il pianeta e anticipino i bisogni di domani con spirito critico.</p>
      <p>In questa visione, la figura ibrida dell'ingegnere-architetto è chiave: erede di una tradizione culturale radicata nei maestri greci, romani, medievali e rinascimentali, capace di integrare con equilibrio tecnica e arte. È questo bilanciamento olistico che fa la forza e la qualità dei progetti e rappresenta il cuore del nostro corso.</p>

      <?= $h3('valori') ?>
      <ul class="bg-chips">
        <?php foreach ($values as [$value]): ?><li><?= $value ?></li><?php endforeach ?>
      </ul>

      <?= $h3('personalita') ?>
      <p>Il modo in cui IEA comunica definisce profondamente la relazione con le persone a cui si rivolge. Ma qual è la differenza tra personalità e tono di voce? Se pensiamo al brand IEA come se fosse una persona, possiamo dire che la voce è la sua personalità, derivata dai suoi valori. Il tono è invece contingente a un certo «momento», inteso come particolare media e contesto di applicazione della comunicazione del brand: si esprime attraverso aggettivi e caratteristiche peculiari, derivate dai valori ma strettamente relative alla comunicazione.</p>
      <table class="bg-table">
        <caption class="visually-hidden">Dai valori al tono di voce</caption>
        <thead><tr><th scope="col">Valore</th><th scope="col">Tono</th><th scope="col">Come suona</th></tr></thead>
        <tbody>
          <?php foreach ($values as [$value, $tone, $text]): ?>
          <tr><th scope="row"><?= $value ?></th><td><strong><?= $tone ?></strong></td><td><?= $text ?></td></tr>
          <?php endforeach ?>
        </tbody>
      </table>

      <?= $h3('target') ?>
      <p>Il tono di voce di IEA è target-driven: è basato sulla persona o sull'organizzazione a cui si rivolge, e usa poche regole precise per evitare la frammentazione e mantenere consistenza comunicativa. Il soggetto o i pronomi personali seguono regole diverse a seconda dei gruppi di attori a cui ci si rivolge.</p>
      <div class="bg-grid bg-grid--4">
        <div class="bg-card"><h4>Livello 1</h4><ul><li>Aspiranti studenti</li><li>Matricole</li><li>Studenti</li><li>Dottorandi</li><li>Alumni</li></ul></div>
        <div class="bg-card"><h4>Livello 2</h4><ul><li>Docenti</li><li>Personale tecnico e amministrativo</li><li>Comunità scientifica</li><li>Professionisti</li><li>Istituzioni</li></ul></div>
        <div class="bg-card"><h4>Livello 3</h4><ul><li>Imprese</li><li>Stampa</li></ul></div>
        <div class="bg-card"><h4>Livello 4</h4><ul><li>Cittadinanza</li></ul></div>
      </div>

      <?= $h3('come-si-scrive') ?>
      <p>Il tono tiene conto del media e del particolare evento. Privilegia frasi brevi, strutture lineari e termini di uso comune, evitando sigle o acronimi non di uso comune e un registro troppo colloquiale.</p>
      <table class="bg-table">
        <thead><tr><th scope="col">Target</th><th scope="col">Tono</th><th scope="col">Obiettivi</th></tr></thead>
        <tbody>
          <tr><td>Aspiranti studenti, matricole, studenti, dottorandi, alumni</td><td>Accogliente, chiaro, diretto: <strong>amichevole</strong></td><td>Engagement, informazione</td></tr>
          <tr><td>Docenti, personale, comunità scientifica, professionisti, istituzioni, imprese, stampa, cittadinanza</td><td>Informato, esperto, chiaro: <strong>istituzionale</strong></td><td>Informare, divulgare, rappresentare</td></tr>
        </tbody>
      </table>

      <?= $h3('esempi-di-tono') ?>
      <div class="bg-grid bg-grid--2">
        <div class="bg-card">
          <h4>Tono amichevole</h4>
          <p class="bg-q">Come parla IEA quando usa un tono accogliente, chiaro, autentico?</p>
          <p><strong>Soggetto:</strong> «Egli», terza persona singolare. Il pronome personale soggetto può sempre essere sottinteso.</p>
          <blockquote class="bg-example">Il Corso IEA è tra i primi fondati in Italia: l'organizzazione è ben collaudata e in continuo miglioramento.</blockquote>
          <p class="bg-q">Come parla quando invita all'azione?</p>
          <p><strong>«Noi diamo del tu».</strong> Il pronome personale soggetto può sempre essere sottinteso. Evitare il gerundio, l'ironia e l'irriverenza.</p>
          <blockquote class="bg-example">Dopo la laurea potrai entrare a far parte di una vasta comunità politecnica di Alumni.</blockquote>
          <p class="bg-q">Come parla quando è necessario esprimere il soggetto?</p>
          <p><strong>Soggetto:</strong> IEA o «il Corso IEA».</p>
          <blockquote class="bg-example">L'Open Day IEA, dedicato alla presentazione di tutti gli esami del corso, si svolge una volta all'anno, in una data stabilita dall'Ateneo.</blockquote>
        </div>
        <div class="bg-card">
          <h4>Tono istituzionale</h4>
          <p class="bg-q">Come parla IEA quando usa un tono informato, esperto, chiaro?</p>
          <p><strong>Soggetto:</strong> «il Corso di Laurea in Ingegneria Edile Architettura»; è preferibile il soggetto espresso.</p>
          <blockquote class="bg-example">Il Corso di Laurea in Ingegneria Edile Architettura abilita, dopo l'esame di Stato, all'iscrizione ai due Ordini professionali, degli Architetti e degli Ingegneri.</blockquote>
          <p class="bg-q">Come parla quando invita all'azione?</p>
          <p>Forma impersonale, senza il soggetto parlante e senza l'interlocutore. Evitare il gerundio e preferire l'infinito.</p>
          <blockquote class="bg-example">Presentare la domanda di partecipazione entro la scadenza indicata nel bando. Allegare i documenti richiesti senza superare i 15 MB totali.</blockquote>
        </div>
      </div>

      <?= $h3('inclusivita') ?>
      <p>La comunicazione del corso non discrimina per genere, provenienza o condizione. Il corso è aperto a chi studia e a chi vorrebbe studiare qui, comprese le persone con disabilità e con disturbi specifici dell'apprendimento: ogni contenuto deve poter essere letto, capito e usato da tutte e tutti.</p>
      <ul>
        <li>Rivolgersi a chi legge con forme neutre o con il doppio genere: «chi studia», «studenti e studentesse», «iscritte e iscritti». Non usare il maschile come forma universale.</li>
        <li>Non fare ipotesi su età, famiglia, provenienza o condizione economica di chi legge.</li>
        <li>Scegliere immagini che valorizzino la diversità di genere, età, origine e condizione, senza stereotipi (vedi <a href="#linguaggio">Linguaggio</a> nelle immagini).</li>
        <li>Garantire l'accessibilità: contrasto, testo alternativo alle immagini, link che descrivono la destinazione (vedi <a href="#accessibilita">Accessibilità</a>).</li>
      </ul>

      <?= $h3('best-practice') ?>
      <ul>
        <li><strong>Una frase, un'informazione.</strong> Al massimo 25 parole per frase e 3-4 frasi per paragrafo.</li>
        <li><strong>Forma attiva e verbi.</strong> «La commissione valuta la domanda», non «la domanda viene valutata»; «iscriviti entro», non «l'iscrizione dovrà essere effettuata entro».</li>
        <li><strong>Sigle sciolte</strong> alla prima occorrenza di ogni pagina: CFU (crediti formativi universitari), OFA (obblighi formativi aggiuntivi).</li>
        <li><strong>Titoli che anticipano il contenuto</strong> («Come iscriverti», non «Modalità di immatricolazione»), brevi, senza maiuscolo continuo né punti esclamativi.</li>
        <li><strong>Date, importi e requisiti in elenchi o tabelle.</strong> Date nel formato «23 agosto 2026», importi come «140,00 €».</li>
        <li><strong>Link descrittivi</strong>, mai «clicca qui» o «leggi tutto», e dichiarati quando portano a portali esterni.</li>
        <li><strong>Promemoria in tono di supporto:</strong> «Ricorda:» invece di «Attenzione:».</li>
      </ul>
    </section>

    <!-- 2. MARCHIO -->
    <section aria-labelledby="marchio">
      <?= $h2('marchio') ?>

      <?= $h3('panoramica') ?>
      <div class="bg-grid bg-grid--2 bg-grid--center">
        <div>
          <p>Il marchio del corso unisce l'acronimo IEA, in un carattere disegnato per il corso, e la denominazione per esteso «Corso di Laurea Magistrale in Ingegneria Edile Architettura». Si usa sempre insieme al marchio dell'Università di Pisa, che lo precede.</p>
          <p>Il marchio non si ridisegna e non si ricostruisce: si usano soltanto i file forniti. La dimensione minima di applicazione è di <strong>30 mm di altezza</strong> se utilizzato con il testo per esteso e di <strong>15 mm</strong> se usato solo con l'acronimo IEA.</p>
        </div>
        <div class="bg-stack">
          <div class="bg-logo-box"><img src="<?= $logo('iea-logo-stacked.svg') ?>" alt="Marchio IEA, versione verticale" width="439" height="203"></div>
          <div class="bg-logo-box"><img src="<?= $logo('iea-logo-horizontal.svg') ?>" alt="Marchio IEA, versione orizzontale" width="683" height="109"></div>
        </div>
      </div>

      <?= $h3('configurazioni') ?>
      <p>Il marchio esiste in quattro versioni. Si sceglie in base allo spazio disponibile:</p>
      <div class="bg-grid bg-grid--4">
        <figure class="bg-logo-box bg-logo-box--fig"><img src="<?= $logo('iea-logo-stacked.svg') ?>" alt="" width="439" height="203"><figcaption>Verticale</figcaption></figure>
        <figure class="bg-logo-box bg-logo-box--fig"><img src="<?= $logo('iea-logo-stacked-left.svg') ?>" alt="" width="439" height="203"><figcaption>Verticale a sinistra</figcaption></figure>
        <figure class="bg-logo-box bg-logo-box--fig"><img src="<?= $logo('iea-logo-horizontal.svg') ?>" alt="" width="683" height="109"><figcaption>Orizzontale</figcaption></figure>
        <figure class="bg-logo-box bg-logo-box--fig"><img src="<?= $logo('iea-logo-only.svg') ?>" alt="" width="221" height="101"><figcaption>Solo acronimo</figcaption></figure>
      </div>

      <?= $h3('schemi-colore') ?>
      <div class="bg-grid bg-grid--2">
        <figure class="bg-logo-box bg-logo-box--support"><img src="<?= $logo('iea-logo-stacked.svg') ?>" alt="Marchio a colori su fondo Supporto" width="439" height="203"><figcaption>A colori su Supporto</figcaption></figure>
        <figure class="bg-logo-box bg-logo-box--navy"><img class="bg-white" src="<?= $logo('iea-logo-stacked.svg') ?>" alt="Marchio in bianco su fondo Secondario" width="439" height="203"><figcaption>Bianco su Secondario</figcaption></figure>
        <figure class="bg-logo-box bg-logo-box--support"><img class="bg-black" src="<?= $logo('iea-logo-stacked.svg') ?>" alt="Marchio in nero su fondo Supporto" width="439" height="203"><figcaption>Nero su Supporto</figcaption></figure>
        <figure class="bg-logo-box bg-logo-box--black"><img class="bg-white" src="<?= $logo('iea-logo-stacked.svg') ?>" alt="Marchio in bianco su fondo Testo" width="439" height="203"><figcaption>Bianco su Testo</figcaption></figure>
      </div>

      <?= $h3('posizionamento-marchio') ?>
      <p>Il marchio si colloca in uno degli angoli del layout, allineato ai margini: in alto a sinistra, in alto a destra, in basso a sinistra o centrato in alto, con attorno uno spazio libero almeno pari all'altezza della «I» dell'acronimo.</p>
      <div class="bg-grid bg-grid--2">
        <div class="bg-frame bg-frame--tl"><img src="<?= $logo('iea-logo-horizontal.svg') ?>" alt="" width="683" height="109"><span class="bg-cap">In alto a sinistra</span></div>
        <div class="bg-frame bg-frame--tr"><img src="<?= $logo('iea-logo-stacked.svg') ?>" alt="" width="439" height="203"><span class="bg-cap">In alto a destra</span></div>
        <div class="bg-frame bg-frame--tc"><img src="<?= $logo('iea-logo-stacked.svg') ?>" alt="" width="439" height="203"><span class="bg-cap">Centrato in alto</span></div>
        <div class="bg-frame bg-frame--bl"><img src="<?= $logo('iea-logo-horizontal.svg') ?>" alt="" width="683" height="109"><span class="bg-cap">In basso a sinistra</span></div>
      </div>

      <?= $h3('marchio-ateneo') ?>
      <p>Il marchio del corso non sostituisce quello dell'Università di Pisa: compare accanto, separato da un filetto verticale, come nell'intestazione di questo sito. Il marchio di Ateneo va usato solo nei file ufficiali forniti dall'Ateneo, nei colori Pantone 541, bianco o nero.</p>
      <div class="bg-lockup" role="img" aria-label="Marchio dell'Università di Pisa, filetto e marchio IEA">
        <img src="<?= $logo('marchio_unipi_orizz_pant541.svg') ?>" alt="" width="297" height="100">
        <span aria-hidden="true"></span>
        <img src="<?= $logo('iea-logo-horizontal.svg') ?>" alt="" width="683" height="109">
      </div>

      <?= $h3('marchio-donts') ?>
      <div class="bg-grid bg-grid--3">
        <?php foreach ($donts as [$label, $class]): ?>
        <figure class="bg-dont <?= $class ?>">
          <div class="bg-dont__stage"><img src="<?= $logo('iea-logo-stacked.svg') ?>" alt="" width="439" height="203"></div>
          <figcaption><span class="bg-no">No</span> <?= $label ?></figcaption>
        </figure>
        <?php endforeach ?>
      </div>
      <p>Il marchio non si riscrive con un altro carattere, non si separa dalla denominazione quando il formato lo prevede completo e non si colloca su immagini in cui perde leggibilità.</p>
    </section>

    <!-- 3. TIPOGRAFIA -->
    <section aria-labelledby="tipografia">
      <?= $h2('tipografia') ?>

      <?= $h3('font-principale') ?>
      <div class="bg-grid bg-grid--2 bg-grid--center">
        <p>Per continuità e coerenza con il brand dell'Ateneo, anche il Corso di Laurea in Ingegneria Edile Architettura adotta come font primario, utilizzato per intestazioni e titolo di pagina, <strong>Inter</strong>.</p>
        <div class="bg-specimen bg-specimen--inter">
          <p class="bg-specimen__big">Fatti non foste a viver come bruti, ma per seguir virtute e canoscenza</p>
          <p class="bg-specimen__small">Ingegneria a Pisa vanta una tradizione centenaria.</p>
        </div>
      </div>

      <?= $h3('font-secondario') ?>
      <div class="bg-grid bg-grid--2 bg-grid--center">
        <p>Per continuità e coerenza con il brand dell'Ateneo, anche il Corso di Laurea in Ingegneria Edile Architettura adotta come font secondario, utilizzato per il corpo del testo, <strong>Titillium Web</strong>.</p>
        <div class="bg-specimen bg-specimen--titillium">
          <p class="bg-specimen__big">Fatti non foste a viver come bruti, ma per seguir virtute e canoscenza</p>
          <p class="bg-specimen__small">Ingegneria a Pisa vanta una tradizione centenaria.</p>
        </div>
      </div>

      <?= $h3('tipografia-web') ?>
      <p>Per continuità e coerenza con il brand dell'Ateneo e con le linee guida di composizione del layout di unipi.it, il corso adotta una struttura di pesi e dimensioni che ottimizza la leggibilità: <strong>Inter</strong> per i titoli H1, H2, H3 e H4, <strong>Titillium Web</strong> per il corpo del testo, gli elenchi puntati, le descrizioni e le citazioni.</p>
      <table class="bg-table bg-scale">
        <caption class="visually-hidden">Scala tipografica su web</caption>
        <thead><tr><th scope="col">Livello</th><th scope="col">Dimensione</th><th scope="col">Peso</th><th scope="col">Esempio</th></tr></thead>
        <tbody>
          <?php foreach ($headline as [$name, $size, $weight, $class]): ?>
          <tr><th scope="row"><?= $name ?></th><td><?= $size ?></td><td><?= $weight ?></td><td class="bg-type bg-type--inter <?= $class ?>"><?= $name === 'Titolo' ? 'IEA Pisa' : 'Ingegneria a Pisa' ?></td></tr>
          <?php endforeach ?>
          <?php foreach ($bodies as [$name, $size, $weight, $class]): ?>
          <tr><th scope="row"><?= $name ?></th><td><?= $size ?></td><td><?= $weight ?></td><td class="bg-type bg-type--titillium <?= $class ?>">Ingegneria a Pisa vanta una tradizione centenaria.</td></tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <p>Regole d'uso: testo allineato a sinistra; righe di 60-75 caratteri; interlinea di circa 1,5 per il corpo e 1,1-1,2 per i titoli; maiuscolo continuo solo per piccole etichette; un solo livello di titolo per volta, senza saltare da H1 a H3.</p>
    </section>

    <!-- 4. COLORE -->
    <section aria-labelledby="colore">
      <?= $h2('colore') ?>

      <?= $h3('palette') ?>
      <div class="bg-palette">
        <?php foreach ($colors as [$name, $hex, $slug, $text]): ?>
        <div class="bg-swatch bg-swatch--<?= $slug ?>">
          <div class="bg-swatch__tints" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
          <div class="bg-swatch__main"><strong><?= $name ?></strong><code><?= $hex ?></code><small><?= $text ?></small></div>
          <div class="bg-swatch__shades" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
        </div>
        <?php endforeach ?>
      </div>

      <?= $h3('colore-utilizzo') ?>
      <div class="bg-grid bg-grid--2 bg-grid--center">
        <div>
          <p>L'utilizzo del colore di Accento è sempre da usare in modo limitato, per enfatizzare i concetti principali e focalizzare l'attenzione sui punti o sui contenuti chiave.</p>
          <p><strong>Non è previsto l'uso del colore di Accento come colore di sfondo</strong> di impaginati e layout.</p>
        </div>
        <div class="bg-combos" role="img" aria-label="Tre combinazioni: Accento come filetto, Testo su fondo bianco o scuro, sfondo Secondario, bianco o Testo">
          <div class="bg-combo bg-combo--1"><span>Accento</span><span>Testo</span><span>Sfondo</span></div>
          <div class="bg-combo bg-combo--2"><span>Accento</span><span>Testo</span><span>Sfondo</span></div>
          <div class="bg-combo bg-combo--3"><span>Accento</span><span>Testo</span><span>Sfondo</span></div>
        </div>
      </div>

      <?= $h3('accessibilita') ?>
      <p>Per mantenere alti gli standard di accessibilità, va sempre controllato che il contrasto tra testo e sfondo sia alto: il livello AAA (almeno 7:1) è l'obiettivo per i contenuti informativi e per i testi; il livello AA (almeno 4,5:1) è il minimo ammesso per il testo corrente e può essere usato per i testi grandi, da 24 px in su. Per verificare una combinazione si può usare <a href="https://webaim.org/resources/contrastchecker/" rel="external">il verificatore di contrasto di WebAIM</a>.</p>
      <table class="bg-table">
        <caption class="visually-hidden">Contrasto delle combinazioni di colore</caption>
        <thead><tr><th scope="col">Combinazione</th><th scope="col">Contrasto</th><th scope="col">Livello</th><th scope="col">Uso</th></tr></thead>
        <tbody>
          <?php foreach ($contrasts as [$pair, $ratio, $level, $use]): ?>
          <tr><th scope="row"><?= $pair ?></th><td><?= $ratio ?></td><td><strong><?= $level ?></strong></td><td><?= $use ?></td></tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </section>

    <!-- 5. IMMAGINI -->
    <section aria-labelledby="immagini">
      <?= $h2('immagini') ?>

      <?= $h3('stile') ?>
      <p>Le immagini sono protagoniste nella comunicazione del corso: raccontano storie, riflettono i valori e l'identità complessa di Ingegneria Edile-Architettura Pisa. La qualità visiva è essenziale per trasmettere l'esperienza unica di chi studia e vive questo percorso.</p>
      <div class="bg-grid bg-grid--4">
        <div class="bg-card bg-card--photo bg-card--tall"><?= $img('stile-shooting') ?><h4>Shooting</h4><p>Servizi fotografici specifici realizzati per esigenze istituzionali e comunicative, fondamentali per progetti ad alta personalizzazione e rilevanza.</p></div>
        <div class="bg-card bg-card--photo bg-card--tall"><?= $img('stile-banche') ?><h4>Banche immagini</h4><p>Risorsa quotidiana per immagini attuali, editoriali e tematiche, utili a mantenere freschezza e pertinenza nei contenuti.</p></div>
        <div class="bg-card bg-card--photo bg-card--tall"><?= $img('stile-ia') ?><h4>IA</h4><p>Immagini e arte generate da IA possono essere utilizzate per facilitare e arricchire la produzione di contenuti editoriali e promozionali.</p></div>
        <div class="bg-card bg-card--photo bg-card--tall bg-card--no"><?= $img('stile-amatoriali') ?><h4>Amatoriali</h4><p>Da evitare nella comunicazione istituzionale e promozionale, per garantire professionalità e coerenza al brand del corso.</p></div>
      </div>

      <?= $h3('linguaggio') ?>
      <div class="bg-grid bg-grid--2">
        <div class="bg-card bg-card--photo bg-card--square"><?= $img('ling-unicita') ?><h4>Unicità</h4><p>Evitare immagini artificiali o costruite; preferire ambienti e situazioni autentici e realistici, che colpiscano per originalità.</p></div>
        <div class="bg-card bg-card--photo bg-card--square"><?= $img('ling-luminosita') ?><h4>Luminosità e colore</h4><p>I colori e la luce devono apparire naturali, evitando toni troppo forti o saturi. Il flash va evitato, soprattutto nelle foto amatoriali.</p></div>
        <div class="bg-card bg-card--photo bg-card--square"><?= $img('ling-soggetti') ?><h4>Soggetti</h4><p>Garantire che il soggetto sia ben rappresentato, in ambienti ordinati e privi di distrazioni.</p></div>
        <div class="bg-card bg-card--photo bg-card--square"><?= $img('ling-inclusivita') ?><h4>Inclusività</h4><p>Usare immagini che valorizzino la diversità politica, razziale, di genere e di età, evitando gli stereotipi.</p></div>
      </div>

      <?= $h3('tagli') ?>
      <p>Una buona inquadratura mette a fuoco il soggetto, è luminosa e nitida e racconta una situazione reale. Da evitare:</p>
      <div class="bg-grid bg-grid--4 bg-shots">
        <?php foreach (range(1, 8) as $n): ?>
        <?= $img('tagli-' . $n) ?>
        <?php endforeach ?>
      </div>

      <?= $h3('soggetti') ?>
      <article class="bg-subject">
        <div class="bg-subject__text"><h4>Persone</h4><p>Le immagini devono essere semplici e autentiche, mettendo in luce persone reali anziché modelli. È preferibile catturare interazioni naturali piuttosto che pose statiche, per raccontare relazioni genuine. Gli scatti ideali sono spontanei e offrono uno sguardo reale sulla vita universitaria. I soggetti devono apparire positivi, accoglienti e naturali, con una rappresentanza equilibrata di etnie, età e generi, riflettendo la diversità del nostro contesto.</p></div>
        <div class="bg-gallery bg-gallery--portrait">
          <?php foreach (range(1, 3) as $n): ?><?= $img('pers-' . $n) ?><?php endforeach ?>
        </div>
      </article>
      <article class="bg-subject">
        <div class="bg-subject__text"><h4>Architettura</h4><p>Le immagini devono mettere in risalto ciò che rende unico il nostro corso e l'ateneo pisano, inserito in un contesto urbano ricco di storia e servizi. I luoghi devono apparire vivi, contemporanei e accoglienti, con un dinamismo dato dalla presenza delle persone in armonia con l'ambiente universitario.</p></div>
        <div class="bg-gallery bg-gallery--portrait">
          <?php foreach (range(1, 3) as $n): ?><?= $img('arch-' . $n) ?><?php endforeach ?>
        </div>
      </article>
      <article class="bg-subject">
        <div class="bg-subject__text"><h4>Natura</h4><p>Lo stile fotografico per soggetti naturali, paesaggi e aree urbane deve essere semplice e autentico, valorizzando la luce naturale e composizioni pulite. Nelle aree urbane è importante mostrare luoghi vivi e accoglienti, integrando la presenza umana per creare dinamismo e raccontare relazioni autentiche con l'ambiente. L'obiettivo è catturare momenti reali e atmosfere genuine, evitando artificiosità e privilegiando armonia, profondità ed equilibrio visivo.</p></div>
        <div class="bg-gallery bg-gallery--wide">
          <?php foreach (range(1, 4) as $n): ?><?= $img('nat-' . $n) ?><?php endforeach ?>
        </div>
      </article>
      <article class="bg-subject">
        <div class="bg-subject__text"><h4>Dettagli</h4><p>La fotografia di dettagli, macro, pattern e texture si concentra sull'esaltazione di elementi minuti, rivelando forme e trame spesso invisibili a occhio nudo. Utilizza luci morbide e laterali per valorizzare rilievi e profondità, e una messa a fuoco precisa per catturare la nitidezza dei particolari. Questo approccio trasforma superfici e motivi in soggetti artistici e trasforma ripetizioni, moduli e dettagli tecnici in elementi che toccano la soglia dell'astratto.</p></div>
        <div class="bg-gallery bg-gallery--wide">
          <?php foreach (range(1, 4) as $n): ?><?= $img('det-' . $n) ?><?php endforeach ?>
        </div>
      </article>
      <article class="bg-subject">
        <div class="bg-subject__text"><h4>Uni life</h4><p>Le fotografie della vita universitaria devono catturare momenti autentici e quotidiani, riflettendo i valori del corso di laurea: inclusività, collaborazione, impegno e crescita personale. Devono mostrare studenti e docenti impegnati nello studio, nel confronto e nelle attività sociali, con un'atmosfera positiva e accogliente. Le immagini raccontano storie di passione, innovazione e appartenenza, offrendo uno sguardo sincero e dinamico sulla vita dentro e fuori le aule.</p></div>
        <div class="bg-gallery bg-gallery--wide">
          <?php foreach (range(1, 4) as $n): ?><?= $img('uni-' . $n) ?><?php endforeach ?>
        </div>
      </article>

      <?= $h3('trattamenti') ?>
      <p>Le fotografie si usano a colori. Per esigenze grafiche sono ammessi tre trattamenti, sempre con i colori della palette e mai con l'Accento come sfondo:</p>
      <div class="bg-grid bg-grid--3">
        <?php foreach ([['bg-treat--bn', 'Bianco e nero', 'Per foto con luce poco naturale o per dare sobrietà a un contenuto istituzionale.'], ['bg-treat--duo', 'Bicolore', 'Blu notte e bianco: per sfondi di locandine, copertine e intestazioni.'], ['bg-treat--grad', 'Gradiente', 'Sfumatura blu notte dal basso: dà leggibilità a un titolo sulla foto.']] as [$class, $name, $text]): ?>
        <figure class="bg-treat <?= $class ?>">
          <div class="bg-treat__image"><?php if ($photos['laureati']): ?><img src="<?= $photos['laureati'] ?>" alt="" width="800" height="533" loading="lazy"><?php endif ?></div>
          <figcaption><strong><?= $name ?></strong><span><?= $text ?></span></figcaption>
        </figure>
        <?php endforeach ?>
      </div>

      <?= $h3('rapporto-marchio') ?>
      <p>Il marchio su una fotografia si colloca in una zona uniforme e libera, lontano da volti e dettagli. Sulle immagini scure o sul gradiente si usa la versione bianca, su quelle chiare la versione a colori o nera. Non si applicano riquadri, bordi o ombre: si cambia l'immagine o la versione del marchio.</p>
    </section>

    <!-- 6. FORMAT -->
    <section aria-labelledby="format">
      <?= $h2('format') ?>

      <?= $h3('concept') ?>
      <p>Un format è un impaginato che si ripete: stessa posizione del marchio, stessa gerarchia di titolo e testo, stesso uso del colore, con contenuti che cambiano di volta in volta. Serve a riconoscere subito il corso, qualunque sia il mezzo, e a produrre materiali più in fretta.</p>
      <p>Un format può essere <strong>grafico</strong>, <strong>tipografico</strong> o <strong>basato su immagini</strong>. In ogni caso rispetta la palette del corso, la tipografia (Inter per i titoli, Titillium Web per il testo) e le fasce di rispetto: lo spazio libero attorno al marchio e i margini del formato.</p>
      <div class="bg-grid bg-grid--3">
        <div class="bg-card"><h4>Grafico</h4><p>Forme, campiture e filetti nei colori della palette, senza fotografie. Adatto a locandine, copertine e comunicazioni di servizio.</p></div>
        <div class="bg-card"><h4>Tipografico</h4><p>Il testo è il protagonista: un titolo in Inter ben dimensionato, con poche parole e un solo accento di colore.</p></div>
        <div class="bg-card"><h4>Basato su immagini</h4><p>Una fotografia protagonista, scelta secondo le regole della sezione Immagini, con titolo e marchio in zone libere.</p></div>
      </div>

      <?= $h3('composizione') ?>
      <p>La composizione è libera. Si consiglia però di seguire le buone prassi della percezione visiva, in particolare i principi della psicologia della Gestalt: servono a far capire un impaginato prima ancora di leggerlo.</p>
      <div class="bg-grid bg-grid--3 bg-gestalt">
        <figure class="bg-card">
          <svg viewBox="0 0 120 70" aria-hidden="true"><g class="s"><circle cx="12" cy="22" r="5"/><circle cx="26" cy="22" r="5"/><circle cx="40" cy="22" r="5"/><circle cx="80" cy="22" r="5"/><circle cx="94" cy="22" r="5"/><circle cx="108" cy="22" r="5"/><circle cx="12" cy="48" r="5"/><circle cx="26" cy="48" r="5"/><circle cx="40" cy="48" r="5"/><circle cx="80" cy="48" r="5"/><circle cx="94" cy="48" r="5"/><circle cx="108" cy="48" r="5"/></g></svg>
          <figcaption><h4>Prossimità</h4><p>Ciò che è vicino si legge come un gruppo. Tieni insieme titolo, testo e link che parlano della stessa cosa e separali dal resto con lo spazio.</p></figcaption>
        </figure>
        <figure class="bg-card">
          <svg viewBox="0 0 120 70" aria-hidden="true"><g class="s"><circle cx="16" cy="16" r="6"/><circle cx="44" cy="16" r="6"/><circle cx="72" cy="16" r="6"/><circle cx="100" cy="16" r="6"/><circle cx="16" cy="54" r="6"/><circle cx="100" cy="54" r="6"/><circle cx="16" cy="35" r="6"/><circle cx="44" cy="54" r="6"/><circle cx="100" cy="35" r="6"/></g><g class="a"><circle cx="44" cy="35" r="6"/><circle cx="72" cy="35" r="6"/><circle cx="72" cy="54" r="6"/></g></svg>
          <figcaption><h4>Somiglianza</h4><p>Elementi con lo stesso colore, la stessa forma o la stessa dimensione hanno la stessa funzione. L'Accento è per ciò che conta davvero, e solo per quello.</p></figcaption>
        </figure>
        <figure class="bg-card">
          <svg viewBox="0 0 120 70" aria-hidden="true"><path class="line" d="M6 50 C 30 6, 52 6, 62 34 S 94 62, 114 18" fill="none"/><g class="s"><circle cx="6" cy="50" r="4"/><circle cx="30" cy="22" r="4"/><circle cx="62" cy="34" r="4"/><circle cx="88" cy="50" r="4"/><circle cx="114" cy="18" r="4"/></g></svg>
          <figcaption><h4>Continuità</h4><p>L'occhio segue linee e allineamenti. Allinea gli elementi a una griglia e ai margini del formato, in modo che il percorso di lettura sia evidente.</p></figcaption>
        </figure>
        <figure class="bg-card">
          <svg viewBox="0 0 120 70" aria-hidden="true"><rect class="frame" x="4" y="4" width="112" height="62"/><rect class="respect" x="16" y="14" width="88" height="42"/><rect class="a" x="52" y="26" width="20" height="18"/></svg>
          <figcaption><h4>Spazio e fasce di rispetto</h4><p>Il vuoto attorno a un elemento lo fa emergere dallo sfondo. Lascia libero lo spazio attorno al marchio e ai margini: non è spazio sprecato.</p></figcaption>
        </figure>
        <figure class="bg-card">
          <svg viewBox="0 0 120 70" aria-hidden="true"><rect class="t" x="8" y="10" width="84" height="12"/><rect class="s" x="8" y="32" width="60" height="6"/><rect class="s" x="8" y="44" width="70" height="6"/><rect class="s" x="8" y="56" width="46" height="6"/></svg>
          <figcaption><h4>Gerarchia</h4><p>Dimensione, peso e contrasto indicano cosa leggere per primo. Un titolo, un sottotitolo, un testo: tre livelli bastano.</p></figcaption>
        </figure>
        <figure class="bg-card">
          <svg viewBox="0 0 120 70" aria-hidden="true"><rect class="frame" x="4" y="4" width="112" height="62"/><path class="grid" d="M41 4v62M79 4v62M4 25h112M4 45h112"/><circle class="a" cx="79" cy="25" r="7"/></svg>
          <figcaption><h4>Equilibrio e terzi</h4><p>Dividi il formato in tre parti per lato e metti il soggetto su una linea o su un incrocio: la composizione è più viva di una centrata e simmetrica.</p></figcaption>
        </figure>
      </div>

      <?= $h3('format-applicazione') ?>
      <div class="bg-grid bg-grid--4">
        <div class="bg-placeholder bg-placeholder--tall">Esempio da aggiungere</div>
        <div class="bg-placeholder bg-placeholder--tall">Esempio da aggiungere</div>
        <div class="bg-placeholder bg-placeholder--tall">Esempio da aggiungere</div>
        <div class="bg-placeholder bg-placeholder--tall">Esempio da aggiungere</div>
      </div>
    </section>

    <!-- 7. APPLICAZIONI -->
    <section aria-labelledby="applicazioni">
      <?= $h2('applicazioni') ?>
      <?= $note('nel file Figma le pagine delle applicazioni mostrano solo i mockup, senza testi, tranne la firma email. Le indicazioni qui sotto sono proposte sulla base del manuale di Ateneo e di questo documento.') ?>
      <div class="bg-grid bg-grid--2">
        <article class="bg-card" id="documenti"><h3>Documenti</h3><p>Carta intestata e documenti ufficiali: marchio in alto a sinistra, titoli in Inter, testo in Titillium Web, colori Testo e Secondario. L'Accento solo per un elemento di enfasi.</p></article>
        <article class="bg-card" id="cancelleria"><h3>Cancelleria</h3><p>Biglietti da visita, buste e cartelline: marchio nella versione orizzontale o verticale, fondo bianco o Supporto, nessun colore pieno di Accento.</p></article>
        <article class="bg-card" id="sito-web"><h3>Sito web</h3><p>Il sito del corso (<a href="<?= url() ?>"><?= parse_url(site()->url(), PHP_URL_HOST) ?></a>) segue la grafica di unipi.it: titoli Inter in blu notte, testo Titillium Web, link e pulsanti in blu reale, sfondi Supporto. L'Accento viola segnala il corso, per esempio nel marchio e nei filetti attivi.</p></article>
        <article class="bg-card" id="poster"><h3>Poster</h3><p>Fotografia a tutta pagina o fondo Secondario, titolo grande in Inter, marchio in basso o in alto a sinistra con la dimensione minima rispettata. Date e luogo in evidenza, un solo accento di colore.</p></article>
        <article class="bg-card" id="brochure"><h3>Brochure</h3><p>Copertina con fotografia e marchio; pagine interne con titoli Inter, testo Titillium Web, riquadri Supporto per date, requisiti e contatti.</p></article>
        <article class="bg-card" id="merchandising"><h3>Merchandising</h3><p>Marchio in bianco su Secondario o su Testo, oppure in nero su fondi chiari, nella versione solo acronimo se lo spazio è poco. Mai l'Accento come colore di fondo.</p></article>
        <article class="bg-card" id="newsletter"><h3>Newsletter</h3><p>Testata con il marchio, colonna singola larga al massimo 600 px, titoli in Inter (con carattere di riserva sicuro per le email), pulsanti in blu reale, link descrittivi, testo alternativo alle immagini.</p></article>
        <article class="bg-card" id="social-media">
          <h3>Social media</h3>
          <p>Avatar: acronimo IEA su Supporto o Secondario. Per ogni piattaforma si usa il format del corso:</p>
          <ul>
            <li><strong>Instagram:</strong> 1080 × 1350 px, verticale.</li>
            <li><strong>LinkedIn:</strong> 1200 × 627 px, orizzontale.</li>
            <li><strong>Facebook:</strong> 1200 × 630 px, orizzontale.</li>
          </ul>
          <p>Testi nel tono amichevole; testo alternativo alle immagini.</p>
        </article>
      </div>

      <?= $h3('firma-email') ?>
      <p>La firma email è utilizzata per le caselle di posta legate a figure impersonali del Corso di Laurea: segreteria@, info@, presidente@ e altre eventualmente create per la gestione delle relazioni con studenti e dottorandi.</p>
      <div class="bg-grid bg-grid--2 bg-grid--center">
        <div class="bg-sign" aria-label="Esempio di firma email">
          <p class="bg-sign__name"><strong>– Nome cognome</strong><br><em>Segreteria Studenti</em></p>
          <p>Corso di Laurea Magistrale in Ingegneria Edile Architettura<br>Università di Pisa<br>tel. +39 xxx xxxxxxx<br>mob. +39 xxx xxxxxxx<br>www.unipi.it<br>www.iea.ing.unipi.it</p>
        </div>
        <div>
          <table class="bg-table">
            <thead><tr><th scope="col">Font</th><th scope="col">Colore</th></tr></thead>
            <tbody>
              <tr><td>Helvetica bold, 12 px<br>Helvetica italic, 12 px<br>Helvetica regular, 12 px</td><td><span class="bg-dot bg-dot--black"></span> Nero #000000<br><span class="bg-dot bg-dot--grey"></span> Grigio antracite #1F1F1F</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </div>
  <script>
    // evidenzia nell'indice la sezione che si sta leggendo: l'ultima il cui titolo ha superato il quarto alto della finestra
    (() => {
      const links = [...document.querySelectorAll('.bg-nav a')];
      const targets = links.map((link) => [link, document.getElementById(link.hash.slice(1))]).filter(([, target]) => target);
      let waiting = false;
      const update = () => {
        waiting = false;
        const limit = window.innerHeight * 0.25;
        const current = targets.filter(([, target]) => target.getBoundingClientRect().top <= limit).pop()?.[0];
        links.forEach((link) => (link === current ? link.setAttribute('aria-current', 'true') : link.removeAttribute('aria-current')));
      };
      addEventListener('scroll', () => { if (!waiting) { waiting = true; requestAnimationFrame(update); } }, { passive: true });
      update();
    })();
  </script>
  <?php endslot() ?>
<?php endsnippet() ?>
