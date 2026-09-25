<?php

/** Produzione */
return [
    'debug' => false,
    'cache' => [
        'pages' => [
            'active' => true,
            // home e bacheca cambiano con la data (post in archivio, avvisi scaduti): non vanno in cache
            'ignore' => fn ($page) => in_array($page->intendedTemplate()->name(), ['home', 'bacheca', 'bacheca-categoria', 'bacheca-archivio'], true),
        ],
    ],
    'iea.noindex' => false,
];
