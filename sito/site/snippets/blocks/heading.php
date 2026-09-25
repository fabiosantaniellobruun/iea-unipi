<?php
/** Titolo con un id ricavato dal testo, per i link a una parte della pagina (come /it/iscriversi/primo-anno#anno-accademico-2026-27) */
$level = in_array($block->level()->value(), ['h2', 'h3'], true) ? $block->level()->value() : 'h2';
?>
<<?= $level ?> id="<?= Str::slug(Str::unhtml($block->text())) ?>"><?= $block->text() ?></<?= $level ?>>
