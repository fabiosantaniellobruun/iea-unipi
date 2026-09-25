<?php

use Kirby\Cms\Page;
use Kirby\Cms\Pages;
use Kirby\Toolkit\Str;

/**
 * Ricerca interna, sui testi della lingua corrente. Non usa $site->search() perché cercherebbe anche dentro
 * la struttura dei blocchi (chiavi come "type" o "level"): qui si leggono solo i testi.
 */

const SEARCH_SKIP_TEMPLATES = ['error', 'styleguide', 'cerca', 'calendario', 'mappa', 'guida'];

/** Minuscolo e senza accenti, per trovare "università" anche scrivendo "universita" */
function searchNormalize(string $text): string
{
    return Str::lower(Str::ascii($text));
}

/** Come searchNormalize(), ma un carattere per uno: le posizioni restano quelle del testo originale */
function searchNormalizeKeepLength(string $text): string
{
    $out = '';
    foreach (mb_str_split($text) as $char) {
        $ascii = Str::ascii($char);
        $out  .= Str::lower(Str::length($ascii) === 1 ? $ascii : $char);
    }
    return $out;
}

/**
 * Parole della ricerca, di almeno due lettere. Dalle parole di almeno cinque lettere si toglie l'ultima vocale
 * o la "s" finale, così "tirocinio" trova anche "tirocini" e "internships" anche "internship".
 */
function searchTerms(string $query): array
{
    $words = preg_split('/[\s,;.:!?"“”«»()\[\]]+/u', searchNormalize(Str::substr($query, 0, 100)), -1, PREG_SPLIT_NO_EMPTY);
    $terms = [];
    foreach ($words as $word) {
        if (Str::length($word) >= 2) {
            $terms[$word] = ['full' => $word, 'stem' => Str::length($word) >= 5 ? preg_replace('/[aeios]$/', '', $word) : $word];
        }
    }
    return array_values($terms);
}

/** Radici delle parole della ricerca: bastano a far trovare una pagina */
function searchWords(string $query): array
{
    return array_values(array_unique(array_column(searchTerms($query), 'stem')));
}

