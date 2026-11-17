<?php
/** Calendario di una categoria della bacheca (per esempio solo gli eventi) */
echo icsResponse(t('calendar.name') . ' – ' . $page->title()->value(), calendarEntries(allPosts()->filterBy('category', $page->uid())));
