<?php /** Pagina di cortesia durante la manutenzione, senza menu né contenuti (vedi 'iea.maintenance') */ ?>
<!doctype html>
<html lang="<?= kirby()->language()?->code() ?? 'it' ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title><?= t('maintenance.title') ?> · <?= t('course') ?></title>
  <link rel="icon" href="<?= url('assets/favicon.svg') ?>" type="image/svg+xml">
  <?= css(['assets/css/tokens.css', 'assets/css/fonts.css', 'assets/css/base.css', 'assets/css/layout.css']) ?>
</head>
<body>
  <main class="container maintenance">
    <p class="brand">
      <img src="<?= brandUrl('marchio_unipi_orizz_pant541.svg') ?>" width="297" height="100" alt="<?= t('university') ?>">
      <span class="brand__rule" aria-hidden="true"></span>
      <img src="<?= brandUrl('iea-logo-horizontal.svg') ?>" width="683" height="109" alt="<?= t('course') ?>">
    </p>
    <h1><?= t('maintenance.title') ?></h1>
    <p class="lead"><?= t('maintenance.text') ?></p>
  </main>
</body>
</html>
