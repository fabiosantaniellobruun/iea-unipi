<?php

/**
 * Configurazione comune a tutti gli ambienti.
 * I file config.<dominio>.php sovrascrivono queste opzioni per ciascun ambiente.
 */
return [
    'debug'     => false,
    'languages' => true,
    // gli allegati hanno un indirizzo stabile accanto alla pagina: /it/pagina/file.pdf
    'content'   => [
        'fileRedirects' => true,
    ],
    'panel' => [
        'language' => 'it',
    ],
    'thumbs' => [
        'format'  => 'webp',
        'quality' => 80,
        'srcsets' => [
            'default' => [400, 800, 1200, 1600],
        ],
    ],
    // Il sito si indicizza solo in produzione: vedi config.iea.ing.unipi.it.php
    'iea.noindex' => true,
    // Pagina di manutenzione per i visitatori: si attiva nel file dell'ambiente durante un aggiornamento
    'iea.maintenance' => false,
];
