<?php
/** Tabella: la prima riga è l'intestazione; le colonne vuote in tutte le righe non si mostrano */
$rows = $block->rows()->toStructure();
if ($rows->count() < 2) return;
$columns = array_filter(['c1', 'c2', 'c3', 'c4'], fn ($key) => $rows->filter(fn ($row) => $row->$key()->isNotEmpty())->isNotEmpty());
$head    = $rows->first();
?>
<table>
  <?php if ($block->caption()->isNotEmpty()): ?>
  <caption><?= $block->caption()->esc() ?></caption>
  <?php endif ?>
  <thead>
    <tr>
      <?php foreach ($columns as $key): ?><th scope="col"><?= $head->$key() ?></th><?php endforeach ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows->offset(1) as $row): ?>
    <tr>
      <?php foreach ($columns as $key): ?><td><?= $row->$key() ?></td><?php endforeach ?>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
