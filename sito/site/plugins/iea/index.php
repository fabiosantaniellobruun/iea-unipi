<?php

/**
 * Funzioni di supporto del sito.
 */

/** Vero se l'indirizzo porta fuori dal sito */
function isExternal(?string $url): bool
{
    if ($url === null || $url === '') {
        return false;
    }
    $host = parse_url($url, PHP_URL_HOST);
    return $host !== null && $host !== parse_url(site()->url(), PHP_URL_HOST);
}

/** Indirizzo di un file della cartella dei marchi (il nome della cartella contiene uno spazio) */
function brandUrl(string $file): string
{
    return url('assets/' . rawurlencode('IEA Brand') . '/' . rawurlencode($file));
}

Kirby::plugin('iea/site', []);
