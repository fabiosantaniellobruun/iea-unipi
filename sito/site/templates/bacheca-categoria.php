<?php snippet('layout', slots: true) ?>
  <?php slot('sidebar') ?><?php snippet('section-nav') ?><?php endslot() ?>
  <?php slot() ?><?php snippet('post-list', ['posts' => $posts, 'category' => false]) ?><?php endslot() ?>
<?php endsnippet() ?>
