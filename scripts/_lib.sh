#!/usr/bin/env bash
# Shared helpers for Sainteku install/update scripts.

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[1]:-${BASH_SOURCE[0]}}")/.." && pwd)"

OS_FAMILY=""
OS_ID=""
WEB_USER="www-data"
PKG_MGR=""

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

detect_platform() {
    local uname_s
    uname_s="$(uname -s)"

    case "$uname_s" in
        Linux) OS_FAMILY="linux" ;;
        Darwin) OS_FAMILY="darwin" ;;
        FreeBSD) OS_FAMILY="freebsd" ;;
        *) OS_FAMILY="unknown" ;;
    esac

    if [[ -f /etc/os-release ]]; then
        # shellcheck disable=SC1091
        . /etc/os-release
        OS_ID="${ID:-linux}"
    elif [[ "$OS_FAMILY" == "darwin" ]]; then
        OS_ID="macos"
    elif [[ "$OS_FAMILY" == "freebsd" ]]; then
        OS_ID="freebsd"
    else
        OS_ID="unknown"
    fi

    WEB_USER="www-data"
    case "$OS_ID" in
        ubuntu|debian) WEB_USER="www-data" ;;
        rhel|centos|fedora|rocky|almalinux|amzn|alpine) WEB_USER="nginx" ;;
        freebsd) WEB_USER="www" ;;
        macos) WEB_USER="_www" ;;
    esac

    PKG_MGR=""
    if [[ "$OS_FAMILY" == "linux" ]]; then
        if command -v apt-get >/dev/null 2>&1; then
            PKG_MGR="apt"
        elif command -v dnf >/dev/null 2>&1; then
            PKG_MGR="dnf"
        elif command -v yum >/dev/null 2>&1; then
            PKG_MGR="yum"
        elif command -v apk >/dev/null 2>&1; then
            PKG_MGR="apk"
        fi
    fi
}

is_linux() {
    [[ "${OS_FAMILY}" == "linux" ]]
}

is_freebsd() {
    [[ "${OS_FAMILY}" == "freebsd" ]]
}

sed_inplace() {
    local file="$1"
    local expression="$2"
    # BSD sed (macOS, FreeBSD) requires '' after -i; GNU sed (Linux) does not.
    if [[ "${OS_FAMILY}" == "darwin" || "${OS_FAMILY}" == "freebsd" ]]; then
        sed -i '' "$expression" "$file"
    else
        sed -i "$expression" "$file"
    fi
}

log_platform() {
    detect_platform
    local arch
    arch="$(uname -m)"
    log_info "OS: ${OS_ID} (${OS_FAMILY}, ${arch}) · web user: ${WEB_USER} · pkg: ${PKG_MGR:-manual}"
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
        sed_inplace "${ENV_FILE}" "s|^${key}=.*|${key}=${escaped}|"
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
    detect_platform
    local link="${APP_DIR}/public/storage"
    local target="${APP_DIR}/storage/app/public"
    local expected backup_dir stamp backup_file current

    expected="$(cd "${APP_DIR}" && pwd)/storage/app/public"
    backup_dir="${APP_DIR}/storage/backups"
    mkdir -p "${target}" "${backup_dir}"

    if [[ -L "${link}" ]]; then
        current="$(readlink "${link}")"
        if [[ "${current}" == "${expected}" && -d "${link}" ]]; then
            log_ok "storage:link valid (${current})"
            return 0
        fi
        log_warn "storage:link salah: ${current} (harus ${expected})"
        stamp="$(date +%Y%m%d-%H%M%S)"
        backup_file="${backup_dir}/storage-symlink.${stamp}.txt"
        {
            echo "expected=${expected}"
            echo "current=${current}"
            ls -la "${link}" 2>/dev/null || true
        } > "${backup_file}"
        rm -f "${link}"
    elif [[ -e "${link}" ]]; then
        die "public/storage ada tetapi bukan symlink — perbaiki manual"
    fi

    log_info "Membuat storage:link -> ${expected}"
    ln -s "${expected}" "${link}"

    if id -u "${WEB_USER}" >/dev/null 2>&1; then
        chown -h "${WEB_USER}:${WEB_USER}" "${link}" 2>/dev/null || true
    fi

    if [[ ! -L "${link}" ]] || [[ "$(readlink "${link}")" != "${expected}" ]] || [[ ! -d "${link}" ]]; then
        die "Gagal memverifikasi storage:link"
    fi

    log_ok "storage:link siap (${expected})"
}

fix_permissions() {
    if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
        return 0
    fi
    detect_platform
    log_info "Set permission storage & bootstrap/cache (${WEB_USER})..."
    if id -u "${WEB_USER}" >/dev/null 2>&1; then
        chown -R "${WEB_USER}:${WEB_USER}" "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache" 2>/dev/null || true
    else
        log_warn "User ${WEB_USER} tidak ada — skip chown"
    fi
    chmod -R 775 "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache" 2>/dev/null || true
    log_ok "Permission diperbarui"
}

