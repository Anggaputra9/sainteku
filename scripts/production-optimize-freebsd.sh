#!/usr/bin/env bash
# Sainteku — optimasi production FreeBSD
#   - PHP OPcache (+ JIT)
#   - Queue worker (rc.d)
#   - Laravel scheduler (cron)
#   - Backup whatsar.db (cron)
#
# Usage:
#   sudo bash scripts/production-optimize-freebsd.sh
#   sudo bash scripts/production-optimize-freebsd.sh --app-dir /usr/local/www/saintekku

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
# shellcheck source=_lib.sh
source "${SCRIPT_DIR}/_lib.sh"

APP_DIR="${APP_DIR:-/usr/local/www/saintekku}"
SKIP_OPCACHE=false
SKIP_QUEUE=false
SKIP_BACKUP=false

usage() {
    cat <<'HELP'
Usage: production-optimize-freebsd.sh [OPTIONS]

  --app-dir PATH     Root aplikasi (default: /usr/local/www/saintekku)
  --skip-opcache     Lewati install/tuning OPcache
  --skip-queue       Lewati rc.d queue worker + scheduler cron
  --skip-backup      Lewati cron backup whatsar.db
  --help             Bantuan
HELP
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --app-dir) APP_DIR="$2"; shift 2 ;;
        --skip-opcache) SKIP_OPCACHE=true; shift ;;
        --skip-queue) SKIP_QUEUE=true; shift ;;
        --skip-backup) SKIP_BACKUP=true; shift ;;
        --help|-h) usage; exit 0 ;;
        *) log_warn "Opsi tidak dikenal: $1"; shift ;;
    esac
done

if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
    die "Jalankan sebagai root: doas/doas bash scripts/production-optimize-freebsd.sh"
fi

detect_platform
if ! is_freebsd; then
    die "Script ini khusus FreeBSD (terdeteksi: ${OS_FAMILY}/${OS_ID})"
fi

log_info "Optimasi production Sainteku di ${APP_DIR}"

install_opcache() {
    if [[ "$SKIP_OPCACHE" == "true" ]]; then
        return 0
    fi

    if command -v pkg >/dev/null 2>&1; then
        if ! pkg info -e php84-opcache >/dev/null 2>&1; then
            log_info "Install php84-opcache..."
            pkg install -y php84-opcache
        else
            log_ok "php84-opcache sudah terpasang"
        fi
    else
        log_warn "pkg tidak ditemukan — pastikan php84-opcache terinstall manual"
    fi

    install -d /usr/local/etc/php
    install -m 644 "${APP_DIR}/deploy/php84-opcache-production.ini" \
        /usr/local/etc/php/99-sainteku-opcache.ini
    log_ok "OPcache config: /usr/local/etc/php/99-sainteku-opcache.ini"

    if service php_fpm status >/dev/null 2>&1; then
        service php_fpm restart
        log_ok "php-fpm restarted"
    elif service php-fpm status >/dev/null 2>&1; then
        service php-fpm restart
        log_ok "php-fpm restarted"
    else
        log_warn "php-fpm service tidak ditemukan — restart manual jika perlu"
    fi
}

