<?php /** Indice di sezione: introduzione facoltativa e card delle pagine della sezione */ ?>
<?php snippet('layout', slots: true) ?>
  <?php slot('sidebar') ?><?php snippet('section-nav') ?><?php endslot() ?>
  <?php slot() ?>
    <?= $page->text()->toBlocks() ?>
    <?php if (($children = $page->children()->listed())->isNotEmpty()): ?>
    <h2><?= t('menu.section') ?></h2>
    <ul class="cards">
      <?php foreach ($children as $child): ?>
      <li><?php snippet('page-card', ['item' => $child]) ?></li>
      <?php endforeach ?>
    </ul>
    <?php endif ?>
    <?php snippet('unlinked-files', ['page' => $page]) ?>
  <?php endslot() ?>
<?php endsnippet() ?>
