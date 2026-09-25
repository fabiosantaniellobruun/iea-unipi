<?php

use Kirby\Cms\Pages;
use Kirby\Toolkit\Str;

/** Tutti i post della bacheca, anche in archivio */
function allPosts(): Pages
{
    return page('bacheca')?->children()->template('post') ?? new Pages();
}

/**
 * Voci del calendario: gli eventi, nel giorno o nei giorni in cui si svolgono, e le scadenze dei bandi.
 * I post senza date restano solo in bacheca.
 * Ogni voce: post, type (event o deadline), first e last (primo e ultimo giorno), time (ora di inizio o null).
 */
function calendarEntries(Pages $posts): array
{
    $entries = [];

    foreach ($posts as $post) {
        if ($start = $post->startTime()) {
            $first     = strtotime('today', $start);
            $entries[] = [
                'post'  => $post,
                'type'  => 'event',
                'first' => $first,
                'last'  => $post->endTime() !== null ? max($first, strtotime('today', $post->endTime())) : $first,
                'time'  => date('H:i', $start) !== '00:00' ? date('H:i', $start) : null,
            ];
        }

        if ($post->isCall() === true) {
            $day       = strtotime('today', $post->deadline()->toDate());
            $entries[] = ['post' => $post, 'type' => 'deadline', 'first' => $day, 'last' => $day, 'time' => null];
        }
    }

    usort($entries, fn ($a, $b) => [$a['first'], $a['time'] ?? ''] <=> [$b['first'], $b['time'] ?? '']);
    return $entries;
}

/** Nome della voce: il titolo del post, preceduto da "Scadenza:" per i bandi */
function calendarLabel(array $entry): string
{
    return ($entry['type'] === 'deadline' ? t('calendar.deadline') . ': ' : '') . $entry['post']->title()->value();
}

/** Indirizzi per iscriversi a un calendario .ics: [https, webcal, Google Calendar] */
function calendarFeeds(string $url): array
{
    $webcal = preg_replace('#^https?://#', 'webcal://', $url);
    return [$url, $webcal, 'https://calendar.google.com/calendar/render?cid=' . rawurlencode($webcal)];
}

// ---------------------------------------------------------------- iCalendar (RFC 5545)

function icsText(string $text): string
{
    return str_replace(['\\', ';', ',', "\r\n", "\n"], ['\\\\', '\\;', '\\,', '\\n', '\\n'], $text);
}

/** Righe di al massimo 75 byte, senza spezzare i caratteri UTF-8 */
function icsFold(string $line): string
{
    $out   = '';
    $limit = 75;

    while (strlen($line) > $limit) {
        $cut = $limit;
        while ($cut > 0 && (ord($line[$cut]) & 0xC0) === 0x80) {
            $cut--;
        }
        $out  .= substr($line, 0, $cut) . "\r\n ";
        $line  = substr($line, $cut);
        $limit = 74;
    }

    return $out . $line . "\r\n";
}

function icsUtc(int $time): string
{
    return gmdate('Ymd\THis\Z', $time);
}

function icsEvent(array $entry, string $host): array
{
    $post  = $entry['post'];
    $lines = [
        'BEGIN:VEVENT',
        'UID:' . $post->uuid()->id() . ($entry['type'] === 'deadline' ? '-deadline' : '') . '@' . $host,
        'DTSTAMP:' . icsUtc($post->modified()),
    ];

    if ($entry['time'] !== null) {
        $lines[] = 'DTSTART:' . icsUtc($post->startTime());
        if (($end = $post->endTime()) !== null) {
            // fine senza ora: l'evento dura fino alla fine di quel giorno
            $lines[] = 'DTEND:' . icsUtc(date('H:i', $end) === '00:00' ? strtotime('+1 day', $end) : $end);
        }
    } else {
        $lines[] = 'DTSTART;VALUE=DATE:' . date('Ymd', $entry['first']);
        $lines[] = 'DTEND;VALUE=DATE:' . date('Ymd', strtotime('+1 day', $entry['last']));
    }

    $lines[] = 'SUMMARY:' . icsText(calendarLabel($entry));
    $lines[] = 'DESCRIPTION:' . icsText(trim($post->summary()->value() . "\n\n" . $post->url()));
    $lines[] = 'URL:' . $post->url();

    if ($entry['type'] === 'event' && $post->place()->isNotEmpty()) {
        $lines[] = 'LOCATION:' . icsText($post->place()->value());
    }

    if ($category = $post->categoryPage()) {
        $lines[] = 'CATEGORIES:' . icsText($category->title()->value());
    }

    $lines[] = 'END:VEVENT';
    return $lines;
}

/** Calendario completo in formato .ics, con le indicazioni per l'aggiornamento automatico */
function icsCalendar(string $name, array $entries): string
{
    $host  = parse_url(site()->url(), PHP_URL_HOST) ?: 'localhost';
    $lines = [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//Universita di Pisa//Ingegneria Edile-Architettura//' . Str::upper(kirby()->language()?->code() ?? 'it'),
        'CALSCALE:GREGORIAN',
        'METHOD:PUBLISH',
        'X-WR-CALNAME:' . icsText($name),
        'X-WR-TIMEZONE:Europe/Rome',
        'REFRESH-INTERVAL;VALUE=DURATION:PT6H',
        'X-PUBLISHED-TTL:PT6H',
    ];

    foreach ($entries as $entry) {
        array_push($lines, ...icsEvent($entry, $host));
    }

    $lines[] = 'END:VCALENDAR';
    return implode('', array_map('icsFold', $lines));
}

/** Risposta .ics: tipo text/calendar e nome del file */
function icsResponse(string $name, array $entries): string
{
    kirby()->response()->type('text/calendar')->header('Content-Disposition', 'inline; filename="' . Str::slug($name) . '.ics"');
    return icsCalendar($name, $entries);
}
