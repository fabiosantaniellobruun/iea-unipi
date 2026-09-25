<?php /** Pagina non trovata: il testo è modificabile dal pannello, i link per ripartire si generano da soli */ ?>
<?php snippet('layout', slots: true) ?>
  <div class="container page-body">
    <div class="content">
      <h2><?= t('error.links') ?></h2>
      <ul class="sitemap">
        <li><a href="<?= $site->url() ?>"><?= t('home') ?></a></li>
        <?php foreach ($site->children()->listed() as $section): ?>
        <li><a href="<?= $section->url() ?>"><?= $section->title()->esc() ?></a></li>
        <?php endforeach ?>
        <?php if ($map = page('mappa-del-sito')): ?>
        <li><a href="<?= $map->url() ?>"><?= $map->title()->esc() ?></a></li>
        <?php endif ?>
      </ul>
    </div>
  </div>
<?php endsnippet() ?>
