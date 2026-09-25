<?php
/** Numeri di pagina di un elenco. $pagination */
if ($pagination->pages() < 2) return;
?>
<nav class="pagination" aria-label="<?= t('noticeboard.pages') ?>">
  <ul>
    <?php if ($pagination->hasPrevPage()): ?>
    <li><a href="<?= $pagination->prevPageUrl() ?>" rel="prev"><?= t('pagination.prev') ?></a></li>
    <?php endif ?>
    <?php foreach ($pagination->range(7) as $number): ?>
    <li><a href="<?= $pagination->pageUrl($number) ?>"<?= $pagination->page() === $number ? ' aria-current="page"' : '' ?>><span class="visually-hidden"><?= t('pagination.page') ?> </span><?= $number ?></a></li>
    <?php endforeach ?>
    <?php if ($pagination->hasNextPage()): ?>
    <li><a href="<?= $pagination->nextPageUrl() ?>" rel="next"><?= t('pagination.next') ?></a></li>
    <?php endif ?>
  </ul>
</nav>
