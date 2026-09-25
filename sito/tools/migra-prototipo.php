<?php

/**
 * Migrazione dei contenuti dal prototipo a Kirby (Fase 4 del piano, docs/05-piano-sito-kirby.md).
 *
 * Uso, dalla cartella sito/:   php tools/migra-prototipo.php
 *
 * Legge i Markdown del prototipo (../content, italiano) e la loro traduzione (tools/prototipo-en,
 * stessa struttura, facoltativa) e riscrive in content/ la home, le pagine del menu e del footer
 * e i post della bacheca. Non tocca site, error e styleguide.
 * - I testi diventano blocchi: titolo, testo, elenco, tabella, scheda informativa, avviso, allegati.
 * - Gli allegati del sito attuale (iea.ing.unipi.it) si scaricano in tools/.cache e si copiano nella
 *   cartella della prima pagina che li usa; le altre pagine li raggiungono con un link.
 * - Le segnalazioni [DA VERIFICARE] e [DA FORNIRE] restano nel testo.
 * - Gli Uuid di pagine e file già presenti si conservano: lo script si può rilanciare.
 */

use Kirby\Cms\App;
use Kirby\Data\Data;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;
use Kirby\Http\Remote;
use Kirby\Text\Markdown;
use Kirby\Toolkit\Str;
use Kirby\Uuid\Uuid;

$sito = dirname(__DIR__);
require $sito . '/kirby/bootstrap.php';
new App(['roots' => ['index' => $sito]]);

const LANGS = ['it', 'en'];
$SRC   = ['it' => dirname($sito) . '/content', 'en' => __DIR__ . '/prototipo-en'];
$OUT   = $sito . '/content';
$CACHE = __DIR__ . '/.cache';

// Testo alternativo delle immagini scaricate: nel pannello è obbligatorio
$ALT = [
	'barozzi-aa-2025.jpg' => [
		'it' => 'Locandina: Fabrizio Barozzi (Barozzi Veiga), "Abby Kortrijk", 17 settembre 2025 alle 11:00, Aula Magna della Scuola di Ingegneria di Pisa. Foto di un edificio scuro a torre accanto a una casa in mattoni.',
		'en' => 'Poster: Fabrizio Barozzi (Barozzi Veiga), "Abby Kortrijk", 17 September 2025 at 11:00, Main Hall of the School of Engineering, Pisa. Photo of a dark tower-like building next to a brick house.',
	],
	'locandina_premio_aidia_2026.png' => [
		'it' => 'Locandina del premio AIDIA "Idee per un mondo che cambia", 2ª edizione: 5 premi per professioniste laureate in ingegneria e architettura, candidature entro il 21 settembre 2026.',
		'en' => 'Poster of the AIDIA award "Ideas for a changing world", 2nd edition: 5 awards for women graduates in engineering and architecture, applications by 21 September 2026.',
	],
];

// Fine degli eventi di più giorni: nel prototipo c'era solo la data d'inizio, la fine è nel testo
$END = [
	'bacheca/post/2025-10-21-florence-biennale'     => '2025-10-24',
	'bacheca/post/2026-07-28-progettare-la-memoria' => '2026-08-01',
];

// Ritocchi redazionali dovuti al passaggio al nuovo sito: [pagina, lingua, testo da cercare, sostituzione]
$FIXES = [
	['bacheca/archivio', 'it', ' Ogni post rimanda all\'originale.', ''],
	['bacheca/archivio', 'it', 'I contenuti della bacheca degli anni precedenti ancora presenti nel sito attuale.', 'I post della bacheca degli anni precedenti.'],
	['bacheca/post/2026-04-29-progettare-con-mies-e-klee', 'it', "\n\nNel sito attuale i due incontri sono due post separati: qui sono uniti.", ''],
];

$warnings = [];
function warn(string $message): void
{
	global $warnings;
	$warnings[] = $message;
}

// ---------------------------------------------------------------- lettura del prototipo

function readMd(string $path): ?array
{
	if (is_file($path) === false) {
		return null;
	}

	$text = file_get_contents($path);
	$meta = [];

	if (str_starts_with($text, '---') === true) {
		[, $frontmatter, $text] = explode('---', $text, 3);
		foreach (preg_split('/\R/', trim($frontmatter)) as $line) {
			[$key, $value] = array_map('trim', explode(':', $line, 2));
			$meta[$key] = $value;
		}
	}

	return ['meta' => $meta, 'body' => trim($text)];
}

