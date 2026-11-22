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
  <?php if (($description = $page->description()->or($page->summary())->or($page->lead()))->isNotEmpty()): ?>
  <meta name="description" content="<?= $description->esc() ?>">
  <?php endif ?>
  <link rel="icon" href="<?= url('favicon.ico') ?>" sizes="32x32">
  <link rel="icon" href="<?= url('assets/favicon.svg') ?>" type="image/svg+xml">
  <link rel="apple-touch-icon" href="<?= url('assets/apple-touch-icon.png') ?>">
  <?php if (option('iea.noindex') || in_array($page->intendedTemplate()->name(), ['guida', 'brand'], true)): ?>
  <meta name="robots" content="noindex, nofollow">
  <?php endif ?>
  <script>document.documentElement.classList.add('js')</script>
  <?= css(['assets/css/tokens.css', 'assets/css/fonts.css', 'assets/css/base.css', 'assets/css/layout.css', 'assets/css/components.css', 'assets/css/live.css']) ?>
  <?= js(['assets/js/main.js', 'assets/js/live.js'], ['defer' => true]) ?>
  <?php foreach ($kirby->languages() as $language): ?>
  <link rel="alternate" hreflang="<?= $language->code() ?>" href="<?= $page->url($language->code()) ?>">
  <?php endforeach ?>
</head>
<body>
  <a class="skip-link" href="#contenuto"><?= t('skip') ?></a>

  <?php snippet('header') ?>

  <main id="contenuto" tabindex="-1">
    <?php // la home e la pagina non trovata hanno una testata propria ?>
    <?php if ($page->isHomePage() === false && $page->intendedTemplate()->name() !== 'error'): ?>
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

  <?php if (in_array($page->intendedTemplate()->name(), ['guida', 'brand', 'styleguide'], true) === false): ?>
  <?php // toast delle ultime dalla bacheca, in basso a sinistra: si riempiono da soli con live.json ?>
  <aside class="toasts" id="toasts" data-url="<?= $kirby->language()->url() ?>/live.json" aria-label="<?= t('toast.aria') ?>"
         data-label="<?= t('toast.label') ?>" data-read="<?= t('toast.read') ?>" data-close="<?= t('toast.close') ?>"
         data-prev="<?= t('toast.prev') ?>" data-next="<?= t('toast.next') ?>" hidden></aside>
  <?php endif ?>
</body>
</html>
