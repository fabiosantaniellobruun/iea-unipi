<?php

/** Home: ogni post compare in un solo blocco, nell'ordine avvisi, in evidenza, ultime dalla bacheca */
return function ($page) {
    $notices  = currentPosts()->filterBy('category', 'avvisi')->filter(fn ($post) => $post->isFresh())->limit(3);
    $featured = $page->featured()->toPages()->not($notices)->limit(3);
    $latest   = currentPosts()->not($notices)->not($featured)->limit(4);

    return compact('notices', 'featured', 'latest');
};
