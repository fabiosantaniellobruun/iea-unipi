<?php /** Risultati della ricerca, con l'estratto del testo e le parole trovate evidenziate */ ?>
<?php snippet('layout', slots: true) ?>
  <div class="container page-body">
    <div class="content">
      <?php snippet('search-form', ['query' => $query]) ?>
      <?php if ($results !== null): ?>
      <p class="search-count" role="status">
        <?= str_replace(['{count}', '{query}'], [$results->pagination()->total(), esc($query)], t(match ($results->pagination()->total()) { 0 => 'search.none', 1 => 'search.result', default => 'search.results' })) ?>
      </p>
      <?php if ($results->isNotEmpty()): ?>
      <ol class="search-results">
        <?php foreach ($results as $result): ?>
        <li>
          <h2><a href="<?= $result->url() ?>"><?= $result->isHomePage() ? t('home') : $result->title()->esc() ?></a></h2>
          <?php if ($path = searchPath($result)): ?><p class="search-results__path"><?= $path ?></p><?php endif ?>
          <p><?= searchExcerpt($result, $query) ?></p>
        </li>
        <?php endforeach ?>
      </ol>
      <?php snippet('pagination', ['pagination' => $results->pagination(), 'label' => t('search.pages')]) ?>
      <?php endif ?>
      <?php endif ?>
    </div>
  </div>
<?php endsnippet() ?>
