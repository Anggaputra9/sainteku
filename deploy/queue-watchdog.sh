#!/bin/sh
# Sainteku — watchdog queue workers (auto-restart worker yang mati)
#
# Usage:
#   queue-watchdog.sh
# Cron contoh (tiap 5 menit, root):
#   */5 * * * * /usr/local/sbin/queue-watchdog.sh >> /var/log/sainteku-queue-watchdog.log 2>&1

set -eu

STAMP="$(date '+%Y-%m-%d %H:%M:%S')"
PIDFILE_BASE="/var/run/sainteku-queue"

log() {
    echo "[${STAMP}] $*"
}

# Baca konfigurasi rc.conf tanpa memanggil service (hindari loop)
sainteku_queue_enable="NO"
sainteku_queue_workers="4"

if [ -f /etc/rc.conf ]; then
    # shellcheck disable=SC1091
    . /etc/rc.conf
fi

: "${sainteku_queue_enable:=NO}"
: "${sainteku_queue_workers:=4}"

if [ "${sainteku_queue_enable}" != "YES" ]; then
    exit 0
fi

running=0
missing=0
id=1

while [ "${id}" -le "${sainteku_queue_workers}" ]; do
    pidfile="${PIDFILE_BASE}-${id}.pid"
    if [ -f "${pidfile}" ] && kill -0 "$(cat "${pidfile}")" 2>/dev/null; then
        running=$((running + 1))
    else
        missing=$((missing + 1))
    fi
    id=$((id + 1))
done

if [ "${missing}" -eq 0 ]; then
    exit 0
fi

log "WARN queue workers ${running}/${sainteku_queue_workers} aktif — restart yang mati (${missing})"
service sainteku_queue start >/dev/null 2>&1 || true

exit 0