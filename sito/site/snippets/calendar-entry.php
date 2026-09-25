<?php /** Voce del calendario: ora (se c'è) e titolo; per i bandi "Scadenza:". $entry */ ?>
<a class="calendar-entry calendar-entry--<?= $entry['type'] ?>" href="<?= $entry['post']->url() ?>"><?php if ($entry['time']): ?><span class="calendar-entry__time"><?= $entry['time'] ?></span> <?php endif ?><?= esc(calendarLabel($entry)) ?></a>
