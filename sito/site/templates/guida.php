<?php
/** Guida per la redazione: indice delle sezioni e testo. Non va nei motori di ricerca. */
$kirby->response()->header('X-Robots-Tag', 'noindex, nofollow');
$sections = $page->text()->toBlocks()->filterBy('type', 'heading')->filter(fn ($block) => $block->level()->value() === 'h2');
?>
<?php snippet('layout', slots: true) ?>
  <div class="container page-body">
    <div class="content guide">
      <?php if ($sections->isNotEmpty()): ?>
      <nav class="guide-toc" aria-labelledby="guide-toc-title">
        <h2 id="guide-toc-title"><?= t('menu.section') ?></h2>
        <ol>
          <?php foreach ($sections as $section): ?>
          <li><a href="#<?= Str::slug(Str::unhtml($section->text())) ?>"><?= $section->text() ?></a></li>
          <?php endforeach ?>
        </ol>
      </nav>
      <?php endif ?>
      <?= $page->text()->toBlocks() ?>
    </div>
  </div>
<?php endsnippet() ?>
