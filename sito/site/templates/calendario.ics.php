<?php
/** Calendario completo della bacheca, per iscriversi da un'app di calendario */
echo icsResponse(t('calendar.name'), calendarEntries(allPosts()));
