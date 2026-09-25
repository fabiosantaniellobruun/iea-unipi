<?php

use Kirby\Cms\Page;

/** Post della bacheca */
class PostPage extends Page
{
    /** Pagina della categoria scelta nel post (Avvisi, Eventi, Bandi e opportunità) */
    public function categoryPage(): ?Page
    {
        return $this->parent()?->children()->find($this->category()->value());
    }
}
