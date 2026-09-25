<nav class="breadcrumb" aria-label="<?= t('breadcrumb') ?>">
  <ol>
    <?php foreach ($site->breadcrumb() as $crumb): ?>
    <li>
      <?php if ($crumb->is($page)): ?>
      <span aria-current="page"><?= $crumb->title()->esc() ?></span>
      <?php else: ?>
      <a href="<?= $crumb->url() ?>"><?= $crumb->isHomePage() ? t('home') : $crumb->title()->esc() ?></a>
      <?php endif ?>
    </li>
    <?php endforeach ?>
  </ol>
</nav>
