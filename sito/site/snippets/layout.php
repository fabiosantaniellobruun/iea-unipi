<?php
/**
 * Struttura comune a tutte le pagine.
 * Uso nei template: snippet('layout', slots: true) ... endsnippet()
 * Slot: default (contenuto), sidebar (facoltativo).
 */
$languageCode = $kirby->language()?->code() ?? 'it';
?>
<!doctype html>
<html lang="<?= $languageCode ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $page->isHomePage() ? t('course.full') : $page->title()->esc() . ' · ' . t('course') ?> · <?= t('university') ?></title>
  <?php if ($page->description()->isNotEmpty()): ?>
  <meta name="description" content="<?= $page->description()->esc() ?>">
  <?php endif ?>
  <?php if (option('iea.noindex')): ?>
  <meta name="robots" content="noindex, nofollow">
  <?php endif ?>
  <script>document.documentElement.classList.add('js')</script>
  <?= css(['assets/css/tokens.css', 'assets/css/fonts.css', 'assets/css/base.css', 'assets/css/layout.css', 'assets/css/components.css']) ?>
  <?= js('assets/js/main.js', ['defer' => true]) ?>
  <?php foreach ($kirby->languages() as $language): ?>
  <link rel="alternate" hreflang="<?= $language->code() ?>" href="<?= $page->url($language->code()) ?>">
  <?php endforeach ?>
</head>
<body>
  <a class="skip-link" href="#contenuto"><?= t('skip') ?></a>

  <?php snippet('header') ?>

  <main id="contenuto" tabindex="-1">
    <?php if ($page->isHomePage() === false): ?>
    <div class="container page-header">
      <h1><?= $page->title()->esc() ?></h1>
      <?php snippet('breadcrumb') ?>
      <?php if ($page->lead()->isNotEmpty()): ?>
      <p class="lead"><?= $page->lead()->esc() ?></p>
      <?php endif ?>
    </div>
    <?php endif ?>

    <?php if (isset($slots) && $slots->sidebar()): ?>
    <div class="container page-body with-sidebar">
      <aside><?= $slots->sidebar() ?></aside>
      <div class="content"><?= $slot ?></div>
    </div>
    <?php else: ?>
    <?= $slot ?>
    <?php endif ?>
  </main>

  <?php snippet('footer') ?>
</body>
</html>
