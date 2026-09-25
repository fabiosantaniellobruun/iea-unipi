// Menu mobile e ricerca nell'header. La classe "js" su <html> la imposta lo script in linea nell'head:
// senza JavaScript il menu resta sempre visibile e la lente porta alla pagina di ricerca.
document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.site-header');
  const inner = header?.querySelector('.site-header__inner');

  const menuToggle = document.querySelector('.menu-toggle');
  const nav = document.getElementById(menuToggle?.getAttribute('aria-controls'));

  const searchToggle = document.querySelector('.search-toggle');
  const search = document.getElementById(searchToggle?.getAttribute('aria-controls'));
  const input = search?.querySelector('input');
  const status = search?.querySelector('[data-search-status]');
  const panel = document.getElementById('search-panel');

  const setMenu = (open) => {
    if (!menuToggle || !nav) return;
    menuToggle.setAttribute('aria-expanded', String(open));
    nav.classList.toggle('is-open', open);
  };

  // La ricerca prende il posto di tutta la riga dell'header; l'altezza resta quella di prima, così la pagina non salta
  const setSearch = (open) => {
    if (!searchToggle || !search) return;
    if (open) {
      setMenu(false);
      inner.style.minHeight = inner.offsetHeight + 'px';
    } else {
      inner.style.minHeight = '';
      panel.hidden = true;
      input.value = '';
      status.textContent = '';
    }
    searchToggle.setAttribute('aria-expanded', String(open));
    search.hidden = !open;
    header.classList.toggle('is-searching', open);
    if (open) input.focus();
  };

  menuToggle?.addEventListener('click', () => setMenu(menuToggle.getAttribute('aria-expanded') !== 'true'));
  searchToggle?.addEventListener('click', () => setSearch(true));
  search?.querySelector('.header-search__close').addEventListener('click', () => {
    setSearch(false);
    searchToggle.focus();
  });

  // Risultati mentre si scrive: i primi della ricerca completa (cerca.json); Invio porta alla pagina con tutti i risultati
  if (search && panel) {
    const list = panel.querySelector('[data-search-results]');
    const count = panel.querySelector('[data-search-count]');
    const all = panel.querySelector('[data-search-all]');
    let timer;
    let request;

    const render = (data) => {
      list.replaceChildren(...data.results.map((result) => {
        const item = document.createElement('li');
        const link = document.createElement('a');
        link.href = result.url;
        const title = document.createElement('span');
        title.className = 'search-panel__title';
        title.textContent = result.title;
        link.append(title);
        if (result.path) {
          const path = document.createElement('span');
          path.className = 'search-panel__path';
          path.textContent = result.path;
          link.append(path);
        }
        const excerpt = document.createElement('span');
        excerpt.className = 'search-panel__excerpt';
        excerpt.innerHTML = result.excerpt; // testo già protetto dal server, con le parole trovate in <mark>
        link.append(excerpt);
        item.append(link);
        return item;
      }));
      count.textContent = data.count;
      status.textContent = data.count;
      all.href = data.all;
      all.parentElement.hidden = data.total <= data.results.length;
      panel.hidden = false;
    };

    input.addEventListener('input', () => {
      clearTimeout(timer);
      const query = input.value.trim();
      if (query.length < 2) {
        panel.hidden = true;
        status.textContent = '';
        return;
      }
      timer = setTimeout(async () => {
        request?.abort();
        request = new AbortController();
        try {
          const response = await fetch(search.dataset.results + '?q=' + encodeURIComponent(query), { signal: request.signal });
          render(await response.json());
        } catch (error) {
          if (error.name !== 'AbortError') panel.hidden = true;
        }
      }, 200);
    });

    // un clic fuori dall'header chiude la ricerca
    document.addEventListener('click', (event) => {
      if (!search.hidden && !header.contains(event.target)) setSearch(false);
    });
  }

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    if (search && !search.hidden) {
      setSearch(false);
      searchToggle.focus();
    } else if (nav?.classList.contains('is-open')) {
      setMenu(false);
      menuToggle.focus();
    }
  });
});
