<?php
$files = $block->files()->toFiles();
if ($files->isNotEmpty()) snippet('file-list', ['files' => $files]);
