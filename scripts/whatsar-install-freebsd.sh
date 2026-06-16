#!/usr/bin/env bash
# Sainteku — install Whatsar native FreeBSD (build from source + rc.d)
#
# Tidak pakai Linuxulator — binary Go murni, ringan di production.
#
# Usage (from project root):
#   sudo bash scripts/whatsar-install-freebsd.sh
#   sudo bash scripts/whatsar-install-freebsd.sh --app-dir /usr/local/www/saintekku

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
# shellcheck source=_lib.sh
source "${SCRIPT_DIR}/_lib.sh"

REPO="arifianilhamnrr/whatsar"
TAG="v0.1.0"
GO_MIN_MAJOR=1
GO_MIN_MINOR=25
GO_BOOTSTRAP_VER="1.25.4"

BINARY_PATH="/usr/local/bin/whatsar"
WRAPPER_DST="/usr/local/sbin/whatsar-wrapper.sh"
RC_DST="/usr/local/etc/rc.d/whatsar"
SKIP_RCD=false
PORT=""

usage() {
    cat <<'HELP'
Usage: whatsar-install-freebsd.sh [OPTIONS]

  --app-dir PATH   Sainteku root (default: parent of scripts/)
  --port PORT      Whatsar HTTP port (default: dari .env atau 8080)
  --no-rcd         Skip rc.d install
  --help           Show this help
HELP
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --app-dir) APP_DIR="$2"; ENV_FILE="${APP_DIR}/.env"; shift 2 ;;
        --port) PORT="$2"; shift 2 ;;
        --no-rcd) SKIP_RCD=true; shift ;;
        --help|-h) usage; exit 0 ;;
        *) log_warn "Opsi tidak dikenal: $1"; shift ;;
    esac
done

if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
    die "Jalankan sebagai root: sudo bash scripts/whatsar-install-freebsd.sh"
fi

detect_platform
if ! is_freebsd; then
    die "Script ini khusus FreeBSD (terdeteksi: ${OS_FAMILY}/${OS_ID})"
fi
log_info "Platform FreeBSD · arch $(uname -m) · web user ${WEB_USER}"

ENV_FILE="${APP_DIR}/.env"
PORT="${PORT:-$(env_get WHATSAR_PORT)}"
PORT="${PORT:-8080}"

if [[ -z "$(env_get WHATSAR_ADMIN_PASSWORD)" ]]; then
    die "WHATSAR_ADMIN_PASSWORD belum diset. Jalankan bash install.sh (wizard) atau isi manual di .env"
fi

DATA_DIR="${APP_DIR}/storage/whatsar"
mkdir -p "$DATA_DIR"
if id -u "${WEB_USER}" >/dev/null 2>&1; then
    chown -R "${WEB_USER}:${WEB_USER}" "$DATA_DIR" 2>/dev/null || true
fi

go_version_ok() {
    local ver major minor
    if ! command -v go >/dev/null 2>&1; then
        return 1
    fi
    ver="$(go env GOVERSION 2>/dev/null | sed 's/^go//')"
    major="${ver%%.*}"
    minor="${ver#*.}"
    minor="${minor%%.*}"
    if (( major > GO_MIN_MAJOR || (major == GO_MIN_MAJOR && minor >= GO_MIN_MINOR) )); then
        return 0
    fi
    return 1
}

ensure_go() {
    if go_version_ok; then
        log_ok "Go $(go env GOVERSION) siap"
        return 0
    fi

    log_info "Go ${GO_MIN_MAJOR}.${GO_MIN_MINOR}+ diperlukan — bootstrap Go ${GO_BOOTSTRAP_VER}..."

    local arch goarch tarball
    arch="$(uname -m)"
    case "$arch" in
        amd64|x86_64) goarch="amd64" ;;
        arm64|aarch64) goarch="arm64" ;;
        *) die "Arsitektur FreeBSD tidak didukung: $arch" ;;
    esac

    tarball="go${GO_BOOTSTRAP_VER}.freebsd-${goarch}.tar.gz"
    local workdir
    workdir="$(mktemp -d)"
    curl -fsSL "https://go.dev/dl/${tarball}" -o "${workdir}/${tarball}"
    rm -rf /usr/local/go
    tar -C /usr/local -xzf "${workdir}/${tarball}"
    rm -rf "$workdir"
    export PATH="/usr/local/go/bin:${PATH}"

    if ! go_version_ok; then
        die "Go bootstrap gagal — butuh Go >= ${GO_MIN_MAJOR}.${GO_MIN_MINOR}"
    fi
    log_ok "Go $(go env GOVERSION) terpasang di /usr/local/go"
}

