<?php

/**
 * Reimposta la password di un utente del pannello, dal computer dello sviluppatore.
 *
 * Uso, dalla cartella sito/:   php tools/cambia-password.php email@esempio.it
 *
 * La password si scrive nel terminale e non viene mostrata né salvata altrove: Kirby ne conserva solo l'hash.
 * Serve quando il recupero via email non è disponibile, per esempio in locale.
 */

use Kirby\Cms\App;

$sito = dirname(__DIR__);
require $sito . '/kirby/bootstrap.php';

$email = $argv[1] ?? '';
if ($email === '') {
    fwrite(STDERR, "Uso: php tools/cambia-password.php email@esempio.it\n");
    exit(1);
}

$kirby = new App(['roots' => ['index' => $sito]]);
$kirby->impersonate('kirby');

$user = $kirby->user($email);
if ($user === null) {
    fwrite(STDERR, "Nessun utente con l'email $email\n");
    exit(1);
}

/** Legge una riga dal terminale senza mostrarla */
function askHidden(string $prompt): string
{
    echo $prompt;
    system('stty -echo');
    $value = trim((string)fgets(STDIN));
    system('stty echo');
    echo "\n";
    return $value;
}

$password = askHidden('Nuova password per ' . $user->email() . ' (almeno 8 caratteri, non si vede mentre scrivi): ');
if ($password !== askHidden('Ripeti la password: ')) {
    fwrite(STDERR, "Le due password non coincidono: nulla è cambiato.\n");
    exit(1);
}

try {
    $user->changePassword($password);
} catch (Throwable $e) {
    fwrite(STDERR, 'Password non cambiata: ' . $e->getMessage() . "\n");
    exit(1);
}

echo 'Fatto: ora puoi accedere al pannello come ' . $user->email() . ' (' . $user->role()->title() . ").\n";
