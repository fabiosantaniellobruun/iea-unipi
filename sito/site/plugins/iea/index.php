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

Kirby::plugin('iea/site', []);
