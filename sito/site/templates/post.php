<?php /** Post della bacheca: dati principali, immagine, testo e allegati */ ?>
<?php snippet('layout', slots: true) ?>
  <?php slot('sidebar') ?><?php snippet('section-nav') ?><?php endslot() ?>
  <?php slot() ?>
    <dl class="infocard post-facts">
      <?php if ($category = $page->categoryPage()): ?>
      <dt><?= t('post.category') ?></dt>
      <dd><a href="<?= $category->url() ?>"><?= $category->title()->esc() ?></a></dd>
      <?php endif ?>
      <?php if ($when = $page->when(true)): ?>
      <dt><?= t('post.when') ?></dt>
      <dd><?= $when ?></dd>
      <?php endif ?>
      <?php if ($page->place()->isNotEmpty()): ?>
      <dt><?= t('post.where') ?></dt>
      <dd><?= $page->place()->esc() ?></dd>
      <?php endif ?>
      <?php if ($page->isCall()): ?>
      <dt><?= t('post.deadline') ?></dt>
      <dd><?= formatDate($page->deadline()->toDate()) ?></dd>
      <?php endif ?>
      <dt><?= t('post.published') ?></dt>
      <dd><time datetime="<?= $page->date()->toDate('Y-m-d') ?>"><?= formatDate($page->date()->toDate()) ?></time></dd>
    </dl>
    <?php if ($cover = $page->cover()->toFile()): ?>
      <?php snippet('figure', ['image' => $cover]) ?>
    <?php endif ?>
    <?= $page->text()->toBlocks() ?>
    <?php snippet('unlinked-files', ['page' => $page]) ?>
  <?php endslot() ?>
<?php endsnippet() ?>
