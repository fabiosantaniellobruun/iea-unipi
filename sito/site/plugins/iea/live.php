<?php

use Kirby\Cms\Response;

/**
 * Dati per la barra "ORA/LIVE" e per i toast delle notizie: un JSON letto dal browser a ogni visita.
 * Le pagine possono stare in cache, i dati no: gli eventi e le notizie si aggiornano dal pannello senza nuovi deploy,
 * e il browser decide da solo cosa mostrare in base al giorno di oggi.
 */

/** Notizie da mostrare nei toast: post recenti o con un evento o una scadenza vicini, al massimo 5 */
function toastPosts(int $today): array
{
    $items = [];

    foreach (currentPosts() as $post) {
        $last = $post->lastDay();
        $soon = $post->startTime() ?? ($post->deadline()->isNotEmpty() ? $post->deadline()->toDate() : null);
        $recent = $post->date()->toDate() >= strtotime('-14 days', $today);
        $upcoming = $soon !== null && $soon >= $today && $soon <= strtotime('+21 days', $today);

        if ($last !== null && $last < $today) {
            continue; // evento o scadenza passati
        }
        if ($recent === false && $upcoming === false) {
            continue;
        }

        $cover = $post->cover()->toFile();
        $items[] = [
            'id'    => $post->uuid()->id(),
            'title' => $post->title()->value(),
            'url'   => $post->url(),
            'kind'  => $post->categoryPage()?->title()->value() ?? '',
            'label' => $post->cardDate(),
            'until' => date('Y-m-d', $last ?? strtotime('+14 days', $post->date()->toDate())),
            'image' => $cover ? $cover->thumb(['width' => 224, 'height' => 224, 'crop' => true])->url() : null,
        ];
        if (count($items) === 5) {
            break;
        }
    }

    return $items;
}

/** Eventi con una data, da oggi in avanti: il browser mostra quelli del giorno */
function liveEvents(int $today): array
{
    $from   = strtotime('-1 day', $today);
    $events = [];

    foreach (allPosts() as $post) {
        $start = $post->startTime();
        if ($start === null) {
            continue;
        }
        $first = strtotime('today', $start);
        $last  = $post->endTime() !== null ? max($first, strtotime('today', $post->endTime())) : $first;
        if ($last < $from) {
            continue;
        }
        $events[] = [
            'id'    => $post->uuid()->id(),
            'title' => $post->title()->value(),
            'url'   => $post->url(),
            'first' => date('Y-m-d', $first),
            'last'  => date('Y-m-d', $last),
            'when'  => $post->when(),
        ];
    }

    usort($events, fn ($a, $b) => [$a['first'], $a['title']] <=> [$b['first'], $b['title']]);
    return $events;
}

function liveResponse(string $language): Response
{
    kirby()->setCurrentLanguage($language);
    kirby()->setCurrentTranslation($language);

    // solo in sviluppo: ?today=2026-09-21 simula un altro giorno, per provare la barra e i toast
    $debug = option('debug') === true;
    $today = strtotime('today');
    if ($debug && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)get('today')) && ($day = strtotime((string)get('today')))) {
        $today = $day;
    }

    return Response::json(
        [
            'today'  => date('Y-m-d', $today),
            'debug'  => $debug,
            'events' => liveEvents($today),
            'toasts' => toastPosts($today),
        ],
        200,
        false,
        ['Cache-Control' => 'public, max-age=300']
    );
}