install_queue_service() {
    if [[ "$SKIP_QUEUE" == "true" ]]; then
        return 0
    fi

    install -d /usr/local/sbin /usr/local/etc/rc.d
    sed \
        -e "s|/usr/local/www/saintekku|${APP_DIR}|g" \
        "${APP_DIR}/deploy/queue-wrapper.sh" > /usr/local/sbin/queue-wrapper.sh
    chmod 755 /usr/local/sbin/queue-wrapper.sh

    cp "${APP_DIR}/deploy/queue-watchdog.sh" /usr/local/sbin/queue-watchdog.sh
    chmod 755 /usr/local/sbin/queue-watchdog.sh

    sed \
        -e "s|/usr/local/www/saintekku|${APP_DIR}|g" \
        -e "s|sainteku_queue_user=\"www\"|sainteku_queue_user=\"${WEB_USER}\"|g" \
        "${APP_DIR}/deploy/queue-freebsd" > /usr/local/etc/rc.d/sainteku_queue
    chmod 755 /usr/local/etc/rc.d/sainteku_queue

    local queue_workers="${SAINTEKKU_QUEUE_WORKERS:-4}"

    if command -v sysrc >/dev/null 2>&1; then
        sysrc sainteku_queue_enable="YES" >/dev/null 2>&1 || true
        sysrc sainteku_app_dir="${APP_DIR}" >/dev/null 2>&1 || true
        sysrc sainteku_queue_user="${WEB_USER}" >/dev/null 2>&1 || true
        sysrc sainteku_queue_workers="${queue_workers}" >/dev/null 2>&1 || true
    elif [[ -f /etc/rc.conf ]]; then
        grep -q '^sainteku_queue_enable=' /etc/rc.conf 2>/dev/null \
            || echo 'sainteku_queue_enable="YES"' >> /etc/rc.conf
        grep -q '^sainteku_app_dir=' /etc/rc.conf 2>/dev/null \
            || echo "sainteku_app_dir=\"${APP_DIR}\"" >> /etc/rc.conf
        grep -q '^sainteku_queue_user=' /etc/rc.conf 2>/dev/null \
            || echo "sainteku_queue_user=\"${WEB_USER}\"" >> /etc/rc.conf
        grep -q '^sainteku_queue_workers=' /etc/rc.conf 2>/dev/null \
            || echo "sainteku_queue_workers=\"${queue_workers}\"" >> /etc/rc.conf
    fi

    service sainteku_queue restart 2>/dev/null || service sainteku_queue start
    log_ok "Queue workers: service sainteku_queue (${queue_workers} worker)"

    local root_cron="/var/cron/tabs/root"
    local watchdog_line="*/5 * * * * /usr/local/sbin/queue-watchdog.sh >> /var/log/sainteku-queue-watchdog.log 2>&1"
    install -d "$(dirname "${root_cron}")"
    if [[ -f "${root_cron}" ]] && grep -Fq "queue-watchdog.sh" "${root_cron}"; then
        log_ok "Queue watchdog cron sudah ada (root)"
    else
        if [[ -f "${root_cron}" ]]; then
            printf '\n%s\n' "${watchdog_line}" >> "${root_cron}"
        else
            printf '%s\n' "${watchdog_line}" > "${root_cron}"
        fi
        chmod 600 "${root_cron}"
        log_ok "Queue watchdog cron ditambahkan (root, tiap 5 menit)"
    fi

    touch /var/log/sainteku-queue-watchdog.log

    local cron_file="/var/cron/tabs/${WEB_USER}"
    local schedule_line="* * * * * cd ${APP_DIR} && /usr/local/bin/php artisan schedule:run >> /var/log/sainteku-schedule.log 2>&1"
    install -d "$(dirname "${cron_file}")"
    if [[ -f "${cron_file}" ]] && grep -Fq "artisan schedule:run" "${cron_file}"; then
        log_ok "Scheduler cron sudah ada (${WEB_USER})"
    else
        if [[ -f "${cron_file}" ]]; then
            printf '\n%s\n' "${schedule_line}" >> "${cron_file}"
        else
            printf '%s\n' "${schedule_line}" > "${cron_file}"
        fi
        chown "${WEB_USER}:${WEB_USER}" "${cron_file}"
        chmod 600 "${cron_file}"
        log_ok "Scheduler cron ditambahkan untuk user ${WEB_USER}"
    fi

    touch /var/log/sainteku-schedule.log
    chown "${WEB_USER}:${WEB_USER}" /var/log/sainteku-schedule.log
}

install_whatsar_backup_cron() {
    if [[ "$SKIP_BACKUP" == "true" ]]; then
        return 0
    fi

    install -d /usr/local/sbin
    sed \
        -e "s|/usr/local/www/saintekku|${APP_DIR}|g" \
        "${APP_DIR}/deploy/whatsar-backup.sh" > /usr/local/sbin/whatsar-backup.sh
    chmod 755 /usr/local/sbin/whatsar-backup.sh

    mkdir -p "${APP_DIR}/storage/whatsar/backups"
    chown -R "${WEB_USER}:${WEB_USER}" "${APP_DIR}/storage/whatsar" 2>/dev/null || true

    local cron_file="/var/cron/tabs/root"
    local backup_line="0 2 * * * /usr/local/sbin/whatsar-backup.sh ${APP_DIR} >> /var/log/whatsar-backup.log 2>&1"
    install -d "$(dirname "${cron_file}")"
    if [[ -f "${cron_file}" ]] && grep -Fq "whatsar-backup.sh" "${cron_file}"; then
        log_ok "Backup cron whatsar sudah ada (root)"
    else
        if [[ -f "${cron_file}" ]]; then
            printf '\n%s\n' "${backup_line}" >> "${cron_file}"
        else
            printf '%s\n' "${backup_line}" > "${cron_file}"
        fi
        chmod 600 "${cron_file}"
        log_ok "Backup cron ditambahkan (root, harian 02:00)"
    fi

    touch /var/log/whatsar-backup.log

    /usr/local/sbin/whatsar-backup.sh "${APP_DIR}"
    log_ok "Backup whatsar.db test-run selesai"
}

reload_cron() {
    if service cron restart >/dev/null 2>&1; then
        log_ok "cron restarted"
    else
        log_warn "Gagal restart cron — cek: service cron restart"
    fi
}

install_opcache
install_queue_service
install_whatsar_backup_cron
reload_cron

echo ""
log_ok "Optimasi production FreeBSD selesai"
echo "  OPcache   : php -i | grep opcache.enable"
echo "  Queue     : service sainteku_queue status  (${SAINTEKKU_QUEUE_WORKERS:-4} worker)"
echo "  Scheduler : crontab -u ${WEB_USER} -l"
echo "  Backup WA : ls ${APP_DIR}/storage/whatsar/backups/"