#!/bin/sh
# Sainteku — watchdog Whatsar (health check + auto-restart)
#
# Usage:
#   whatsar-watchdog.sh [/path/to/sainteku]
# Cron contoh (tiap 5 menit):
#   */5 * * * * /usr/local/sbin/whatsar-watchdog.sh /usr/local/www/saintekku >> /var/log/whatsar-watchdog.log 2>&1

set -eu

APP_DIR="${1:-${SAINTEKKU_APP_DIR:-/usr/local/www/saintekku}}"
ENV_FILE="${APP_DIR}/.env"
PORT="8080"
STAMP="$(date '+%Y-%m-%d %H:%M:%S')"

if [ -f "${ENV_FILE}" ]; then
    _port="$(grep '^WHATSAR_PORT=' "${ENV_FILE}" 2>/dev/null | cut -d= -f2 | tr -d '"' || true)"
    [ -n "${_port}" ] && PORT="${_port}"
fi

HEALTH_URL="http://127.0.0.1:${PORT}/health"
RESOLV_CONF="/etc/resolv.conf"

log() {
    echo "[${STAMP}] $*"
}

health_ok() {
    if command -v curl >/dev/null 2>&1; then
        curl -fsS -m 5 "${HEALTH_URL}" >/dev/null 2>&1
        return $?
    fi
    if command -v fetch >/dev/null 2>&1; then
        fetch -q -o /dev/null -T 5 "${HEALTH_URL}" 2>/dev/null
        return $?
    fi
    return 1
}

dns_ok() {
    host -t A web.whatsapp.com >/dev/null 2>&1
}

fix_dns_if_needed() {
    if dns_ok; then
        return 0
    fi

    log "WARN DNS gagal resolve web.whatsapp.com — perbaiki resolv.conf"

    if command -v tailscale >/dev/null 2>&1; then
        tailscale set --accept-dns=false >/dev/null 2>&1 || true
    fi

    cat > "${RESOLV_CONF}" <<EOF
# Fixed DNS — whatsar-watchdog ${STAMP}
nameserver 8.8.8.8
nameserver 1.1.1.1
nameserver 8.8.4.4
EOF

    if dns_ok; then
        log "OK DNS diperbaiki"
        return 0
    fi

    log "ERROR DNS masih gagal setelah perbaikan"
    return 1
}

restart_whatsar() {
    if service whatsar onestatus >/dev/null 2>&1; then
        service whatsar restart >/dev/null 2>&1 || service whatsar start >/dev/null 2>&1
    else
        service whatsar start >/dev/null 2>&1
    fi
}

fix_dns_if_needed || true

if health_ok; then
    exit 0
fi

log "WARN health gagal (${HEALTH_URL}) — restart whatsar"
restart_whatsar
sleep 3

if health_ok; then
    log "OK restart berhasil"
    exit 0
fi

log "ERROR restart gagal — cek: service whatsar onestatus; tail /var/log/whatsar-watchdog.log"
exit 1