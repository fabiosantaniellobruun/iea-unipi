<?php snippet('layout', slots: true) ?>
  <section class="container home-intro" aria-labelledby="home-title">
    <h1 id="home-title" class="display-m"><?= t('course') ?></h1>
    <p class="lead"><?= $page->lead()->esc() ?></p>
  </section>

  <section class="band" aria-labelledby="facts-title">
    <div class="container">
      <h2 id="facts-title"><?= t('home.facts') ?></h2>
      <dl class="infocard">
        <?php foreach ($page->facts()->toStructure() as $fact): ?>
        <dt><?= $fact->label()->esc() ?></dt>
        <dd><?= $fact->value() ?></dd>
        <?php endforeach ?>
      </dl>
    </div>
  </section>
<?php endsnippet() ?>
