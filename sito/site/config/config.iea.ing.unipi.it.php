<?php

/** Produzione */
return [
    'debug' => false,
    'cache' => [
        'pages' => [
            'active' => true,
            // home, bacheca e calendario cambiano con la data (post in archivio, avvisi scaduti), la ricerca con la domanda: non vanno in cache
            'ignore' => fn ($page) => in_array($page->intendedTemplate()->name(), ['home', 'bacheca', 'bacheca-categoria', 'bacheca-archivio', 'calendario', 'cerca'], true),
        ],
    ],
    'iea.noindex' => false,
];