/** Uuid di una pagina o di un file già presente, per non romperne i riferimenti */
function existingUuid(string $pattern): ?string
{
	foreach (glob($pattern) as $file) {
		if (preg_match('/^Uuid:\s*(\S+)/m', file_get_contents($file), $match)) {
			return $match[1];
		}
	}

	return null;
}

/** Id stabile per i blocchi, così una nuova esecuzione non cambia i file se il testo è uguale */
function stableId(string $seed): string
{
	$hash = md5($seed);
	return substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-4' . substr($hash, 13, 3) . '-a' . substr($hash, 17, 3) . '-' . substr($hash, 20, 12);
}

// ---------------------------------------------------------------- elenco delle pagine

$nav   = Yaml::decode(file_get_contents($SRC['it'] . '/_nav.yml'));
$pages = ['index' => ['dir' => 'home', 'template' => 'home']];

foreach ($nav['menu'] as $i => $section) {
	$slug     = strtok($section['page'], '/');
	$dir      = ($i + 1) . '_' . $slug;
	$children = $section['children'] ?? [];

	$pages[$section['page']] = [
		'dir'      => $dir,
		'template' => $slug === 'bacheca' ? 'bacheca' : ($children === [] ? 'default' : 'sezione'),
		'label'    => $section['label'],
	];

	foreach ($children as $j => $child) {
		$childSlug = basename($child['page']);
		$pages[$child['page']] = [
			'dir'      => $dir . '/' . ($j + 1) . '_' . $childSlug,
			'template' => $slug === 'bacheca' ? ($childSlug === 'archivio' ? 'bacheca-archivio' : 'bacheca-categoria') : 'default',
			'label'    => $child['label'],
		];
	}
}

$footerDirs = ['servizi/index' => 'servizi-di-ateneo'];
foreach ($nav['footer'] as $item) {
	$pages[$item['page']] = [
		'dir'      => $footerDirs[$item['page']] ?? strtok($item['page'], '/'),
		'template' => 'default',
		'label'    => $item['label'],
	];
}

// Post della bacheca: il nome del file senza la data è l'indirizzo
foreach (glob($SRC['it'] . '/bacheca/post/*.md') as $file) {
	$name = basename($file, '.md');
	$pages['bacheca/post/' . $name] = [
		'dir'      => $pages['bacheca/index']['dir'] . '/' . preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', $name),
		'template' => 'post',
	];
}

$byDir = [];
foreach ($pages as $key => &$page) {
	foreach (LANGS as $lang) {
		$page['src'][$lang] = readMd($SRC[$lang] . '/' . $key . '.md');
	}

	if ($page['src']['it'] === null) {
		throw new Exception('Manca il testo italiano di ' . $key);
	}

	$page['uuid']        = existingUuid($OUT . '/' . $page['dir'] . '/*.it.txt') ?? Uuid::generate();
	$byDir[$page['dir']] = $key;
}
unset($page);

function slugOf(string $key, string $lang): string
{
	global $pages;
	$slug = preg_replace('/^\d+_/', '', basename($pages[$key]['dir']));
	return $lang === 'it' ? $slug : ($pages[$key]['src'][$lang]['meta']['slug'] ?? $slug);
}

function pageUrl(string $key, string $lang, string $anchor = ''): string
{
	global $pages, $byDir;

	if ($key === 'index') {
		return '/' . $lang;
	}

	$parts = explode('/', $pages[$key]['dir']);
	$slugs = [];
	for ($i = 1; $i <= count($parts); $i++) {
		$slugs[] = slugOf($byDir[implode('/', array_slice($parts, 0, $i))], $lang);
	}

	return '/' . $lang . '/' . implode('/', $slugs) . ($anchor !== '' ? '#' . $anchor : '');
}

function keyFromPath(string $path): ?string
{
	global $pages;
	$path = trim($path, '/');

	if ($path === '') {
		return 'index';
	}

	foreach ([$path, $path . '/index'] as $key) {
		if (isset($pages[$key]) === true) {
			return $key;
		}
	}

	return null;
}

