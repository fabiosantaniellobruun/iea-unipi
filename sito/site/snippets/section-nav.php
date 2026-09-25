<?php
/** Menu della sezione di primo livello a cui appartiene la pagina */
$section = $page->parents()->last() ?? $page;
$items   = $section->children()->listed();
if ($items->isEmpty()) return;

// un post della bacheca attiva la voce della sua categoria
$current = $page->intendedTemplate()->name() === 'post' ? $page->categoryPage() : null;
?>
<nav class="section-nav" aria-labelledby="section-nav-title">
  <h2 id="section-nav-title"><?= $section->title()->esc() ?></h2>
  <ul>
    <li><a href="<?= $section->url() ?>"<?= $section->is($page) ? ' aria-current="page"' : '' ?>><?= t('section.overview', 'Panoramica') ?></a></li>
    <?php foreach ($items as $item): ?>
    <li><a href="<?= $item->url() ?>"<?= $item->is($page) ? ' aria-current="page"' : ($page->parents()->has($item) || $item->is($current) ? ' aria-current="true"' : '') ?>><?= $item->title()->esc() ?></a></li>
    <?php endforeach ?>
  </ul>
</nav>
