<footer class="site-footer">
  <div class="container">
    <div class="site-footer__cols">
      <div>
        <h2><?= t('course.full') ?></h2>
        <p><?= t('university') ?><br><?= t('department') ?></p>
      </div>
      <div>
        <h2><?= t('footer.contacts') ?></h2>
        <?= $site->footerContacts()->kt() ?>
      </div>
      <div>
        <h2><?= t('footer.site') ?></h2>
        <ul>
          <?php foreach ($site->footerLinks()->toPages() as $link): ?>
          <li><a href="<?= $link->url() ?>"><?= $link->title()->esc() ?></a></li>
          <?php endforeach ?>
        </ul>
      </div>
      <div>
        <h2><?= t('footer.legal') ?></h2>
        <ul>
          <?php foreach ($site->legalLinks()->toStructure() as $link): ?>
          <li><a href="<?= $link->link()->toUrl() ?>" rel="external"><?= $link->text()->esc() ?></a></li>
          <?php endforeach ?>
        </ul>
      </div>
    </div>
    <div class="site-footer__bottom">
      <ul class="social">
        <?php foreach ($site->social()->toStructure() as $item): ?>
        <li><a href="<?= $item->link()->toUrl() ?>" rel="external"><?= $item->text()->esc() ?></a></li>
        <?php endforeach ?>
      </ul>
      <p>© <?= date('Y') ?> <?= t('university') ?></p>
    </div>
  </div>
</footer>
