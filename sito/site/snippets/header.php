<?php
/** Barra dei link rapidi, marchio, menu principale, ricerca e lingua */
$sections = $site->children()->listed();
$active   = $page->parents()->last() ?? $page; // sezione di primo livello della pagina corrente
?>
<nav class="quickbar" aria-label="<?= t('menu.quick') ?>">
  <div class="container">
    <ul>
      <?php foreach ($site->quicklinks()->toStructure() as $link): ?>
      <?php $url = $link->link()->toUrl() ?>
      <li><a href="<?= $url ?>"<?= isExternal($url) ? ' rel="external"' : '' ?>><?= $link->text()->esc() ?></a></li>
      <?php endforeach ?>
      <li><a href="<?= $kirby->url('panel') ?>"><?= t('panel') ?></a></li>
    </ul>
  </div>
</nav>

<header class="site-header">
  <div class="container site-header__inner">
    <a class="brand" href="<?= $site->url() ?>">
      <?php // sotto i 576px i marchi si riducono (cherubino e sola sigla IEA), così marchi e pulsanti stanno su una riga ?>
      <picture class="brand__unipi">
        <source media="(min-width: 36em)" srcset="<?= brandUrl('marchio_unipi_orizz_pant541.svg') ?>" width="297" height="100">
        <img src="<?= brandUrl('cherubino.svg') ?>" width="89" height="91" alt="<?= t('university') ?>">
      </picture>
      <span class="brand__rule" aria-hidden="true"></span>
      <picture class="brand__course">
        <source media="(min-width: 36em)" srcset="<?= brandUrl('iea-logo-horizontal.svg') ?>" width="683" height="109">
        <img src="<?= brandUrl('iea-logo-only.svg') ?>" width="221" height="101" alt="<?= t('course') ?>">
      </picture>
    </a>

    <?php $searchUrl = page('cerca')?->url() ?? url('cerca') ?>
    <div class="header-tools">
      <?php // senza JavaScript la lente porta alla pagina di ricerca; con JavaScript apre il campo al posto del menu ?>
      <a class="search-link" href="<?= $searchUrl ?>">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/></svg>
        <span class="visually-hidden"><?= t('search.label') ?></span>
      </a>
      <button class="search-toggle" type="button" aria-expanded="false" aria-controls="header-search">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/></svg>
        <span class="visually-hidden"><?= t('search.label') ?></span>
      </button>
      <ul class="lang-switch" aria-label="<?= t('language') ?>">
        <?php foreach ($kirby->languages()->sortBy('code', 'desc') as $language): ?>
        <li>
          <a href="<?= $page->url($language->code()) ?>" hreflang="<?= $language->code() ?>" lang="<?= $language->code() ?>"<?= $kirby->language()?->code() === $language->code() ? ' aria-current="true"' : '' ?>>
            <span aria-hidden="true"><?= $language->code() === 'it' ? 'ITA' : 'ENG' ?></span>
            <span class="visually-hidden"><?= $language->name() ?></span>
          </a>
        </li>
        <?php endforeach ?>
      </ul>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principale">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        <span class="visually-hidden"><?= t('menu') ?></span>
      </button>
    </div>

    <?php // campo di ricerca: con JavaScript prende il posto di tutta la riga e mostra i risultati mentre si scrive ?>
    <form class="header-search" id="header-search" role="search" action="<?= $searchUrl ?>" data-results="<?= $searchUrl ?>.json" hidden>
      <button class="header-search__submit" type="submit"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/></svg><span class="visually-hidden"><?= t('search.button') ?></span></button>
      <label class="visually-hidden" for="header-q"><?= t('search.label') ?></label>
      <input id="header-q" type="search" name="q" placeholder="<?= t('search.label') ?>…" autocomplete="off">
      <button class="header-search__close" type="button"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg><span class="visually-hidden"><?= t('search.close') ?></span></button>
      <p class="visually-hidden" role="status" data-search-status></p>
    </form>

    <nav class="main-nav" id="menu-principale" aria-label="<?= t('menu.main') ?>">
      <ul>
        <?php foreach ($sections as $section): ?>
        <li><a href="<?= $section->url() ?>"<?= $section->is($active) ? ' aria-current="' . ($section->is($page) ? 'page' : 'true') . '"' : '' ?>><?= $section->title()->esc() ?></a></li>
        <?php endforeach ?>
      </ul>
    </nav>
  </div>

  <div class="search-panel" id="search-panel" hidden>
    <div class="container">
      <p class="search-panel__count" data-search-count></p>
      <ul class="search-panel__results" data-search-results></ul>
      <p class="search-panel__all"><a class="more" href="<?= $searchUrl ?>" data-search-all><?= t('search.all') ?></a></p>
    </div>
  </div>
</header>
