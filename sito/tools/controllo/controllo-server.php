<?php
/** Controllo del server per il sito Kirby. Va caricato da solo, aperto nel browser e CANCELLATO subito dopo. */
header('Content-Type: text/plain; charset=utf-8');
header('X-Robots-Tag: noindex');

$ok = fn (bool $v) => $v ? 'OK     ' : 'MANCA  ';

echo "PHP: " . PHP_VERSION . "  (serve 8.4 o successivo: " . trim($ok(PHP_VERSION_ID >= 80400)) . ")\n";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? '?') . "  | SAPI: " . PHP_SAPI . "\n\n";

echo "Estensioni\n";
foreach (['mbstring', 'ctype', 'curl', 'dom', 'filter', 'hash', 'iconv', 'json', 'libxml', 'openssl', 'simplexml', 'intl', 'gd', 'fileinfo', 'zip', 'exif'] as $ext) {
    echo '  ' . $ok(extension_loaded($ext)) . $ext . "\n";
}
echo '  (immagini: gd ' . (extension_loaded('gd') ? 'sì' : 'no') . ', imagick ' . (extension_loaded('imagick') ? 'sì' : 'no') . "; WebP in gd: " . (function_exists('imagewebp') ? 'sì' : 'no') . ")\n\n";

echo "Limiti (servono almeno 20M, 24M, 256M)\n";
foreach (['upload_max_filesize', 'post_max_size', 'memory_limit', 'max_execution_time'] as $key) {
    echo "  $key = " . ini_get($key) . "\n";
}

echo "\nCartella e permessi\n";
echo '  cartella: ' . __DIR__ . "\n";
echo '  scrittura in questa cartella: ' . trim($ok(is_writable(__DIR__))) . "\n";
echo '  mod_rewrite: ' . (function_exists('apache_get_modules') ? (in_array('mod_rewrite', apache_get_modules(), true) ? 'OK' : 'MANCA') : 'non verificabile da PHP (CGI/FPM): si vede con la prova qui sotto') . "\n";
echo '  mail(): ' . (function_exists('mail') ? 'disponibile' : 'disabilitata') . '  | sendmail_path: ' . (ini_get('sendmail_path') ?: '(vuoto)') . "\n";
echo '  fuso orario PHP: ' . date_default_timezone_get() . "\n";

echo "\nProva .htaccess: apri anche " . dirname($_SERVER['REQUEST_URI']) . "/prova-rewrite (deve mostrare 'rewrite OK' se il file .htaccess di prova è caricato)\n";
