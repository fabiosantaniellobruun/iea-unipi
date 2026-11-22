#!/usr/bin/env bash
# Carica il sito su dev.iea.ing.unipi.it. Da lanciare dalla rete dell'Ateneo (o con la VPN).
#
#   tools/carica-dev.sh pulisci     cancella dal server i file di controllo
#   tools/carica-dev.sh log         scarica i log degli ultimi 10 giorni (sola lettura)
#   tools/carica-dev.sh elenco      elenca le cartelle sul server (nessuna scrittura)
#   tools/carica-dev.sh controllo   i tre file di controllo di PHP e .htaccess (da cancellare dopo)
#   tools/carica-dev.sh primo       primo caricamento: tutto, contenuti e account del pannello compresi
#   tools/carica-dev.sh aggiorna    aggiornamenti: codice, grafica e librerie; MAI content/ e account
#
# Aggiungi --dry-run per vedere cosa verrebbe caricato senza caricare nulla.
#
# La password sta nel portachiavi (si inserisce una volta con:
#   security add-generic-password -a polo6adm_deviea -s iea-dev-ftp -w ).
# Serve lftp:  brew install lftp
# Variabili: FTP_USER, FTP_HOST, REMOTE_DIR (cartella pubblica sul server, di solito "/" o "public_html"),
# VERIFICA_CERTIFICATO=no se il certificato FTPS del server non è valido.
set -euo pipefail

FTP_USER="${FTP_USER:-polo6adm_deviea}"
FTP_HOST="${FTP_HOST:-dev.iea.ing.unipi.it}"
REMOTE_DIR="${REMOTE_DIR:-/}"
SITO="$(cd "$(dirname "$0")/.." && pwd)"
MODO="${1:-}"
EXTRA="${2:-}"

command -v lftp >/dev/null || { echo "Manca lftp: brew install lftp" >&2; exit 1; }
PASSWORD="$(security find-generic-password -a "$FTP_USER" -s iea-dev-ftp -w)" || { echo "Password non trovata nel portachiavi" >&2; exit 1; }

DRY=""; [ "$EXTRA" = "--dry-run" ] && DRY="--dry-run"
VERIFICA="yes"; [ "${VERIFICA_CERTIFICATO:-yes}" = "no" ] && VERIFICA="no"

if [ "$MODO" = "pulisci" ]; then
  lftp -f /dev/stdin <<LFTP
set ftp:ssl-allow yes
set ftp:ssl-force no
set ssl:verify-certificate $([ "${VERIFICA_CERTIFICATO:-yes}" = "no" ] && echo no || echo yes)
open -u $FTP_USER,$PASSWORD $FTP_HOST
rm -f ${REMOTE_DIR}/controllo-server.php ${REMOTE_DIR}/prova-rewrite.php ${REMOTE_DIR}/version.php
bye
LFTP
  exit 0
fi

if [ "$MODO" = "log" ]; then
  # scarica i log del server (sola lettura) in una cartella temporanea
  DEST="${LOG_DIR:-$(mktemp -d)}"
  lftp -f /dev/stdin <<LFTP
set ftp:ssl-allow yes
set ftp:ssl-force no
set ssl:verify-certificate $([ "${VERIFICA_CERTIFICATO:-yes}" = "no" ] && echo no || echo yes)
open -u $FTP_USER,$PASSWORD $FTP_HOST
mirror --include-glob=*.log* --newer-than=now-10days /log "$DEST"
bye
LFTP
  echo "Log in $DEST"
  exit 0
fi

if [ "$MODO" = "elenco" ]; then
  lftp -f /dev/stdin <<LFTP
set ftp:ssl-allow yes
set ftp:ssl-force no
set ssl:verify-certificate $([ "${VERIFICA_CERTIFICATO:-yes}" = "no" ] && echo no || echo yes)
set net:max-retries 2
open -u $FTP_USER,$PASSWORD $FTP_HOST
cls -l ${REMOTE_DIR}
bye
LFTP
  exit 0
fi

# Copia pulita da caricare: i filtri con "/" iniziale valgono solo nella cartella principale
# (dentro kirby/ e vendor/ servono i loro composer.json e README).
case "$MODO" in
  controllo) SORGENTE="$SITO/tools/controllo"; FILTRI=(--exclude=.DS_Store) ;;
  primo|aggiorna)
    SORGENTE="$SITO"
    FILTRI=(--exclude=/tools --exclude=/media --exclude=/README.md --exclude=/composer.json --exclude=/composer.lock
            --exclude=/.gitignore --exclude=/site/cache --exclude=/site/sessions --exclude=/site/config/.license --exclude=.DS_Store)
    # in aggiornamento non si toccano i contenuti scritti dal cliente sul server né gli account del pannello
    [ "$MODO" = "aggiorna" ] && FILTRI+=(--exclude=/content --exclude=/site/accounts)
    ;;
  *) sed -n '2,11p' "$0"; exit 1 ;;
esac
PREPARATA="$(mktemp -d)"
trap 'rm -rf "$PREPARATA"' EXIT
rsync -a "${FILTRI[@]}" "$SORGENTE"/ "$PREPARATA"/

echo "Da: $SORGENTE ($(find "$PREPARATA" -type f | wc -l | tr -d " ") file)"
echo "A:  $FTP_USER@$FTP_HOST:$REMOTE_DIR  (modo: $MODO ${DRY:+, prova senza caricare})"

# La password va a lftp da stdin, non come argomento: non compare nell'elenco dei processi.
lftp -f /dev/stdin <<LFTP
set ftp:ssl-allow yes
set ftp:ssl-force no
set ssl:verify-certificate $VERIFICA
set net:max-retries 3
set net:timeout 30
open -u $FTP_USER,$PASSWORD $FTP_HOST
mirror -R --verbose --parallel=4 $DRY "$PREPARATA" "$REMOTE_DIR"
bye
LFTP
echo "Fatto."
[ "$MODO" = "controllo" ] && echo "Ora apri https://$FTP_HOST/controllo-server.php e, subito dopo, cancella dal server controllo-server.php, prova-rewrite.php e .htaccess."
