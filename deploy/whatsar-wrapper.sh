#!/bin/sh
# Sainteku — launcher Whatsar (baca .env aplikasi, jalankan binary native)
#
# Dipakai oleh systemd (Linux) dan rc.d (FreeBSD).
# Usage: whatsar-wrapper.sh [/path/to/sainteku]

set -eu

APP_DIR="${1:-${WHATSAR_APP_DIR:-/usr/local/www/saintekku}}"
ENV_FILE="${WHATSAR_ENV_FILE:-${APP_DIR}/.env}"
BINARY="${WHATSAR_BINARY:-/usr/local/bin/whatsar}"

if [ ! -x "${BINARY}" ]; then
    echo "whatsar binary tidak ditemukan: ${BINARY}" >&2
    exit 1
fi

if [ ! -f "${ENV_FILE}" ]; then
    echo "file .env tidak ditemukan: ${ENV_FILE}" >&2
    exit 1
fi

export WHATSAR_ENV_FILE="${ENV_FILE}"
cd "${APP_DIR}" || exit 1
exec "${BINARY}"