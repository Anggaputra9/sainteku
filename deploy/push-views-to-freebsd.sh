#!/usr/bin/env bash
# Deploy view blade Ujian ke FreeBSD production (bukan Ubuntu dev).
set -euo pipefail

REMOTE="rifky@10.87.117.3"
SSH="ssh -p 1977 -o StrictHostKeyChecking=no"
SCP="scp -P 1977 -o StrictHostKeyChecking=no"
APP="/usr/local/www/saintekku"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"

FILES=(
  "Modules/Ujian/resources/views/rooms/index.blade.php"
  "Modules/Ujian/resources/views/rooms/modal-detail.blade.php"
)

echo "==> Push views ke FreeBSD (${REMOTE})"
for f in "${FILES[@]}"; do
  base="$(basename "$f")"
  ${SCP} "${ROOT}/${f}" "${REMOTE}:/tmp/${base}"
  ${SSH} "${REMOTE}" "doas cp /tmp/${base} ${APP}/${f} && doas chown www:www ${APP}/${f}"
  echo "    OK ${f}"
done

echo "==> view:clear + view:cache + restart apache24"
${SSH} "${REMOTE}" "doas -u www php ${APP}/artisan view:clear && doas -u www php ${APP}/artisan view:cache && doas service apache24 restart"

echo "==> Verifikasi"
${SSH} "${REMOTE}" "grep -c 'ujianDetail' ${APP}/Modules/Ujian/resources/views/rooms/index.blade.php; doas -u www grep -rl 'ujian-grade-float-bar\\|ujianDetail' ${APP}/storage/framework/views/*.php 2>/dev/null | wc -l"

echo "==> Selesai. Hard refresh https://sainteku.uinsaizu.ac.id/ujian/rooms"