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
      <img class="brand__unipi" src="<?= brandUrl('marchio_unipi_orizz_pant541.svg') ?>" width="297" height="100" alt="<?= t('university') ?>">
      <span class="brand__rule" aria-hidden="true"></span>
      <picture class="brand__course">
        <source media="(min-width: 30em)" srcset="<?= brandUrl('iea-logo-horizontal.svg') ?>" width="683" height="109">
        <img src="<?= brandUrl('iea-logo-only.svg') ?>" width="221" height="101" alt="<?= t('course') ?>">
      </picture>
    </a>

    <div class="header-tools">
      <a href="<?= page('cerca')?->url() ?? url('cerca') ?>">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/></svg>
        <span class="visually-hidden"><?= t('search.label') ?></span>
      </a>
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
        <?= t('menu') ?>
      </button>
    </div>

    <nav class="main-nav" id="menu-principale" aria-label="<?= t('menu.main') ?>">
      <ul>
        <?php foreach ($sections as $section): ?>
        <li><a href="<?= $section->url() ?>"<?= $section->is($active) ? ' aria-current="' . ($section->is($page) ? 'page' : 'true') . '"' : '' ?>><?= $section->title()->esc() ?></a></li>
        <?php endforeach ?>
      </ul>
    </nav>
  </div>
</header>
