#!/bin/sh
# Wajib dijalankan setelah rsync blade/PHP ke FreeBSD production.
# Tanpa ini, OPcache (validate_timestamps=0) masih menyajikan HTML/JS lama.
#
# Usage (di server FreeBSD):
#   sh deploy/post-rsync-freebsd.sh
#   sh deploy/post-rsync-freebsd.sh /usr/local/www/saintekku

set -eu

APP_DIR="${1:-/usr/local/www/saintekku}"
WEB_USER="${WEB_USER:-www}"

run_as_web() {
    if command -v doas >/dev/null 2>&1; then
        doas -u "${WEB_USER}" "$@"
    elif [ "$(id -u)" -eq 0 ]; then
        su -m "${WEB_USER}" -c "$*"
    else
        echo "ERROR: butuh doas/root untuk menjalankan artisan sebagai ${WEB_USER}" >&2
        exit 1
    fi
}

restart_web() {
    if command -v doas >/dev/null 2>&1; then
        doas service apache24 restart 2>/dev/null || doas service php_fpm restart 2>/dev/null || true
    elif [ "$(id -u)" -eq 0 ]; then
        service apache24 restart 2>/dev/null || service php_fpm restart 2>/dev/null || true
    fi
}

echo "==> cek storage:link"
if [ -f "${APP_DIR}/deploy/ensure-storage-link.sh" ]; then
    sh "${APP_DIR}/deploy/ensure-storage-link.sh" "${APP_DIR}"
else
    echo "WARN: deploy/ensure-storage-link.sh tidak ditemukan — lewati cek storage:link" >&2
fi

echo "==> view:clear + view:cache di ${APP_DIR}"
run_as_web php "${APP_DIR}/artisan" view:clear
run_as_web php "${APP_DIR}/artisan" view:cache

echo "==> restart web server (flush OPcache)"
restart_web

echo "==> verifikasi tombol Koreksi Ulang di compiled views"
count=$(run_as_web sh -c "grep -r 'Koreksi Ulang' '${APP_DIR}/storage/framework/views/' 2>/dev/null | wc -l | tr -d ' '")
echo "    Koreksi Ulang di compiled views: ${count} baris"
if [ "${count}" -lt 1 ]; then
    echo "WARN: compiled views belum memuat tombol — cek permission storage/framework/views" >&2
    exit 1
fi

echo "==> Selesai. Hard refresh browser (Ctrl+Shift+R) lalu buka /ujian/rooms"