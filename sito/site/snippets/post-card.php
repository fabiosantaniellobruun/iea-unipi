<?php
/**
 * Card di un post della bacheca. $post; $level: livello del titolo (h2 o h3); $category: mostra la categoria.
 * L'immagine di copertina, se c'è, è decorativa: il testo alternativo completo è nella pagina del post.
 */
$level    ??= 'h3';
$category ??= true;
?>
<article class="card">
  <?php if ($cover = $post->cover()->toFile()): ?>
    <?php if ($cover->height() > $cover->width()): ?>
    <?php // locandine e immagini verticali: intere, dentro il riquadro 16:9 ?>
    <?php $thumb = $cover->resize(null, 450) ?>
    <img class="card__image card__image--contain" src="<?= $thumb->url() ?>" width="<?= $thumb->width() ?>" height="<?= $thumb->height() ?>" alt="" loading="lazy">
    <?php else: ?>
    <?php $crop = fn (int $width) => ['width' => $width, 'height' => intdiv($width * 9, 16), 'crop' => true] ?>
    <img class="card__image" src="<?= $cover->thumb($crop(800))->url() ?>" srcset="<?= $cover->srcset(['400w' => $crop(400), '800w' => $crop(800)]) ?>" sizes="(min-width: 768px) 24rem, 100vw" width="800" height="450" alt="" loading="lazy">
    <?php endif ?>
  <?php endif ?>
  <p class="card__meta">
    <?php if ($category && $categoryPage = $post->categoryPage()): ?><span class="tag"><?= $categoryPage->title()->esc() ?></span><?php endif ?>
    <?= $post->cardDate() ?>
  </p>
  <<?= $level ?> class="card__title"><a href="<?= $post->url() ?>"><?= $post->title()->esc() ?></a></<?= $level ?>>
  <?php if ($post->summary()->isNotEmpty()): ?><p><?= $post->summary()->esc() ?></p><?php endif ?>
</article>
