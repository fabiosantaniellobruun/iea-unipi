<?php /** Elenco di allegati statico, usato dalla guida di stile. $items: [[testo, formato, peso]] */ ?>
<ul class="attachments">
  <?php foreach ($items as [$text, $format, $size]): ?>
  <li><a href="#"><?= esc($text) ?></a> <span class="meta"><?= $format ?>, <?= $size ?></span></li>
  <?php endforeach ?>
</ul>