// ---------------------------------------------------------------- Markdown e HTML

function markdownToHtml(string $markdown): string
{
	return (new Markdown(['extra' => true, 'breaks' => false]))->parse($markdown);
}

function dom(string $html): DOMElement
{
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML('<?xml encoding="utf-8"?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	libxml_clear_errors();
	return $dom->getElementsByTagName('div')->item(0);
}

function innerHtml(DOMNode $node): string
{
	$html = '';
	foreach ($node->childNodes as $child) {
		$html .= $node->ownerDocument->saveHTML($child);
	}
	return trim($html);
}

/** Slug dei titoli usato dal prototipo (python-markdown, estensione toc), per riconoscere le ancore */
function tocSlug(string $text): string
{
	$text = Normalizer::normalize($text, Normalizer::FORM_KD);
	$text = preg_replace('/[^\x00-\x7F]/', '', $text);
	$text = strtolower(trim(preg_replace('/[^\w\s-]/', '', $text)));
	return preg_replace('/[-\s]+/', '-', $text);
}

/** Titoli di una pagina, in ordine: [ancora del prototipo, ancora del nuovo sito] */
function headings(string $key, string $lang): array
{
	global $pages;
	static $cache = [];

	if (isset($cache[$key][$lang]) === false) {
		$cache[$key][$lang] = [];
		$root = dom(markdownToHtml($pages[$key]['src'][$lang]['body'] ?? ''));
		foreach ($root->childNodes as $node) {
			if (in_array($node->nodeName, ['h2', 'h3'], true)) {
				$cache[$key][$lang][] = [tocSlug($node->textContent), Str::slug($node->textContent)];
			}
		}
	}

	return $cache[$key][$lang];
}

/**
 * Ancora del titolo nel nuovo sito: la stessa che calcola site/snippets/blocks/heading.php.
 * Nei testi tradotti si può lasciare l'ancora italiana: vale il titolo nella stessa posizione.
 */
function kirbyAnchor(string $key, string $lang, string $protoAnchor): string
{
	foreach (headings($key, $lang) as [$toc, $slug]) {
		if ($toc === $protoAnchor) {
			return $slug;
		}
	}

	foreach (headings($key, 'it') as $i => [$toc]) {
		if ($toc === $protoAnchor && isset(headings($key, $lang)[$i]) === true) {
			return headings($key, $lang)[$i][1];
		}
	}

	warn("Ancora #$protoAnchor non trovata in $key ($lang)");
	return $protoAnchor;
}

// ---------------------------------------------------------------- allegati

$files      = []; // indirizzo normalizzato => dati del file
$fileByHref = []; // lingua => href nel nuovo sito => indirizzo normalizzato

function normalizeFileUrl(string $url): ?string
{
	if (preg_match('#^https?://(www\.)?iea\.ing\.unipi\.it/(.+)$#', $url, $match) === 0) {
		return null;
	}

	return preg_match('/\.(pdf|docx?|xlsx?|odt|zip|pptx?|jpe?g|png)$/i', $match[2]) ? 'https://iea.ing.unipi.it/' . $match[2] : null;
}

function registerFile(string $url, string $key, string $lang, string $text): string
{
	global $files, $pages, $OUT;

	if (isset($files[$url]) === false) {
		if ($lang !== 'it') {
			warn("Allegato citato solo in inglese, in $key: $url");
		}

		$filename = F::safeName(rawurldecode(basename(parse_url($url, PHP_URL_PATH))));
		$taken    = array_column(array_filter($files, fn ($file) => $file['owner'] === $key), 'filename');
		$base     = F::name($filename);
		for ($n = 2; in_array($filename, $taken, true); $n++) {
			$filename = $base . '-' . $n . '.' . F::extension($filename);
		}

		$files[$url] = [
			'owner'    => $key,
			'filename' => $filename,
			'image'    => in_array(F::extension($filename), ['jpg', 'jpeg', 'png'], true),
			'uuid'     => existingUuid($OUT . '/' . $pages[$key]['dir'] . '/' . $filename . '.it.txt') ?? Uuid::generate(),
			'titles'   => [],
		];
	}

	$files[$url]['titles'][$lang] ??= $text;
	return $url;
}

function fileHref(string $url, string $lang): string
{
	global $files;
	return pageUrl($files[$url]['owner'], $lang) . '/' . $files[$url]['filename'];
}

// ---------------------------------------------------------------- dai Markdown ai blocchi

function block(string $type, array $content, string $seed): array
{
	return ['content' => $content, 'id' => stableId($seed), 'isHidden' => false, 'type' => $type];
}

/** Riscrive i link: pagine del prototipo -> pagine del nuovo sito, allegati del sito attuale -> file del nuovo sito */
function rewriteLinks(DOMElement $root, string $key, string $lang): void
{
	global $fileByHref;

	foreach ($root->getElementsByTagName('a') as $link) {
		$href = $link->getAttribute('href');

		if ($fileUrl = normalizeFileUrl($href)) {
			registerFile($fileUrl, $key, $lang, trim($link->textContent));
			$new = fileHref($fileUrl, $lang);
			$fileByHref[$lang][$new] = $fileUrl;
			$link->setAttribute('href', $new);
		} elseif (str_starts_with($href, '/') === true) {
			[$path, $anchor] = array_pad(explode('#', $href, 2), 2, '');
			$target = keyFromPath($path);
			if ($target === null) {
				warn("Link interno sconosciuto in $key ($lang): $href");
				continue;
			}
			$link->setAttribute('href', pageUrl($target, $lang, $anchor !== '' ? kirbyAnchor($target, $lang, $anchor) : ''));
		} elseif (preg_match('#iea\.ing\.unipi\.it#', $href)) {
			warn("Link a una pagina del sito attuale in $key ($lang): $href");
		}
	}
}

/** File del paragrafo, se il paragrafo contiene solo link ad allegati, separati da "·" e con il formato tra parentesi */
function onlyFiles(DOMElement $node, string $lang): ?array
{
	global $fileByHref;

	$links = iterator_to_array($node->getElementsByTagName('a'));
	if ($links === []) {
		return null;
	}

	$urls = [];
	foreach ($links as $link) {
		$url = $fileByHref[$lang][$link->getAttribute('href')] ?? null;
		if ($url === null) {
			return null;
		}
		$urls[] = $url;
	}

	$rest = $node->textContent;
	foreach ($links as $link) {
		$rest = str_replace($link->textContent, '', $rest);
	}

	return preg_match('/^(\s*(\((PDF|DOCX?|XLSX?|ZIP|JPG|PNG)\))?\s*·?\s*)*$/i', $rest) ? $urls : null;
}

function toBlocks(string $markdown, string $key, string $lang, array &$images): array
{
	global $files;

	$root = dom(markdownToHtml($markdown));
	rewriteLinks($root, $key, $lang);

	$blocks     = [];
	$paragraphs = [];
	$seed       = function () use (&$blocks, $key, $lang) {
		return $key . '|' . $lang . '|' . count($blocks);
	};
	$flush      = function () use (&$blocks, &$paragraphs, $seed) {
		if ($paragraphs !== []) {
			$blocks[]   = block('text', ['text' => implode('', $paragraphs)], $seed());
			$paragraphs = [];
		}
	};

	foreach ($root->childNodes as $node) {
		if ($node instanceof DOMText) {
			if (trim($node->textContent) !== '') {
				warn("Testo fuori dai paragrafi in $key ($lang): " . trim($node->textContent));
			}
			continue;
		}

		switch ($node->nodeName) {
			case 'h2':
			case 'h3':
				$flush();
				$blocks[] = block('heading', ['level' => $node->nodeName, 'text' => trim($node->textContent)], $seed());
				break;

			case 'p':
				$inner = innerHtml($node);
				if (preg_match('#^<strong>(Ricorda|Remember):</strong>\s*(.*)$#s', $inner, $match)) {
					$flush();
					$blocks[] = block('notice', ['label' => $match[1] . ':', 'text' => '<p>' . $match[2] . '</p>'], $seed());
				} elseif ($urls = onlyFiles($node, $lang)) {
					$flush();
					$documents = array_values(array_filter($urls, fn ($url) => $files[$url]['image'] === false));
					$pictures  = array_values(array_filter($urls, fn ($url) => $files[$url]['image'] === true));
					array_push($images, ...$pictures);
					if ($documents !== []) {
						$blocks[] = block('attachments', ['files' => array_map(fn ($url) => 'file://' . $files[$url]['uuid'], $documents)], $seed());
					}
				} else {
					$paragraphs[] = '<p>' . preg_replace('/\s*\n\s*/', ' ', $inner) . '</p>';
				}
				break;

			case 'ul':
			case 'ol':
				$flush();
				$items = [];
				foreach ($node->childNodes as $item) {
					if ($item->nodeName === 'li') {
						$inner   = preg_replace('/\s*\n\s*/', ' ', innerHtml($item));
						$items[] = '<li>' . (str_starts_with($inner, '<p>') ? $inner : '<p>' . $inner . '</p>') . '</li>';
					}
				}
				$blocks[] = block('list', ['text' => '<' . $node->nodeName . '>' . implode('', $items) . '</' . $node->nodeName . '>'], $seed());
				break;

			case 'table':
				$flush();
				$rows = [];
				foreach ($node->getElementsByTagName('tr') as $tr) {
					$cells = [];
					foreach ($tr->childNodes as $cell) {
						if (in_array($cell->nodeName, ['th', 'td'], true)) {
							$cells[] = innerHtml($cell);
						}
					}
					$rows[] = $cells;
				}
				$header = array_shift($rows);
				if (count($header) === 2 && implode('', array_map('trim', $header)) === '') {
					$blocks[] = block('infocard', ['rows' => array_map(fn ($row) => ['label' => trim(strip_tags($row[0])), 'value' => $row[1]], $rows)], $seed());
				} else {
					if (count($header) > 4) {
						throw new Exception("Tabella con più di 4 colonne in $key ($lang)");
					}
					$named = fn ($row) => array_combine(array_map(fn ($i) => 'c' . ($i + 1), array_keys($row)), $row);
					$blocks[] = block('table', ['caption' => '', 'rows' => array_map($named, [$header, ...$rows])], $seed());
				}
				break;

			default:
				warn("Elemento non gestito in $key ($lang): <{$node->nodeName}>");
		}
	}

	$flush();
	return $blocks;
}

/** Card delle pagine indice: [link => testo] per le sezioni, [domanda => link] per la home */
function extractCards(string &$body): array
{
	$cards = [];

	if (preg_match('~(?:^## [^\n]+\n)?<div class="cards" markdown="1">\s*((?:<div markdown="1">.*?</div>\s*)+)</div>~sm', $body, $match) === 0) {
		return $cards;
	}

	// il titolo "In questa sezione" prima delle card lo scrive il template
	$remove = $match[0];
	if (preg_match('/^## (In questa sezione|In this section)\n/', $remove) === 0) {
		$remove = preg_replace('/^## [^\n]+\n/', '', $remove);
	}
	$body = trim(str_replace($remove, '', $body));

	preg_match_all('#<div markdown="1">\s*(.*?)\s*</div>#s', $match[1], $divs);
	foreach ($divs[1] as $card) {
		$lines   = preg_split('/\R/', trim($card));
		$first   = array_shift($lines);
		preg_match_all('/\[([^\]]+)\]\(([^)]+)\)/', $card, $links, PREG_SET_ORDER);
		$cards[] = [
			'title' => trim(strip_tags(markdownToHtml(trim($first, '*')))),
			'links' => array_map(fn ($link) => $link[2], $links),
			'text'  => trim(strip_tags(markdownToHtml(implode(' ', $lines)))),
		];
	}

	return $cards;
}

// ---------------------------------------------------------------- contenuti delle pagine

$content = []; // chiave => lingua => campi

foreach (LANGS as $lang) {
	foreach ($pages as $key => $page) {
		$source = $page['src'][$lang];
		if ($source === null) {
			continue;
		}

		$meta = $source['meta'];
		$body = $source['body'];

		foreach ($FIXES as [$fixKey, $fixLang, $search, $replace]) {
			if ($fixKey === $key && $fixLang === $lang) {
				if (str_contains($body . ($meta['lead'] ?? ''), $search) === false) {
					warn("Ritocco non applicato in $key ($lang): $search");
				}
				$body = str_replace($search, $replace, $body);
				if (isset($meta['lead'])) {
					$meta['lead'] = str_replace($search, $replace, $meta['lead']);
				}
			}
		}

		// segnaposto delle liste generate dal prototipo: ora le generano i template
		$body   = trim(preg_replace('/^\{\{\w+\}\}$/m', '', $body));
		$cards  = extractCards($body);
		$images = [];
		$fields = [
			'Title' => match (true) {
				$page['template'] === 'home'            => 'Home',
				$lang === 'it' && isset($page['label']) => $page['label'],
				default                                 => $meta['title'],
			},
		];

		if ($lang !== 'it' && slugOf($key, $lang) !== slugOf($key, 'it')) {
			$fields['Slug'] = slugOf($key, $lang);
		}

		switch ($page['template']) {
			case 'home':
				$fields['Lead']      = $meta['lead'];
				$fields['Audiences'] = array_map(fn ($card) => [
					'question' => $card['title'],
					'links'    => array_map(fn ($href) => 'page://' . $pages[keyFromPath(explode('#', $href)[0])]['uuid'], $card['links']),
				], $cards);
				// "Il corso in sintesi": la tabella dopo il titolo diventa la scheda della home
				if (preg_match('/^## (Il corso in sintesi|The course at a glance)\n(\|.*?)(\n\n|\z)/sm', $body, $facts)) {
					$infocard        = toBlocks($facts[2], $key, $lang, $images)[0]['content']['rows'];
					$fields['Facts'] = $infocard;
				} else {
					warn("Scheda del corso non trovata nella home ($lang)");
				}
				break;

			case 'post':
				$fields['Summary'] = $meta['sommario'];
				if (isset($meta['luogo'])) {
					$fields['Place'] = preg_replace('/,?\s*ore \d{1,2}[:.]\d{2}$|,?\s*at \d{1,2}[:.]\d{2}$/', '', $meta['luogo']);
				}
				$fields['Text'] = json_encode(toBlocks($body, $key, $lang, $images), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				if ($lang === 'it') {
					$fields['Category'] = ['avvisi' => 'avvisi', 'eventi' => 'eventi', 'bandi' => 'bandi-e-opportunita'][$meta['categoria']];
					$fields['Date']     = $meta['data'];
					if (isset($meta['data_evento'])) {
						$time            = preg_match('/ore (\d{1,2})[:.](\d{2})/', $meta['luogo'] ?? '', $t) ? sprintf(' %02d:%02d:00', $t[1], $t[2]) : '';
						$fields['Start'] = $meta['data_evento'] . $time;
					}
					if (isset($END[$key])) {
						$fields['End'] = $END[$key];
					}
					if (isset($meta['scadenza'])) {
						$fields['Deadline'] = $meta['scadenza'];
					}
					$pages[$key]['images'] = $images;
				}
				break;

			default:
				if (isset($meta['lead'])) {
					$fields['Lead'] = $meta['lead'];
				}
				if (in_array($page['template'], ['bacheca', 'bacheca-categoria'], true) === false) {
					$fields['Text'] = json_encode(toBlocks($body, $key, $lang, $images), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				}
				if ($images !== []) {
					warn("Immagini isolate in $key ($lang): diventano allegati solo nei post");
				}
				// le card della sezione diventano la descrizione breve delle pagine figlie
				foreach ($cards as $card) {
					$child = keyFromPath($card['links'][0] ?? '');
					if ($child === null) {
						warn("Card senza pagina in $key ($lang): {$card['title']}");
						continue;
					}
					$content[$child][$lang]['Description'] = $card['text'];
				}
		}

		$content[$key][$lang] = array_merge($fields, $content[$key][$lang] ?? []);
	}
}

// Home: i post in evidenza del prototipo, dal più recente
$featured = array_filter(array_keys($pages), fn ($key) => ($pages[$key]['src']['it']['meta']['evidenza'] ?? '') === 'true');
usort($featured, fn ($a, $b) => strcmp($pages[$b]['src']['it']['meta']['data'], $pages[$a]['src']['it']['meta']['data']));
$content['index']['it']['Featured'] = array_map(fn ($key) => 'page://' . $pages[$key]['uuid'], $featured);

// Copertina dei post: la prima immagine citata nel testo
foreach ($pages as $key => $page) {
	if ($page['template'] === 'post') {
		$first = $page['images'][0] ?? null;
		foreach ($files as $url => $file) {
			if ($first === null && $file['owner'] === $key && $file['image'] === true) {
				$first = $url;
			}
		}
		if ($first !== null) {
			$content[$key]['it']['Cover'] = ['file://' . $files[$first]['uuid']];
		}
	}
}

// ---------------------------------------------------------------- scrittura

$written = 0;
foreach ($pages as $key => $page) {
	$dir = $OUT . '/' . $page['dir'];
	if (is_dir($dir) === false) {
		mkdir($dir, 0755, true);
	}

	foreach (LANGS as $lang) {
		$fields = $content[$key][$lang] ?? null;

		// file di un altro tipo di pagina rimasti nella cartella (per esempio default.it.txt diventato sezione.it.txt)
		foreach (glob($dir . '/*.' . $lang . '.txt') as $old) {
			if (basename($old) !== $page['template'] . '.' . $lang . '.txt' && ($fields !== null || $lang === 'it')) {
				unlink($old);
			}
		}

		if ($fields === null) {
			continue;
		}

		if ($lang === 'it') {
			$fields['Uuid'] = $page['uuid'];
		}

		Data::write($dir . '/' . $page['template'] . '.' . $lang . '.txt', $fields);
		$written++;
	}
}

// Post di prova rimasti nella bacheca e non più nel prototipo
$postDirs = array_map(fn ($page) => $page['dir'], array_filter($pages, fn ($page) => $page['template'] === 'post'));
foreach (glob($OUT . '/' . $pages['bacheca/index']['dir'] . '/*', GLOB_ONLYDIR) as $dir) {
	$relative = substr($dir, strlen($OUT) + 1);
	if (preg_match('/^\d+_/', basename($dir)) === 0 && in_array($relative, $postDirs, true) === false && glob($dir . '/post.*.txt') !== []) {
		array_map('unlink', glob($dir . '/*'));
		rmdir($dir);
		echo "Rimosso il post di prova $relative\n";
	}
}

// Allegati: scaricati una volta sola in tools/.cache, poi copiati nella pagina che li usa
if (is_dir($CACHE) === false) {
	mkdir($CACHE, 0755, true);
}

$bytes = 0;
foreach ($files as $url => $file) {
	$cached = $CACHE . '/' . md5($url) . '-' . $file['filename'];
	if (is_file($cached) === false) {
		$response = Remote::get($url, ['timeout' => 120]);
		if ($response->code() !== 200) {
			warn("Allegato non scaricato ({$response->code()}): $url");
			continue;
		}
		file_put_contents($cached, $response->content());
	}

	$target = $OUT . '/' . $pages[$file['owner']]['dir'] . '/' . $file['filename'];
	copy($cached, $target);
	$bytes += filesize($target);

	foreach (LANGS as $lang) {
		$meta = ['Template' => $file['image'] ? 'immagine' : 'allegato'];
		if ($file['image']) {
			$alt = $ALT[$file['filename']][$lang] ?? null;
			if ($alt === null) {
				warn("Manca il testo alternativo di {$file['filename']} ($lang)");
			} else {
				$meta['Alt'] = $alt;
			}
		} elseif (isset($file['titles'][$lang])) {
			$meta['Title'] = $file['titles'][$lang];
		}
		if ($lang === 'it') {
			$meta['Uuid'] = $file['uuid'];
		} elseif (count($meta) === 1) {
			continue;
		} else {
			unset($meta['Template']);
		}
		Data::write($target . '.' . $lang . '.txt', $meta);
	}
}

printf("Pagine e post: %d file di contenuto scritti\n", $written);
printf("Allegati: %d file, %.1f MB\n", count($files), $bytes / 1048576);
printf("Traduzioni inglesi: %d pagine su %d\n", count(array_filter($pages, fn ($page) => $page['src']['en'] !== null)), count($pages));

if ($warnings !== []) {
	echo "\nDa controllare:\n- " . implode("\n- ", array_unique($warnings)) . "\n";
}
