/*
 * Barra ORA/LIVE (eventi del giorno) e toast delle ultime dalla bacheca.
 * I dati arrivano da live.json, che non va in cache: si aggiornano dal pannello senza deploy.
 * Il giorno è quello di Roma, letto dal browser; ogni minuto si ricontrolla, così a mezzanotte cambia da solo.
 * Senza JavaScript non compare nulla: sono aiuti, non contenuti essenziali.
 */
(() => {
  const live = document.querySelector('[data-live]');
  const toasts = document.getElementById('toasts');
  const endpoint = (live || toasts)?.dataset.url;
  if (!endpoint) return;

  const romeToday = () => new Intl.DateTimeFormat('sv-SE', { timeZone: 'Europe/Rome' }).format(new Date());
  const read = (key) => { try { return localStorage.getItem(key); } catch { return null; } };
  const write = (key, value) => { try { localStorage.setItem(key, value); } catch { /* la scelta non si ricorda */ } };

  // in sviluppo ?today=AAAA-MM-GG simula un altro giorno (il server lo accetta solo con il debug acceso)
  const wanted = new URLSearchParams(location.search).get('today');
  const url = endpoint + (/^\d{4}-\d{2}-\d{2}$/.test(wanted || '') ? `?today=${wanted}` : '');

  fetch(url, { credentials: 'same-origin' })
    .then((response) => (response.ok ? response.json() : null))
    .then((data) => {
      if (!data) return;
      const today = () => (data.debug && wanted ? wanted : romeToday());
      if (live) startLive(live, data.events || [], today);
      if (toasts) startToasts(toasts, data.toasts || [], today);
    })
    .catch(() => {});

  /* ---- ORA/LIVE ---- */
  function startLive(root, events, today) {
    const link = root.querySelector('[data-live-link]');
    const when = root.querySelector('[data-live-when]');
    const nav = root.querySelector('[data-live-nav]');
    const count = root.querySelector('[data-live-count]');
    let current = 0;
    let shown = [];

    const happening = () => events.filter((event) => event.first <= today() && today() <= event.last);

    const render = () => {
      if (shown.length === 0) {
        root.hidden = true;
        return;
      }
      current = (current + shown.length) % shown.length;
      const event = shown[current];
      link.textContent = event.title;
      link.href = event.url;
      when.textContent = event.when ? `(${event.when})` : '';
      nav.hidden = shown.length < 2;
      count.textContent = `${current + 1}/${shown.length}`;
      root.hidden = false;
    };

    const refresh = () => {
      const ids = happening();
      if (ids.map((e) => e.id).join() !== shown.map((e) => e.id).join()) {
        shown = ids;
        current = 0;
      }
      render();
    };

    root.querySelector('[data-live-prev]').addEventListener('click', () => { current -= 1; render(); });
    root.querySelector('[data-live-next]').addEventListener('click', () => { current += 1; render(); });
    refresh();
    setInterval(refresh, 60000);
  }

  /* ---- Toast ---- */
  function startToasts(root, all, today) {
    const items = all.filter((item) => item.until >= today());
    if (items.length === 0) return;

    const KEY = 'iea-toasts-closed';
    const stamp = items.map((item) => item.id).join(',');
    if (read(KEY) === stamp) return; // chiusi da chi legge, finché le notizie non cambiano

    const icon = (path) => `<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="${path}"/></svg>`;
    const make = (tag, className, text) => {
      const node = document.createElement(tag);
      if (className) node.className = className;
      if (text !== undefined) node.textContent = text;
      return node;
    };

    const bar = make('div', 'toasts__bar');
    const prev = make('button', 'toasts__btn');
    const next = make('button', 'toasts__btn');
    prev.type = next.type = 'button';
    prev.setAttribute('aria-label', root.dataset.prev);
    next.setAttribute('aria-label', root.dataset.next);
    prev.innerHTML = icon('m15 5-7 7 7 7');
    next.innerHTML = icon('m9 5 7 7-7 7');
    const label = make('p', 'toasts__label');
    label.setAttribute('aria-live', 'polite');
    const read_ = make('a', 'toasts__btn', root.dataset.read);
    const close = make('button', 'toasts__btn', `${root.dataset.close} ×`);
    close.type = 'button';
    if (items.length > 1) bar.append(prev, next);
    bar.append(label, read_, close);

    const stack = make('div', 'toasts__stack');
    const cards = items.map((item) => {
      const card = make('a', 'toast');
      card.href = item.url;
      if (item.image) {
        const image = make('img', 'toast__image');
        image.src = item.image;
        image.alt = '';
        image.width = 96;
        image.height = 96;
        card.append(image);
      }
      const body = make('div', 'toast__body');
      const meta = make('div', 'toast__meta');
      if (item.kind) meta.append(make('span', 'toast__kind', item.kind));
      meta.append(make('span', '', item.label));
      body.append(meta, make('p', 'toast__title', item.title));
      card.append(body);
      stack.append(card);
      return card;
    });
    root.append(bar, stack);

    let current = 0;
    const render = () => {
      cards.forEach((card, i) => {
        const offset = (i - current + cards.length) % cards.length;
        card.dataset.offset = offset > 2 ? 'far' : String(offset);
        if (offset === 0) card.removeAttribute('aria-hidden'); else card.setAttribute('aria-hidden', 'true');
        card.tabIndex = offset === 0 ? 0 : -1;
      });
      read_.href = items[current].url;
      label.replaceChildren(make('span', 'toasts__name', cards.length > 1 ? `${root.dataset.label} | ` : root.dataset.label));
      if (cards.length > 1) label.append(make('span', 'toasts__count', `${current + 1}/${cards.length}`));
    };
    const go = (step) => { current = (current + step + cards.length) % cards.length; render(); };

    prev.addEventListener('click', () => go(-1));
    next.addEventListener('click', () => go(1));
    const dismiss = () => {
      root.classList.remove('is-open');
      write(KEY, stamp);
      setTimeout(() => { root.hidden = true; }, 400);
    };
    close.addEventListener('click', dismiss);
    root.addEventListener('keydown', (event) => { if (event.key === 'Escape') dismiss(); });

    render();
    root.hidden = false;
    // un attimo dopo il caricamento, perché l'ingresso si noti senza coprire il contenuto
    setTimeout(() => root.classList.add('is-open'), 1200);
  }
})();
