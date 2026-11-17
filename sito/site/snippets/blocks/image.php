<?php if ($image = $block->image()->toFile()) snippet('figure', ['image' => $image, 'caption' => $block->caption()]);