flush_opcache() {
    detect_platform
    log_info "Flush OPcache (wajib setelah rsync blade/PHP di FreeBSD production)..."

    local artisan_php=(php)
    if id -u "${WEB_USER}" >/dev/null 2>&1; then
        if [[ "${EUID:-$(id -u)}" -eq 0 ]]; then
            artisan_php=(sudo -u "${WEB_USER}" php)
        elif command -v doas >/dev/null 2>&1; then
            artisan_php=(doas -u "${WEB_USER}" php)
        fi
    fi

    (cd "$APP_DIR" && "${artisan_php[@]}" -r "if (function_exists('opcache_reset')) { opcache_reset(); echo 'opcache_reset OK'; } else { echo 'opcache_reset tidak tersedia (CLI)'; }") || true

    if [[ "$OS_FAMILY" == "freebsd" ]] && [[ "${EUID:-$(id -u)}" -eq 0 || -x "$(command -v doas)" ]]; then
        local restart_cmd=()
        if command -v doas >/dev/null 2>&1 && [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
            restart_cmd=(doas)
        fi
        if service apache24 status >/dev/null 2>&1; then
            "${restart_cmd[@]}" service apache24 restart >/dev/null 2>&1 && log_ok "apache24 restarted (OPcache web flush)" || log_warn "Gagal restart apache24 — jalankan manual: doas service apache24 restart"
        elif service php_fpm status >/dev/null 2>&1; then
            "${restart_cmd[@]}" service php_fpm restart >/dev/null 2>&1 && log_ok "php_fpm restarted (OPcache web flush)" || log_warn "Gagal restart php_fpm"
        else
            log_warn "apache24/php_fpm tidak ditemukan — restart web server manual agar OPcache ikut refresh"
        fi
    fi
}

optimize_laravel() {
    detect_platform
    log_info "Laravel optimize (config/route/view cache)..."

    local artisan_php=(php)
    if id -u "${WEB_USER}" >/dev/null 2>&1; then
        if [[ "${EUID:-$(id -u)}" -eq 0 ]]; then
            artisan_php=(sudo -u "${WEB_USER}" php)
        elif command -v doas >/dev/null 2>&1; then
            artisan_php=(doas -u "${WEB_USER}" php)
        fi
    fi

    (cd "$APP_DIR" && "${artisan_php[@]}" artisan config:clear && "${artisan_php[@]}" artisan route:clear && "${artisan_php[@]}" artisan view:clear)
    (cd "$APP_DIR" && "${artisan_php[@]}" artisan config:cache && "${artisan_php[@]}" artisan route:cache && "${artisan_php[@]}" artisan view:cache)
    flush_opcache
    fix_permissions
    log_ok "Optimize selesai"
}

clear_laravel_caches() {
    log_info "Clear Laravel cache..."
    (cd "$APP_DIR" && php artisan optimize:clear)
    flush_opcache
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
    detect_platform
    if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
        if is_linux; then
            log_warn "Whatsar install butuh root. Jalankan: sudo bash scripts/whatsar-install.sh"
        elif is_freebsd; then
            log_warn "Whatsar install butuh root. Jalankan: sudo bash scripts/whatsar-install-freebsd.sh"
        else
            log_warn "Whatsar install butuh root di ${OS_FAMILY}/${OS_ID}"
        fi
        return 0
    fi
    if is_linux; then
        log_info "Install/update Whatsar (Linux binary)..."
        bash "${APP_DIR}/scripts/whatsar-install.sh" --app-dir "$APP_DIR"
    elif is_freebsd; then
        log_info "Install/update Whatsar (FreeBSD native build)..."
        bash "${APP_DIR}/scripts/whatsar-install-freebsd.sh" --app-dir "$APP_DIR"
    else
        log_warn "Whatsar tidak didukung di ${OS_FAMILY}/${OS_ID} — gunakan WHATSAR_URL remote atau WHATSAPP_DRIVER=log"
    fi
}

maybe_restart_whatsar() {
    local restart="$1"
    if [[ "$restart" != "true" ]]; then
        return 0
    fi
    restart_whatsar_service
}

restart_whatsar_service() {
    if command -v systemctl >/dev/null 2>&1; then
        systemctl restart whatsar 2>/dev/null && log_ok "whatsar.service restarted" || log_warn "Gagal restart whatsar.service"
    elif is_freebsd && command -v service >/dev/null 2>&1; then
        service whatsar restart 2>/dev/null && log_ok "whatsar restarted (rc.d)" || service whatsar start 2>/dev/null && log_ok "whatsar started (rc.d)" || log_warn "Gagal start/restart whatsar (rc.d)"
    fi
}

whatsar_health_ok() {
    local port url
    port="$(env_get WHATSAR_PORT)"
    port="${port:-8080}"
    url="http://127.0.0.1:${port}/health"
    if command -v curl >/dev/null 2>&1; then
        curl -fsS "$url" >/dev/null 2>&1
    elif command -v fetch >/dev/null 2>&1; then
        fetch -q -o /dev/null "$url" 2>/dev/null
    else
        return 1
    fi
}

ensure_whatsar_running() {
    if [[ "$(env_get WHATSAPP_ENABLED 2>/dev/null || true)" != "true" ]]; then
        return 0
    fi
    if [[ "$(env_get WHATSAPP_DRIVER 2>/dev/null || true)" != "whatsar" ]]; then
        return 0
    fi
    if whatsar_health_ok; then
        log_ok "Whatsar online"
        return 0
    fi
    log_warn "Whatsar offline — mencoba start ulang..."
    if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
        log_warn "Butuh root untuk start whatsar: doas service whatsar start"
        return 0
    fi
    restart_whatsar_service
    sleep 2
    if whatsar_health_ok; then
        log_ok "Whatsar online setelah restart"
    else
        log_warn "Whatsar masih offline — cek: service whatsar status"
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