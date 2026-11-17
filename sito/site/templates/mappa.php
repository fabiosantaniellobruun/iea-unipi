<?php /** Mappa del sito: pagine del menu con le loro sottopagine, poi le pagine del footer */ ?>
<?php snippet('layout', slots: true) ?>
  <div class="container page-body">
    <div class="content">
      <ul class="sitemap">
        <li><a href="<?= $site->url() ?>"><?= t('home') ?></a></li>
        <?php foreach ($site->children()->listed() as $section): ?>
        <li>
          <a href="<?= $section->url() ?>"><?= $section->title()->esc() ?></a>
          <?php if (($children = $section->children()->listed())->isNotEmpty()): ?>
          <ul>
            <?php foreach ($children as $child): ?>
            <li><a href="<?= $child->url() ?>"><?= $child->title()->esc() ?></a></li>
            <?php endforeach ?>
          </ul>
          <?php endif ?>
        </li>
        <?php endforeach ?>
      </ul>
      <h2><?= t('sitemap.footer') ?></h2>
      <ul class="sitemap">
        <?php foreach ($site->footerLinks()->toPages()->not($page) as $link): ?>
        <li><a href="<?= $link->url() ?>"><?= $link->title()->esc() ?></a></li>
        <?php endforeach ?>
      </ul>
    </div>
  </div>
<?php endsnippet() ?>
