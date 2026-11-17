<?php snippet('layout', slots: true) ?>
  <?php slot('sidebar') ?><?php snippet('section-nav') ?><?php endslot() ?>
  <?php slot() ?>
    <?= $page->text()->toBlocks() ?>
    <?php foreach ($years as $year => $posts): ?>
    <h2><?= $year ?></h2>
    <?php snippet('post-list', ['posts' => $posts, 'level' => 'h3']) ?>
    <?php endforeach ?>
    <?php if ($years->isEmpty()): ?><p><?= t('noticeboard.empty') ?></p><?php endif ?>
  <?php endslot() ?>
<?php endsnippet() ?>
