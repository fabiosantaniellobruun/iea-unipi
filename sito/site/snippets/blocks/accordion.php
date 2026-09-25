<div class="accordion">
  <?php foreach ($block->items()->toStructure() as $item): ?>
  <details>
    <summary><?= $item->title()->esc() ?></summary>
    <div class="accordion__body"><?= $item->text() ?></div>
  </details>
  <?php endforeach ?>
</div>
