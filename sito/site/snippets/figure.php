<?php
/**
 * Immagine in versioni ridotte e in WebP (vedi thumbs in config.php). $image; $caption (facoltativa).
 * Non supera la sua misura reale; le immagini verticali, come le locandine, restano più strette della colonna di testo.
 */
$portrait = $image->height() > $image->width();
$fallback = $image->thumb(['width' => 1200]);
?>
<figure class="figure<?= $portrait ? ' figure--portrait' : '' ?>" style="--natural: <?= $image->width() ?>px">
  <img src="<?= $fallback->url() ?>" srcset="<?= $image->srcset() ?>" sizes="<?= $portrait ? '(min-width: 30em) 22rem, 100vw' : '(min-width: 1025px) 47.5rem, 100vw' ?>" width="<?= $fallback->width() ?>" height="<?= $fallback->height() ?>" alt="<?= $image->alt()->esc() ?>" loading="lazy">
  <?php if (isset($caption) && $caption->isNotEmpty()): ?>
  <figcaption><?= $caption ?></figcaption>
  <?php endif ?>
</figure>
