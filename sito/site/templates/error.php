<?php /** Pagina non trovata: foto a tutta larghezza con il testo e la ricerca; titolo e frase si modificano dal pannello */ ?>
<?php snippet('layout', slots: true) ?>
  <?= css('assets/css/notfound.css') ?>
  <section class="notfound">
    <img class="notfound__image" src="<?= url('assets/img/404-1200.webp') ?>"
         srcset="<?= url('assets/img/404-1200.webp') ?> 1200w, <?= url('assets/img/404-2400.webp') ?> 2400w" sizes="100vw"
         width="2400" height="1603" alt="" fetchpriority="high">
    <div class="container notfound__inner">
      <div class="notfound__panel">
        <p class="notfound__code" aria-hidden="true">404</p>
        <h1><?= $page->title()->esc() ?></h1>
        <p class="notfound__lead"><?= $page->lead()->esc() ?></p>
        <?php snippet('search-form') ?>
        <p class="notfound__home"><a href="<?= $site->url() ?>"><?= t('error.home') ?></a></p>
      </div>
    </div>
  </section>
<?php endsnippet() ?>
