<?php
/** Elenco di post in card, con la paginazione se $posts è paginato. $posts; $level; $category */
if ($posts->isEmpty()): ?>
<p><?= t('noticeboard.empty') ?></p>
<?php return; endif ?>
<ul class="cards">
  <?php foreach ($posts as $post): ?>
  <li><?php snippet('post-card', ['post' => $post, 'level' => $level ?? 'h2', 'category' => $category ?? true]) ?></li>
  <?php endforeach ?>
</ul>
<?php if ($pagination = $posts->pagination()) snippet('pagination', ['pagination' => $pagination]) ?>
