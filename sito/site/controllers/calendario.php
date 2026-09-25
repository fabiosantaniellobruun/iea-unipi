<?php

use Kirby\Toolkit\Str;

/** Calendario: griglia del mese scelto (?month=AAAA-MM, dal lunedì), prossimi appuntamenti e indirizzi per iscriversi */
return function ($page) {
    $month   = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string)get('month')) ? get('month') : date('Y-m');
    $first   = strtotime($month . '-01');
    $last    = strtotime(date('Y-m-t', $first));
    $today   = strtotime('today');
    $entries = calendarEntries(allPosts());

    // voci per giorno: gli eventi di più giorni compaiono in ognuno
    $byDay = [];
    foreach ($entries as $entry) {
        for ($day = $entry['first']; $day <= $entry['last']; $day = strtotime('+1 day', $day)) {
            $byDay[date('Y-m-d', $day)][] = $entry;
        }
    }

    $days = [];
    $from = strtotime('-' . (date('N', $first) - 1) . ' days', $first);
    $to   = strtotime('+' . (7 - date('N', $last)) . ' days', $last);
    for ($day = $from; $day <= $to; $day = strtotime('+1 day', $day)) {
        $inMonth = date('Y-m', $day) === $month;
        $days[]  = [
            'time'    => $day,
            'inMonth' => $inMonth,
            'isToday' => $day === $today,
            'entries' => $inMonth ? ($byDay[date('Y-m-d', $day)] ?? []) : [],
        ];
    }

    $prev = strtotime('-1 month', $first);
    $next = strtotime('+1 month', $first);

    $feeds = [['label' => t('calendar.all'), 'links' => calendarFeeds($page->url() . '.ics')]];
    foreach ($page->parent()->children()->template('bacheca-categoria') as $category) {
        $feeds[] = ['label' => $category->title()->value(), 'links' => calendarFeeds($category->url() . '.ics')];
    }

    return [
        'title'    => Str::ucfirst(formatDate($first, 'LLLL y')),
        'weeks'    => array_chunk($days, 7),
        'agenda'   => array_filter($days, fn ($day) => $day['entries'] !== []),
        'isCurrent' => $month === date('Y-m'),
        'prev'     => ['url' => $page->url() . '?month=' . date('Y-m', $prev), 'label' => Str::ucfirst(formatDate($prev, 'LLLL y'))],
        'next'     => ['url' => $page->url() . '?month=' . date('Y-m', $next), 'label' => Str::ucfirst(formatDate($next, 'LLLL y'))],
        'upcoming' => array_slice(array_values(array_filter($entries, fn ($entry) => $entry['last'] >= $today)), 0, 8),
        'feeds'    => $feeds,
    ];
};
