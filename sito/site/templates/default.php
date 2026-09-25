<?php
$section    = $page->parents()->last() ?? $page;
$hasSection = $section->children()->listed()->isNotEmpty();
?>
<?php snippet('layout', slots: true) ?>
  <?php if ($hasSection): ?>
    <?php slot('sidebar') ?><?php snippet('section-nav') ?><?php endslot() ?>
  <?php endif ?>

  <?php slot() ?>
    <?php if ($hasSection): ?>
      <?= $page->text()->toBlocks() ?>
      <?php if ($page->is($section)): ?>
      <ul class="cards">
        <?php foreach ($section->children()->listed() as $child): ?>
        <li>
          <article class="card">
            <h2 class="card__title"><a href="<?= $child->url() ?>"><?= $child->title()->esc() ?></a></h2>
            <?php if (($summary = $child->description()->or($child->lead()))->isNotEmpty()): ?><p><?= $summary->esc() ?></p><?php endif ?>
          </article>
        </li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>
    <?php else: ?>
      <div class="container page-body"><div class="content"><?= $page->text()->toBlocks() ?></div></div>
    <?php endif ?>
  <?php endslot() ?>
<?php endsnippet() ?>
