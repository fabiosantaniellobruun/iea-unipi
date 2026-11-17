<dl class="infocard">
  <?php foreach ($block->rows()->toStructure() as $row): ?>
  <dt><?= $row->label()->esc() ?></dt>
  <dd><?= $row->value()->inline() ?></dd>
  <?php endforeach ?>
</dl>
