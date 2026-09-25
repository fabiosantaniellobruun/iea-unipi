<?php
/** Pagina di contenuto a blocchi, con il menu della sezione se ne fa parte */
$section    = $page->parents()->last() ?? $page;
$hasSection = $section->children()->listed()->isNotEmpty();
?>
<?php snippet('layout', slots: true) ?>
  <?php if ($hasSection): ?>
    <?php slot('sidebar') ?><?php snippet('section-nav') ?><?php endslot() ?>
    <?php slot() ?>
      <?= $page->text()->toBlocks() ?>
      <?php snippet('unlinked-files', ['page' => $page]) ?>
    <?php endslot() ?>
  <?php else: ?>
    <?php slot() ?>
      <div class="container page-body">
        <div class="content">
          <?= $page->text()->toBlocks() ?>
          <?php snippet('unlinked-files', ['page' => $page]) ?>
        </div>
      </div>
    <?php endslot() ?>
  <?php endif ?>
<?php endsnippet() ?>
