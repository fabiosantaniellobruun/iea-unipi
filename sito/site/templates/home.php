<?php snippet('layout', slots: true) ?>
  <section class="container home-intro" aria-labelledby="home-title">
    <h1 id="home-title" class="display-m"><?= t('course') ?></h1>
    <p class="lead"><?= $page->lead()->esc() ?></p>
  </section>

  <?php if (($audiences = $page->audiences()->toStructure())->isNotEmpty()): ?>
  <section class="container home-section" aria-labelledby="audiences-title">
    <h2 id="audiences-title" class="visually-hidden"><?= t('home.audiences') ?></h2>
    <ul class="cards cards--audiences">
      <?php foreach ($audiences as $audience): ?>
      <li>
        <article class="card">
          <h3 class="card__title"><?= $audience->question()->esc() ?></h3>
          <ul class="card__links">
            <?php foreach ($audience->links()->toPages() as $link): ?>
            <li><a href="<?= $link->url() ?>"><?= $link->title()->esc() ?></a></li>
            <?php endforeach ?>
          </ul>
        </article>
      </li>
      <?php endforeach ?>
    </ul>
  </section>
  <?php endif ?>

  <?php if ($notices->isNotEmpty()): ?>
  <section class="container home-section" aria-labelledby="notices-title">
    <h2 id="notices-title"><?= t('home.notices') ?></h2>
    <?php snippet('post-list', ['posts' => $notices, 'level' => 'h3', 'category' => false]) ?>
  </section>
  <?php endif ?>

  <?php if ($featured->isNotEmpty()): ?>
  <section class="container home-section" aria-labelledby="featured-title">
    <h2 id="featured-title"><?= t('home.featured') ?></h2>
    <ul class="cards">
      <?php foreach ($featured as $item): ?>
      <li><?php $item->intendedTemplate()->name() === 'post' ? snippet('post-card', ['post' => $item]) : snippet('page-card', ['item' => $item]) ?></li>
      <?php endforeach ?>
    </ul>
  </section>
  <?php endif ?>

  <section class="band" aria-labelledby="facts-title">
    <div class="container">
      <h2 id="facts-title"><?= t('home.facts') ?></h2>
      <dl class="infocard">
        <?php foreach ($page->facts()->toStructure() as $fact): ?>
        <dt><?= $fact->label()->esc() ?></dt>
        <dd><?= $fact->value() ?></dd>
        <?php endforeach ?>
      </dl>
      <?php if ($course = page('il-corso/in-breve')): ?>
      <p><a class="more" href="<?= $course->url() ?>"><?= t('home.more') ?></a></p>
      <?php endif ?>
    </div>
  </section>

  <?php if ($latest->isNotEmpty()): ?>
  <section class="container home-section" aria-labelledby="latest-title">
    <h2 id="latest-title"><?= t('home.latest') ?></h2>
    <?php snippet('post-list', ['posts' => $latest, 'level' => 'h3']) ?>
    <p><a class="more" href="<?= page('bacheca')->url() ?>"><?= t('noticeboard.all') ?></a></p>
  </section>
  <?php endif ?>
<?php endsnippet() ?>
