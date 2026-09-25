<?php
/** Un solo post, da aggiungere al proprio calendario */
echo icsResponse($page->title()->value(), calendarEntries(new Kirby\Cms\Pages([$page])));
