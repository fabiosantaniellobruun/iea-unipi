<?php

/** Bacheca: tutti i post non in archivio */
return fn () => ['posts' => currentPosts()->paginate(['limit' => 12, 'method' => 'query'])];
