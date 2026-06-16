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

ENV_FILE="${APP_DIR}/.env"

ensure_env_file() {
    if [[ ! -f "${ENV_FILE}" ]]; then
        if [[ -f "${APP_DIR}/.env.example" ]]; then
            cp "${APP_DIR}/.env.example" "${ENV_FILE}"
            log_ok ".env dibuat dari .env.example"
        else
            die "File .env tidak ada dan .env.example tidak ditemukan"
        fi
    fi
}

env_get() {
    local key="$1"
    [[ -f "${ENV_FILE}" ]] || return 0
    if grep -q "^${key}=" "${ENV_FILE}" 2>/dev/null; then
        grep -m1 "^${key}=" "${ENV_FILE}" | cut -d= -f2-
    fi
}

escape_sed_replacement() {
    printf '%s' "$1" | sed -e 's/[\/&|]/\\&/g'
}

upsert_env() {
    local key="$1"
    local value="$2"
    local escaped
    escaped="$(escape_sed_replacement "$value")"

    if [[ ! -f "${ENV_FILE}" ]]; then
        touch "${ENV_FILE}"
    fi
    if grep -q "^${key}=" "${ENV_FILE}" 2>/dev/null; then
        sed -i "s|^${key}=.*|${key}=${escaped}|" "${ENV_FILE}"
    else
        printf '%s=%s\n' "$key" "$value" >> "${ENV_FILE}"
    fi
}

generate_secret() {
    if command -v openssl >/dev/null 2>&1; then
        openssl rand -hex 32
    else
        tr -dc 'a-zA-Z0-9' </dev/urandom | head -c 48
    fi
}

is_interactive_shell() {
    [[ -t 0 ]] && [[ -t 1 ]]
}

prompt_line() {
    local label="$1"
    local default="$2"
    local input=""
    read -rp "${label} [${default}]: " input
    if [[ -z "$input" ]]; then
        echo "$default"
    else
        echo "$input"
    fi
}

prompt_secret() {
    local label="$1"
    local env_key="$2"
    local required="${3:-false}"
    local current=""
    local input=""

    while true; do
        current="$(env_get "$env_key")"
        input=""
        if [[ -n "$current" ]]; then
            read -rsp "${label} (Enter = tetap, isi baru untuk ganti): " input
        else
            read -rsp "${label}: " input
        fi
        echo ""

        if [[ -z "$input" && -n "$current" ]]; then
            echo "$current"
            return 0
        fi
        if [[ -z "$input" && "$required" != "true" ]]; then
            echo ""
            return 0
        fi
        if [[ -z "$input" && "$required" == "true" ]]; then
            log_warn "${label} wajib diisi."
            continue
        fi
        echo "$input"
        return 0
    done
}

run_interactive_env_setup() {
    local with_whatsar="$1"
    local dev_mode="${2:-false}"

    if ! is_interactive_shell; then
        log_info "Mode non-interaktif (bukan TTY) — pakai nilai .env yang ada"
        return 0
    fi

    echo ""
    echo "══════════════════════════════════════════════"
    echo "  Setup .env Sainteku (Enter = pakai default)"
    echo "══════════════════════════════════════════════"
    echo ""

    local app_url app_env app_debug
    app_url="$(prompt_line "APP_URL" "$(env_get APP_URL || echo http://localhost)")"
    if [[ "$dev_mode" == "true" ]]; then
        app_env="local"
        app_debug="true"
    else
        app_env="$(prompt_line "APP_ENV" "$(env_get APP_ENV || echo production)")"
        app_debug="$(prompt_line "APP_DEBUG (true/false)" "$(env_get APP_DEBUG || echo false)")"
    fi

    echo ""
    echo "--- Database ---"
    local db_host db_port db_name db_user db_pass
    db_host="$(prompt_line "DB_HOST" "$(env_get DB_HOST || echo 127.0.0.1)")"
    db_port="$(prompt_line "DB_PORT" "$(env_get DB_PORT || echo 3306)")"
    db_name="$(prompt_line "DB_DATABASE" "$(env_get DB_DATABASE || echo sainteku)")"
    db_user="$(prompt_line "DB_USERNAME" "$(env_get DB_USERNAME || echo root)")"
    db_pass="$(prompt_secret "DB_PASSWORD" "DB_PASSWORD" "true")"

    upsert_env "APP_URL" "$app_url"
    upsert_env "APP_ENV" "$app_env"
    upsert_env "APP_DEBUG" "$app_debug"
    upsert_env "DB_CONNECTION" "mysql"
    upsert_env "DB_HOST" "$db_host"
    upsert_env "DB_PORT" "$db_port"
    upsert_env "DB_DATABASE" "$db_name"
    upsert_env "DB_USERNAME" "$db_user"
    upsert_env "DB_PASSWORD" "$db_pass"

    if [[ "$with_whatsar" == "true" ]]; then
        echo ""
        echo "--- WhatsApp / Whatsar ---"
        local wa_port wa_admin wa_api data_dir
        wa_port="$(prompt_line "WHATSAR_PORT" "$(env_get WHATSAR_PORT || echo 8080)")"
        wa_admin="$(prompt_secret "WHATSAR_ADMIN_PASSWORD (login /admin Whatsar)" "WHATSAR_ADMIN_PASSWORD" "true")"
        wa_api="$(env_get WHATSAR_API_KEY)"
        if [[ -z "$wa_api" ]]; then
            read -rp "WHATSAR_API_KEY (Enter = auto-generate): " wa_api
            if [[ -z "$wa_api" ]]; then
                wa_api="$(generate_secret)"
                log_info "API key Whatsar di-generate otomatis"
            fi
        else
            read -rp "WHATSAR_API_KEY (Enter = tetap pakai yang ada): " input_api
            if [[ -n "${input_api:-}" ]]; then
                wa_api="$input_api"
            fi
        fi

        data_dir="${APP_DIR}/storage/whatsar"
        upsert_env "WHATSAPP_DRIVER" "whatsar"
        upsert_env "WHATSAPP_ENABLED" "true"
        upsert_env "WHATSAR_HOST" "127.0.0.1"
        upsert_env "WHATSAR_PORT" "$wa_port"
        upsert_env "WHATSAR_URL" "http://127.0.0.1:${wa_port}"
        upsert_env "WHATSAR_API_KEY" "$wa_api"
        upsert_env "WHATSAR_ADMIN_PASSWORD" "$wa_admin"
        upsert_env "WHATSAR_DATA_DIR" "$data_dir"
        upsert_env "WHATSAR_DB_PATH" "${data_dir}/whatsar.db"
        upsert_env "WHATSAR_MAX_SESSIONS" "$(env_get WHATSAR_MAX_SESSIONS || echo 5)"
    fi

    echo ""
    log_ok "Konfigurasi .env disimpan"
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
    if [[ -n "$(env_get WHATSAR_PORT 2>/dev/null || true)" ]]; then
        echo "  Whatsar : http://127.0.0.1:$(env_get WHATSAR_PORT)/admin (password admin dari .env)"
    fi
}