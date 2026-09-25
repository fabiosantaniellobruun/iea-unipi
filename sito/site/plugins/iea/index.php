<?php

/**
 * Funzioni di supporto del sito.
 */

require_once __DIR__ . '/calendar.php';
require_once __DIR__ . '/search.php';

/** Vero se l'indirizzo porta fuori dal sito */
function isExternal(?string $url): bool
{
    if ($url === null || $url === '') {
        return false;
    }
    $host = parse_url($url, PHP_URL_HOST);
    return $host !== null && $host !== parse_url(site()->url(), PHP_URL_HOST);
}

/** Data nella lingua della pagina, con uno schema ICU: 'd MMMM y' -> "22 settembre 2026" / "22 September 2026" */
function formatDate(int $time, string $pattern = 'd MMMM y'): string
{
    $locale = kirby()->language()?->code() === 'en' ? 'en_GB' : 'it_IT';
    return (new IntlDateFormatter($locale, IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, $pattern))->format($time);
}

/** Post della bacheca non in archivio, dal più recente */
function currentPosts(): Kirby\Cms\Pages
{
    return page('bacheca')?->children()->template('post')->filter(fn ($post) => $post->isCurrent())->sortBy('date', 'desc') ?? new Kirby\Cms\Pages();
}

/** Indirizzo di un file della cartella dei marchi (il nome della cartella contiene uno spazio) */
function brandUrl(string $file): string
{
    return url('assets/' . rawurlencode('IEA Brand') . '/' . rawurlencode($file));
}

/**
 * Evidenzia le segnalazioni redazionali [DA VERIFICARE: ...] e [DA FORNIRE: ...] nel corpo della pagina,
 * solo nel testo e non dentro i tag (attributi alt, title...)
 */
function highlightNotes(string $html): string
{
    $body = strpos($html, '<body');
    if ($body === false) {
        return $html;
    }

    $head   = substr($html, 0, $body);
    $rest   = substr($html, $body);
    $out    = '';
    $offset = 0;

    preg_match_all('/\[DA (VERIFICARE|FORNIRE)\b[^\]]*\]/u', $rest, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    foreach ($matches as [$full, $type]) {
        [$text, $position] = $full;
        $before    = substr($rest, 0, $position);
        $insideTag = strrpos($before, '<') > strrpos($before, '>');
        $out .= substr($rest, $offset, $position - $offset);
        $out .= $insideTag ? $text : '<mark class="segnalazione segnalazione--' . strtolower($type[0]) . '">' . $text . '</mark>';
        $offset = $position + strlen($text);
    }

    return $head . $out . substr($rest, $offset);
}

Kirby::plugin('iea/site', [
    'routes' => [
        // Manutenzione: con 'iea.maintenance' => true i visitatori vedono una pagina di cortesia (503);
        // chi ha fatto l'accesso al pannello continua a vedere il sito. Pannello, API e file restano raggiungibili.
        [
            'pattern' => '(:all)',
            'method'  => 'ALL',
            'action'  => function () {
                if (option('iea.maintenance') !== true || kirby()->user() !== null) {
                    return $this->next();
                }

                return new Kirby\Cms\Response(snippet('maintenance', [], true), 'text/html', 503, ['Retry-After' => '3600']);
            },
        ],
    ],
    'hooks' => [
        'page.render:after' => function (string $contentType, array $data, string $html) {
            return $contentType === 'html' ? highlightNotes($html) : $html;
        },
    ],
]);
