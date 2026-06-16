#!/bin/sh
# Sainteku — backup whatsar.db (retensi default 14 hari)
#
# Usage:
#   whatsar-backup.sh [/path/to/sainteku]
# Cron contoh (harian 02:00):
#   0 2 * * * /usr/local/sbin/whatsar-backup.sh /usr/local/www/saintekku

set -eu

APP_DIR="${1:-${SAINTEKKU_APP_DIR:-/usr/local/www/saintekku}}"
RETENTION_DAYS="${WHATSAR_BACKUP_RETENTION_DAYS:-14}"

DB_PATH="${APP_DIR}/storage/whatsar/whatsar.db"
BACKUP_DIR="${APP_DIR}/storage/whatsar/backups"

if [ ! -f "${DB_PATH}" ]; then
    echo "whatsar.db tidak ditemukan: ${DB_PATH}" >&2
    exit 0
fi

mkdir -p "${BACKUP_DIR}"
STAMP="$(date +%Y%m%d-%H%M%S)"
DEST="${BACKUP_DIR}/whatsar-${STAMP}.db"

cp -a "${DB_PATH}" "${DEST}"
echo "backup: ${DEST}"

find "${BACKUP_DIR}" -type f -name 'whatsar-*.db' -mtime +"${RETENTION_DAYS}" -delete 2>/dev/null || true