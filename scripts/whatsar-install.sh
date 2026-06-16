#!/usr/bin/env bash
# Sainteku — install Whatsar binary + systemd (Linux VPS)
#
# Usage (from project root):
#   sudo bash scripts/whatsar-install.sh
#   sudo bash scripts/whatsar-install.sh --app-dir /var/www/sainteku --port 8080

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
# shellcheck source=_lib.sh
source "${SCRIPT_DIR}/_lib.sh"

REPO="arifianilhamnrr/whatsar"
PORT=""
BINARY_PATH="/usr/local/bin/whatsar"
SKIP_SYSTEMD=false

usage() {
    cat <<'HELP'
Usage: whatsar-install.sh [OPTIONS]

  --app-dir PATH   Sainteku root (default: parent of scripts/)
  --port PORT      Whatsar HTTP port (default: dari .env atau 8080)
  --no-systemd     Skip systemd unit install
  --help           Show this help
HELP
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --app-dir) APP_DIR="$2"; ENV_FILE="${APP_DIR}/.env"; shift 2 ;;
        --port) PORT="$2"; shift 2 ;;
        --no-systemd) SKIP_SYSTEMD=true; shift ;;
        --help|-h) usage; exit 0 ;;
        *) log_warn "Opsi tidak dikenal: $1"; shift ;;
    esac
done

if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
    die "Jalankan sebagai root: sudo bash scripts/whatsar-install.sh"
fi

ENV_FILE="${APP_DIR}/.env"
PORT="${PORT:-$(env_get WHATSAR_PORT)}"
PORT="${PORT:-8080}"

if [[ -z "$(env_get WHATSAR_ADMIN_PASSWORD)" ]]; then
    die "WHATSAR_ADMIN_PASSWORD belum diset. Jalankan bash install.sh (wizard) atau isi manual di .env"
fi

ARCH="$(uname -m)"
case "$ARCH" in
    x86_64|amd64) GOARCH="amd64" ;;
    aarch64|arm64) GOARCH="arm64" ;;
    armv7l|armv7) GOARCH="arm" ;;
    *) die "Arsitektur tidak didukung: $ARCH" ;;
esac

DATA_DIR="${APP_DIR}/storage/whatsar"
mkdir -p "$DATA_DIR"
chown -R www-data:www-data "$DATA_DIR" 2>/dev/null || true

log_info "Download Whatsar release (${GOARCH})..."
RELEASE_JSON="$(curl -fsSL "https://api.github.com/repos/${REPO}/releases/latest")"
TAG="$(echo "$RELEASE_JSON" | grep -oP '"tag_name":\s*"\K[^"]+' | head -1)"
VER="${TAG#v}"

case "$GOARCH" in
    amd64) ASSET_ARCH="amd64" ;;
    arm64) ASSET_ARCH="arm64" ;;
    arm)   ASSET_ARCH="armv7" ;;
esac

ASSET="whatsar_${VER}_linux_${ASSET_ARCH}.tar.gz"
URL="https://github.com/${REPO}/releases/download/${TAG}/${ASSET}"

WORKDIR="$(mktemp -d)"
curl -fsSL "$URL" -o "${WORKDIR}/pkg.tar.gz"
tar -xzf "${WORKDIR}/pkg.tar.gz" -C "$WORKDIR"
install -m 755 "${WORKDIR}/whatsar" "$BINARY_PATH"
rm -rf "$WORKDIR"
log_ok "Binary: $BINARY_PATH (${TAG})"

API_KEY="$(env_get WHATSAR_API_KEY)"
if [[ -z "$API_KEY" ]]; then
    API_KEY="$(generate_secret)"
    log_info "WHATSAR_API_KEY di-generate otomatis"
fi

upsert_env "WHATSAPP_DRIVER" "whatsar"
upsert_env "WHATSAPP_ENABLED" "true"
upsert_env "WHATSAR_URL" "http://127.0.0.1:${PORT}"
upsert_env "WHATSAR_API_KEY" "$API_KEY"
upsert_env "WHATSAR_DATA_DIR" "$DATA_DIR"
upsert_env "WHATSAR_HOST" "127.0.0.1"
upsert_env "WHATSAR_PORT" "$PORT"
upsert_env "WHATSAR_DB_PATH" "${DATA_DIR}/whatsar.db"

if [[ -z "$(env_get WHATSAR_MAX_SESSIONS)" ]]; then
    upsert_env "WHATSAR_MAX_SESSIONS" "5"
fi

log_ok ".env Whatsar siap (API key + admin password)"

if [[ "$SKIP_SYSTEMD" == "false" ]] && command -v systemctl >/dev/null 2>&1; then
    UNIT_DST="/etc/systemd/system/whatsar.service"
    sed \
        -e "s|/var/www/sainteku|${APP_DIR}|g" \
        "${APP_DIR}/deploy/whatsar.service" > "$UNIT_DST"
    systemctl daemon-reload
    systemctl enable whatsar
    systemctl restart whatsar
    log_ok "systemd: whatsar.service enabled"
    sleep 2
    if curl -fsS "http://127.0.0.1:${PORT}/health" >/dev/null 2>&1; then
        log_ok "Health check passed"
    else
        log_warn "Health check gagal — cek: journalctl -u whatsar -n 50"
    fi
else
    log_info "Skip systemd. Jalankan manual:"
    echo "  WHATSAR_HOST=127.0.0.1 WHATSAR_PORT=${PORT} WHATSAR_DB_PATH=${DATA_DIR}/whatsar.db whatsar"
fi

echo ""
echo "Selesai. Pairing QR: /settings/whatsapp di Sainteku"
echo "Admin Whatsar (opsional): http://127.0.0.1:${PORT}/admin"