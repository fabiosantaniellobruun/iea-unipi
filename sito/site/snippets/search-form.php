<?php /** Campo di ricerca. $query: la domanda già scritta (facoltativa) */ ?>
<?php if ($search = page('cerca')): ?>
<form class="search-form" role="search" action="<?= $search->url() ?>">
  <label class="visually-hidden" for="search-q"><?= t('search.label') ?></label>
  <input id="search-q" type="search" name="q" value="<?= esc($query ?? '', 'attr') ?>" placeholder="<?= t('search.label') ?>…">
  <button type="submit"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/></svg><span class="visually-hidden"><?= t('search.button') ?></span></button>
</form>
<?php endif ?>
