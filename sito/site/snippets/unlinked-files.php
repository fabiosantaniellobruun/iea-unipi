<?php
/**
 * Allegati caricati nella pagina ma non citati nel testo: la redazione può aggiungerli dalla colonna "Allegati"
 * del pannello senza inserire un blocco. $page
 */
$text  = (string)$page->text()->toBlocks();
$files = $page->files()->template('allegato')->filter(fn ($file) => str_contains($text, '/' . $file->filename()) === false);
if ($files->isEmpty()) return;
?>
<h2><?= t('attachments') ?></h2>
<?php snippet('file-list', ['files' => $files]) ?>
