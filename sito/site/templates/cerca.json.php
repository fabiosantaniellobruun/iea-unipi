<?php
/**
 * Risultati della ricerca in JSON, per il campo nell'header: i primi 6, con il numero totale.
 * $query e $results vengono dal controller della pagina di ricerca (cerca.php).
 */
$total = $results?->pagination()->total() ?? 0;

echo json_encode([
    'count'   => $query === '' ? '' : str_replace(['{count}', '{query}'], [$total, $query], t(match ($total) { 0 => 'search.none', 1 => 'search.result', default => 'search.results' })),
    'total'   => $total,
    'all'     => $page->url() . '?q=' . rawurlencode($query),
    'results' => $results === null ? [] : array_values($results->limit(6)->values(fn ($result) => [
        'title'   => $result->isHomePage() ? t('home') : $result->title()->value(),
        'url'     => $result->url(),
        'path'    => html_entity_decode(searchPath($result), ENT_QUOTES, 'UTF-8'),
        'excerpt' => searchExcerpt($result, $query, 140),
    ])),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