ensure_build_deps() {
    local missing=()
    for cmd in curl tar; do
        if ! command -v "$cmd" >/dev/null 2>&1; then
            missing+=("$cmd")
        fi
    done
    if ((${#missing[@]} > 0)); then
        if command -v pkg >/dev/null 2>&1; then
            log_info "Install dependensi build: ${missing[*]}"
            pkg install -y curl tar
        else
            die "Perintah tidak ditemukan: ${missing[*]}"
        fi
    fi
}

build_whatsar() {
    local workdir srcdir
    workdir="$(mktemp -d)"
    srcdir="${workdir}/whatsar-src"

    log_info "Download source Whatsar ${TAG}..."
    curl -fsSL "https://github.com/${REPO}/archive/refs/tags/${TAG}.tar.gz" \
        -o "${workdir}/src.tar.gz"
    mkdir -p "$srcdir"
    tar -xzf "${workdir}/src.tar.gz" -C "$srcdir" --strip-components=1

    log_info "Build native FreeBSD (CGO_ENABLED=0, -ldflags -s -w)..."
    (
        cd "$srcdir"
        export CGO_ENABLED=0
        export GOOS=freebsd
        go build -trimpath -ldflags="-s -w" -o whatsar ./cmd/server
    )

    install -m 755 "${srcdir}/whatsar" "$BINARY_PATH"
    rm -rf "$workdir"
    log_ok "Binary native: $BINARY_PATH (${TAG})"
}

upsert_whatsar_env() {
    local api_key
    api_key="$(env_get WHATSAR_API_KEY)"
    if [[ -z "$api_key" ]]; then
        api_key="$(generate_secret)"
        log_info "WHATSAR_API_KEY di-generate otomatis"
    fi

    upsert_env "WHATSAPP_DRIVER" "whatsar"
    upsert_env "WHATSAPP_ENABLED" "true"
    upsert_env "WHATSAR_URL" "http://127.0.0.1:${PORT}"
    upsert_env "WHATSAR_API_KEY" "$api_key"
    upsert_env "WHATSAR_DATA_DIR" "$DATA_DIR"
    upsert_env "WHATSAR_HOST" "127.0.0.1"
    upsert_env "WHATSAR_PORT" "$PORT"
    upsert_env "WHATSAR_DB_PATH" "${DATA_DIR}/whatsar.db"

    if [[ -z "$(env_get WHATSAR_MAX_SESSIONS)" ]]; then
        upsert_env "WHATSAR_MAX_SESSIONS" "5"
    fi

    log_ok ".env Whatsar siap (API key + admin password)"
}

install_wrapper() {
    install -d /usr/local/sbin
    sed \
        -e "s|/usr/local/www/saintekku|${APP_DIR}|g" \
        "${APP_DIR}/deploy/whatsar-wrapper.sh" > "$WRAPPER_DST"
    chmod 755 "$WRAPPER_DST"
    log_ok "Wrapper: $WRAPPER_DST"
}

install_rcd() {
    install -d /usr/local/etc/rc.d
    sed \
        -e "s|/usr/local/www/saintekku|${APP_DIR}|g" \
        -e "s|whatsar_user=\"www\"|whatsar_user=\"${WEB_USER}\"|g" \
        "${APP_DIR}/deploy/whatsar-freebsd" > "$RC_DST"
    chmod 755 "$RC_DST"
    log_ok "rc.d: $RC_DST"

    if command -v sysrc >/dev/null 2>&1; then
        sysrc whatsar_enable="YES" >/dev/null 2>&1 || true
        sysrc whatsar_app_dir="${APP_DIR}" >/dev/null 2>&1 || true
        sysrc whatsar_user="${WEB_USER}" >/dev/null 2>&1 || true
    elif [[ -f /etc/rc.conf ]]; then
        grep -q '^whatsar_enable=' /etc/rc.conf 2>/dev/null \
            || echo 'whatsar_enable="YES"' >> /etc/rc.conf
        if grep -q '^whatsar_app_dir=' /etc/rc.conf 2>/dev/null; then
            sed_inplace /etc/rc.conf "s|^whatsar_app_dir=.*|whatsar_app_dir=\"${APP_DIR}\"|"
        else
            echo "whatsar_app_dir=\"${APP_DIR}\"" >> /etc/rc.conf
        fi
        if grep -q '^whatsar_user=' /etc/rc.conf 2>/dev/null; then
            sed_inplace /etc/rc.conf "s|^whatsar_user=.*|whatsar_user=\"${WEB_USER}\"|"
        else
            echo "whatsar_user=\"${WEB_USER}\"" >> /etc/rc.conf
        fi
    fi
    log_ok "rc.conf: whatsar_enable=YES"
}

health_check() {
    local url="http://127.0.0.1:${PORT}/health"
    sleep 2
    if command -v curl >/dev/null 2>&1; then
        curl -fsS "$url" >/dev/null 2>&1 && return 0
    elif command -v fetch >/dev/null 2>&1; then
        fetch -q -o /dev/null "$url" 2>/dev/null && return 0
    fi
    return 1
}

ensure_build_deps
ensure_go
build_whatsar
upsert_whatsar_env
install_wrapper

if [[ "$SKIP_RCD" == "false" ]]; then
    install_rcd
    service whatsar restart 2>/dev/null || service whatsar start
    log_ok "Service whatsar started (rc.d)"
    if health_check; then
        log_ok "Health check passed"
    else
        log_warn "Health check gagal — cek: service whatsar status; tail /var/log/messages"
    fi
else
    log_info "Skip rc.d. Jalankan manual:"
    echo "  WHATSAR_ENV_FILE=${APP_DIR}/.env ${WRAPPER_DST} ${APP_DIR}"
fi

echo ""
echo "Selesai. Pairing QR: /settings/whatsapp di Sainteku"
echo "Admin Whatsar (opsional): http://127.0.0.1:${PORT}/admin"