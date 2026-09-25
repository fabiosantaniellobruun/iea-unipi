<?php

/**
 * Configurazione comune a tutti gli ambienti.
 * I file config.<dominio>.php sovrascrivono queste opzioni per ciascun ambiente.
 */
// Date e orari dei contenuti sono ora italiana: "oggi" per la bacheca e gli orari nei calendari esterni
date_default_timezone_set('Europe/Rome');

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
    // "Password dimenticata?" nel login del pannello: arriva per email un codice per entrare e scegliere una nuova password.
    // Il server deve poter spedire email (funzione mail di PHP o SMTP): da confermare con l'IT
    'auth' => [
        'methods' => ['password', 'password-reset'],
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
