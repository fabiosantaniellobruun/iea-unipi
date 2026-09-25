<?php

/** Ricerca: la domanda arriva da ?q=, i risultati sono 10 per pagina */
return function () {
    $query   = trim((string)get('q'));
    $results = $query !== '' ? searchPages($query)->paginate(['limit' => 10, 'method' => 'query']) : null;
    return compact('query', 'results');
};
