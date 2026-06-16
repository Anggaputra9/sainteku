#!/usr/bin/env bash
# Shared helpers for Sainteku install/update scripts.

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[1]:-${BASH_SOURCE[0]}}")/.." && pwd)"

log_info()  { echo "[INFO] $*"; }
log_ok()    { echo "[OK]   $*"; }
log_warn()  { echo "[WARN] $*" >&2; }
log_error() { echo "[ERROR] $*" >&2; }

die() {
    log_error "$1"
    exit "${2:-1}"
}

usage_header() {
    cat <<'HELP'
Sainteku deploy helper

HELP
}

require_cmds() {
    local missing=()
    for cmd in "$@"; do
        if ! command -v "$cmd" >/dev/null 2>&1; then
            missing+=("$cmd")
        fi
    done
    if ((${#missing[@]} > 0)); then
        die "Perintah tidak ditemukan: ${missing[*]}"
    fi
}

require_php() {
    require_cmds php
    local ver
    ver="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
    local major minor
    major="${ver%%.*}"
    minor="${ver#*.}"
    if (( major < 8 || (major == 8 && minor < 2) )); then
        die "PHP 8.2+ diperlukan (terdeteksi: ${ver})"
    fi
}

ensure_env_file() {
    if [[ ! -f "${APP_DIR}/.env" ]]; then
        if [[ -f "${APP_DIR}/.env.example" ]]; then
            cp "${APP_DIR}/.env.example" "${APP_DIR}/.env"
            log_ok ".env dibuat dari .env.example"
        else
            die "File .env tidak ada dan .env.example tidak ditemukan"
        fi
    fi
}

ensure_app_key() {
    if ! grep -q '^APP_KEY=base64:' "${APP_DIR}/.env" 2>/dev/null; then
        log_info "Generate APP_KEY..."
        (cd "$APP_DIR" && php artisan key:generate --force)
        log_ok "APP_KEY siap"
    fi
}

composer_install() {
    local dev_mode="$1"
    log_info "Composer install..."
    if [[ "$dev_mode" == "true" ]]; then
        (cd "$APP_DIR" && composer install --no-interaction --prefer-dist)
    else
        (cd "$APP_DIR" && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist)
    fi
    log_ok "Composer selesai"
}

npm_install_and_build() {
    local skip_build="$1"
    require_cmds npm
    log_info "NPM install..."
    if [[ -f "${APP_DIR}/package-lock.json" ]]; then
        (cd "$APP_DIR" && npm ci)
    else
        (cd "$APP_DIR" && npm install)
    fi
    log_ok "NPM install selesai"

    if [[ "$skip_build" != "true" ]]; then
        log_info "NPM build..."
        (cd "$APP_DIR" && npm run build)
        log_ok "Assets production siap"
    fi
}

run_migrate() {
    local with_seed="$1"
    log_info "Database migrate..."
    if [[ "$with_seed" == "true" ]]; then
        (cd "$APP_DIR" && php artisan migrate --force --seed)
    else
        (cd "$APP_DIR" && php artisan migrate --force)
    fi
    log_ok "Migrate selesai"
}

ensure_storage_link() {
    if [[ ! -L "${APP_DIR}/public/storage" ]] && [[ ! -d "${APP_DIR}/public/storage" ]]; then
        log_info "Storage link..."
        (cd "$APP_DIR" && php artisan storage:link)
        log_ok "storage:link selesai"
    fi
}

fix_permissions() {
    if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
        return 0
    fi
    log_info "Set permission storage & bootstrap/cache..."
    chown -R www-data:www-data "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache" 2>/dev/null || true
    chmod -R 775 "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache" 2>/dev/null || true
    log_ok "Permission diperbarui (www-data)"
}

optimize_laravel() {
    log_info "Laravel optimize (config/route/view cache)..."
    (cd "$APP_DIR" && php artisan config:clear && php artisan route:clear && php artisan view:clear)
    (cd "$APP_DIR" && php artisan config:cache && php artisan route:cache && php artisan view:cache)
    log_ok "Optimize selesai"
}

clear_laravel_caches() {
    log_info "Clear Laravel cache..."
    (cd "$APP_DIR" && php artisan optimize:clear)
    log_ok "Cache dibersihkan"
}

git_pull_latest() {
    local branch="$1"
    if [[ ! -d "${APP_DIR}/.git" ]]; then
        die "Bukan git repo — gunakan --no-git atau clone dulu"
    fi
    require_cmds git
    log_info "Git pull (${branch})..."
    (cd "$APP_DIR" && git fetch origin && git pull --ff-only origin "$branch")
    log_ok "Git pull selesai"
}

maybe_install_whatsar() {
    local with_whatsar="$1"
    if [[ "$with_whatsar" != "true" ]]; then
        return 0
    fi
    if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
        log_warn "Whatsar install butuh root. Jalankan: sudo bash scripts/whatsar-install.sh"
        return 0
    fi
    log_info "Install/update Whatsar..."
    bash "${APP_DIR}/scripts/whatsar-install.sh" --app-dir "$APP_DIR"
}

maybe_restart_whatsar() {
    local restart="$1"
    if [[ "$restart" != "true" ]]; then
        return 0
    fi
    if command -v systemctl >/dev/null 2>&1; then
        systemctl restart whatsar 2>/dev/null && log_ok "whatsar.service restarted" || log_warn "Gagal restart whatsar.service"
    fi
}

print_done() {
    local mode="$1"
    echo ""
    log_ok "Selesai — Sainteku ${mode}"
    echo "  App dir : ${APP_DIR}"
    echo "  Health  : php artisan about"
    echo "  WhatsApp: /settings/whatsapp (setelah pairing QR)"
}