/** Testo leggibile di un valore: HTML, blocchi (JSON) o strutture, senza gli attributi tecnici */
function searchStrings(mixed $value): string
{
    if (is_array($value)) {
        if (($value['isHidden'] ?? false) === true) {
            return '';
        }
        $parts = [];
        foreach ($value as $key => $item) {
            if (in_array($key, ['id', 'type', 'level', 'isHidden', 'location', 'files', 'image', 'links'], true) === false) {
                $parts[] = searchStrings($item);
            }
        }
        return implode(' ', $parts);
    }

    if (is_string($value) === false) {
        return '';
    }

    if (str_starts_with(ltrim($value), '[{') && is_array($decoded = json_decode($value, true))) {
        return searchStrings($decoded);
    }

    return html_entity_decode(strip_tags(str_replace('<', ' <', $value)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/** Testi di una pagina: titolo, introduzione (frase introduttiva, descrizione, sommario) e corpo */
function searchTexts(Page $page): array
{
    static $cache = [];

    return $cache[$page->id() . ":" . kirby()->language()?->code()] ??= (function () use ($page) {
        $content = $page->content(kirby()->language()?->code())->toArray();
        $intro   = [];
        $body    = [];

        foreach ($content as $key => $value) {
            match (true) {
                $key === 'title'                                                                                     => null,
                in_array($key, ['lead', 'description', 'summary', 'place'], true)                                   => $intro[] = searchStrings($value),
                in_array($key, ['uuid', 'slug', 'category', 'date', 'start', 'end', 'deadline', 'cover', 'featured', 'audiences'], true) => null,
                $key === 'facts'                                                                                     => $body[] = searchStrings(Kirby\Data\Data::decode($value, 'yaml')),
                default                                                                                              => $body[] = searchStrings($value),
            };
        }

        $clean = fn (array $parts) => trim(preg_replace('/\s+/u', ' ', implode(' ', $parts)));
        return ['title' => $page->title()->value(), 'intro' => $clean($intro), 'body' => $clean($body)];
    })();
}

/**
 * Pagine che contengono tutte le parole, dalla più pertinente: il titolo vale più dell'introduzione, che vale più
 * del testo, e una parola trovata intera vale più di una trovata solo nella radice
 */
function searchPages(string $query): Pages
{
    $terms = searchTerms($query);
    if ($terms === []) {
        return new Pages();
    }

    $scores = [];
    foreach (site()->index() as $page) {
        if (in_array($page->intendedTemplate()->name(), SEARCH_SKIP_TEMPLATES, true)) {
            continue;
        }

        $texts = array_map('searchNormalize', searchTexts($page));
        $all   = implode(' ', $texts);
        $score = 0;

        foreach ($terms as ['full' => $full, 'stem' => $stem]) {
            if (str_contains($all, $stem) === false) {
                continue 2;
            }
            foreach (['title' => 10, 'intro' => 3, 'body' => 1] as $part => $weight) {
                $score += $weight * (substr_count($texts[$part], $stem) + 2 * substr_count($texts[$part], $full));
            }
        }

        $scores[$page->id()] = $score;
    }

    return site()->index()->filter(fn ($page) => isset($scores[$page->id()]))->sortBy(fn ($page) => $scores[$page->id()], 'desc');
}

/** Estratto intorno alla prima parola trovata, con le parole evidenziate */
function searchExcerpt(Page $page, string $query, int $length = 220): string
{
    $texts = searchTexts($page);
    $text  = trim($texts['intro'] . ' ' . $texts['body']);
    $words = searchWords($query);

    $norm = searchNormalizeKeepLength($text);

    $positions = array_filter(array_map(fn ($word) => mb_strpos($norm, $word), $words), fn ($position) => $position !== false);
    $start     = $positions === [] ? 0 : max(0, min($positions) - 60);
    if ($start > 0 && ($space = mb_strpos($text, ' ', $start)) !== false && $space < $start + 20) {
        $start = $space + 1;
    }

    $snippet     = mb_substr($text, $start, $length);
    $normSnippet = mb_substr($norm, $start, $length);

    // intervalli da evidenziare, fino alla fine della parola, uniti se si sovrappongono
    $ranges = [];
    foreach ($words as $word) {
        for ($offset = 0; ($found = mb_strpos($normSnippet, $word, $offset)) !== false; $offset = $found + 1) {
            $end = $found + mb_strlen($word);
            while ($end < mb_strlen($normSnippet) && preg_match('/[\p{L}\p{N}]/u', mb_substr($normSnippet, $end, 1))) {
                $end++;
            }
            $ranges[] = [$found, $end];
        }
    }
    sort($ranges);

    $html   = '';
    $cursor = 0;
    foreach ($ranges as [$from, $to]) {
        if ($to <= $cursor) {
            continue;
        }
        $from    = max($from, $cursor);
        $html   .= esc(mb_substr($snippet, $cursor, $from - $cursor)) . '<mark class="search-hit">' . esc(mb_substr($snippet, $from, $to - $from)) . '</mark>';
        $cursor  = $to;
    }
    $html .= esc(mb_substr($snippet, $cursor));

    return ($start > 0 ? '… ' : '') . $html . ($start + $length < mb_strlen($text) ? ' …' : '');
}

/** Dove si trova la pagina: sezione, sottosezione e, per i post, la categoria */
function searchPath(Page $page): string
{
    $parts = $page->parents()->flip()->pluck('title');
    if ($page->intendedTemplate()->name() === 'post' && $category = $page->categoryPage()) {
        $parts[] = $category->title();
    }
    return implode(' › ', array_map(fn ($title) => esc((string)$title), $parts));
}
