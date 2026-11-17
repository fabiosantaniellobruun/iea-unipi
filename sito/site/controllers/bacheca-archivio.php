<?php

/** Archivio: i post che non sono più in bacheca, per anno */
return function () {
    $posts = page('bacheca')->children()->template('post')->filter(fn ($post) => $post->isCurrent() === false)->sortBy('date', 'desc');
    return ['years' => $posts->group(fn ($post) => $post->date()->toDate('Y'))];
};
