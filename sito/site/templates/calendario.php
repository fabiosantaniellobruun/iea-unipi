<?php /** Calendario della bacheca: mese navigabile (griglia su schermi larghi, elenco dei giorni su mobile), prossimi appuntamenti, iscrizione */ ?>
<?php snippet('layout', slots: true) ?>
  <?php slot('sidebar') ?><?php snippet('section-nav') ?><?php endslot() ?>
  <?php slot() ?>
    <section class="calendar-view" aria-labelledby="calendar-month">
      <div class="calendar-head">
        <h2 id="calendar-month"><?= $title ?></h2>
        <ul class="calendar-nav">
          <li><a href="<?= $prev['url'] ?>" rel="prev"><span aria-hidden="true">←</span> <span class="visually-hidden"><?= t('calendar.prev') ?>: </span><?= $prev['label'] ?></a></li>
          <?php if ($isCurrent === false): ?>
          <li><a href="<?= $page->url() ?>"><?= t('calendar.current') ?></a></li>
          <?php endif ?>
          <li><a href="<?= $next['url'] ?>" rel="next"><span class="visually-hidden"><?= t('calendar.next') ?>: </span><?= $next['label'] ?> <span aria-hidden="true">→</span></a></li>
        </ul>
      </div>

      <table class="calendar">
        <caption class="visually-hidden"><?= $title ?></caption>
        <thead>
          <tr>
            <?php foreach ($weeks[0] as $day): ?>
            <th scope="col"><abbr title="<?= formatDate($day['time'], 'EEEE') ?>"><?= formatDate($day['time'], 'EEE') ?></abbr></th>
            <?php endforeach ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($weeks as $week): ?>
          <tr>
            <?php foreach ($week as $day): ?>
            <td class="calendar__day<?= $day['inMonth'] ? '' : ' is-outside' ?><?= $day['isToday'] ? ' is-today' : '' ?>"<?= $day['isToday'] ? ' aria-current="date"' : '' ?>>
              <span class="calendar__date"><?= date('j', $day['time']) ?></span>
              <?php if ($day['entries'] !== []): ?>
              <ul>
                <?php foreach ($day['entries'] as $entry): ?>
                <li><?php snippet('calendar-entry', ['entry' => $entry]) ?></li>
                <?php endforeach ?>
              </ul>
              <?php endif ?>
            </td>
            <?php endforeach ?>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>

      <div class="calendar-agenda">
        <?php foreach ($agenda as $day): ?>
        <h3<?= $day['isToday'] ? ' aria-current="date"' : '' ?>><?= Str::ucfirst(formatDate($day['time'], 'EEEE d MMMM')) ?></h3>
        <ul>
          <?php foreach ($day['entries'] as $entry): ?>
          <li><?php snippet('calendar-entry', ['entry' => $entry]) ?></li>
          <?php endforeach ?>
        </ul>
        <?php endforeach ?>
        <?php if ($agenda === []): ?><p><?= t('calendar.empty') ?></p><?php endif ?>
      </div>
    </section>

    <h2><?= t('calendar.upcoming') ?></h2>
    <?php if ($upcoming === []): ?>
    <p><?= t('calendar.upcoming.empty') ?></p>
    <?php else: ?>
    <ul class="upcoming">
      <?php foreach ($upcoming as $entry): ?>
      <li>
        <span class="upcoming__date"><?= $entry['type'] === 'deadline' ? formatDate($entry['first']) : $entry['post']->when() ?></span>
        <?php snippet('calendar-entry', ['entry' => $entry]) ?>
      </li>
      <?php endforeach ?>
    </ul>
    <?php endif ?>

    <h2><?= t('calendar.subscribe') ?></h2>
    <p><?= t('calendar.subscribe.text') ?></p>
    <dl class="infocard subscribe">
      <?php foreach ($feeds as $feed): ?>
      <dt><?= esc($feed['label']) ?></dt>
      <dd>
        <a href="<?= $feed['links'][1] ?>"><?= t('calendar.apple') ?></a> ·
        <a href="<?= $feed['links'][2] ?>" rel="external"><?= t('calendar.google') ?></a>
      </dd>
      <?php endforeach ?>
    </dl>
    <p><?= t('calendar.url') ?>: <code class="feed-url"><?= $feeds[0]['links'][0] ?></code></p>
  <?php endslot() ?>
<?php endsnippet() ?>
