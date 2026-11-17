<?php /** Elenco di allegati con formato e peso. $files */ ?>
<ul class="attachments">
  <?php foreach ($files as $file): ?>
  <li>
    <a href="<?= $file->url() ?>"><?= $file->title()->or($file->name())->esc() ?></a>
    <span class="meta"><?= strtoupper($file->extension()) ?>, <?= $file->niceSize() ?></span>
  </li>
  <?php endforeach ?>
</ul>
