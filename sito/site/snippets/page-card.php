<?php
/** Card di una pagina (card delle sezioni, contenuti in evidenza). $item; $level: livello del titolo */
$level ??= 'h3';
$text    = $item->description()->or($item->lead());
?>
<article class="card">
  <<?= $level ?> class="card__title"><a href="<?= $item->url() ?>"><?= $item->title()->esc() ?></a></<?= $level ?>>
  <?php if ($text->isNotEmpty()): ?><p><?= $text->esc() ?></p><?php endif ?>
</article>
