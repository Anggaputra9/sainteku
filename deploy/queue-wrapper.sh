#!/bin/sh
# Sainteku — Laravel queue worker (database driver)
# Usage: queue-wrapper.sh [/path/to/sainteku]

set -eu

APP_DIR="${1:-${SAINTEKKU_APP_DIR:-/usr/local/www/saintekku}}"
PHP_BIN="${PHP_BIN:-/usr/local/bin/php}"

cd "${APP_DIR}" || exit 1

exec "${PHP_BIN}" artisan queue:work database \
    --sleep=3 \
    --tries=3 \
    --timeout=120 \
    --max-time=3600 \
    --no-interaction