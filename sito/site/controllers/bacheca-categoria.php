<?php

/** Categoria della bacheca: i post non in archivio che hanno scelto questa categoria */
return fn ($page) => ['posts' => currentPosts()->filterBy('category', $page->uid())->paginate(['limit' => 12, 'method' => 'query'])];
