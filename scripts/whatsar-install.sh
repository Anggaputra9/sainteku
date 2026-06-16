#!/usr/bin/env bash
# Sainteku — install Whatsar binary + systemd (Linux VPS)
#
# Usage (from project root):
#   sudo bash scripts/whatsar-install.sh
#   sudo bash scripts/whatsar-install.sh --app-dir /var/www/sainteku --port 8080

set -euo pipefail

REPO="arifianilhamnrr/whatsar"
APP_DIR="$(cd "$(dirname "$0")/.." && pwd)"
PORT="8080"
BINARY_PATH="/usr/local/bin/whatsar"
SKIP_SYSTEMD=false

usage() {
    cat <<'HELP'
Usage: whatsar-install.sh [OPTIONS]

  --app-dir PATH   Sainteku root (default: parent of scripts/)
  --port PORT      Whatsar HTTP port (default: 8080)
  --no-systemd     Skip systemd unit install
  --help           Show this help
HELP
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --app-dir) APP_DIR="$2"; shift 2 ;;
        --port) PORT="$2"; shift 2 ;;
        --no-systemd) SKIP_SYSTEMD=true; shift ;;
        --help|-h) usage; exit 0 ;;
        *) echo "[WARN] Unknown option: $1" >&2; shift ;;
    esac
done

if [[ "${EUID:-$(id -u)}" -ne 0 ]]; then
    echo "[ERROR] Jalankan sebagai root: sudo bash scripts/whatsar-install.sh" >&2
    exit 1
fi

ARCH="$(uname -m)"
case "$ARCH" in
    x86_64|amd64) GOARCH="amd64" ;;
    aarch64|arm64) GOARCH="arm64" ;;
    armv7l|armv7) GOARCH="arm" ;;
    *) echo "[ERROR] Arsitektur tidak didukung: $ARCH" >&2; exit 1 ;;
esac

DATA_DIR="${APP_DIR}/storage/whatsar"
ENV_FILE="${APP_DIR}/.env"
mkdir -p "$DATA_DIR"
chown -R www-data:www-data "$DATA_DIR" 2>/dev/null || true

echo "[INFO] Download Whatsar release (${GOARCH})..."
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
echo "[OK] Binary: $BINARY_PATH (${TAG})"

generate_key() {
    if command -v openssl >/dev/null 2>&1; then
        openssl rand -hex 32
    else
        tr -dc 'a-zA-Z0-9' </dev/urandom | head -c 48
    fi
}

upsert_env() {
    local key="$1"
    local value="$2"
    if [[ ! -f "$ENV_FILE" ]]; then
        touch "$ENV_FILE"
    fi
    if grep -q "^${key}=" "$ENV_FILE" 2>/dev/null; then
        sed -i "s|^${key}=.*|${key}=${value}|" "$ENV_FILE"
    else
        echo "${key}=${value}" >> "$ENV_FILE"
    fi
}

API_KEY="$(generate_key)"
upsert_env "WHATSAPP_DRIVER" "whatsar"
upsert_env "WHATSAPP_ENABLED" "true"
upsert_env "WHATSAR_URL" "http://127.0.0.1:${PORT}"
upsert_env "WHATSAR_API_KEY" "$API_KEY"
upsert_env "WHATSAR_DATA_DIR" "$DATA_DIR"

# Whatsar process reads its own env keys
upsert_env "WHATSAR_HOST" "127.0.0.1"
upsert_env "WHATSAR_PORT" "$PORT"
upsert_env "WHATSAR_DB_PATH" "${DATA_DIR}/whatsar.db"

echo "[OK] .env updated: WHATSAR_URL, WHATSAR_API_KEY"

if [[ "$SKIP_SYSTEMD" == "false" ]] && command -v systemctl >/dev/null 2>&1; then
    UNIT_DST="/etc/systemd/system/whatsar.service"
    sed \
        -e "s|/var/www/sainteku|${APP_DIR}|g" \
        "${APP_DIR}/deploy/whatsar.service" > "$UNIT_DST"
    systemctl daemon-reload
    systemctl enable whatsar
    systemctl restart whatsar
    echo "[OK] systemd: whatsar.service enabled"
    sleep 2
    if curl -fsS "http://127.0.0.1:${PORT}/health" >/dev/null 2>&1; then
        echo "[OK] Health check passed"
    else
        echo "[WARN] Health check gagal — cek: journalctl -u whatsar -n 50"
    fi
else
    echo "[INFO] Skip systemd. Jalankan manual:"
    echo "  WHATSAR_HOST=127.0.0.1 WHATSAR_PORT=${PORT} WHATSAR_DB_PATH=${DATA_DIR}/whatsar.db WHATSAR_API_KEY=<key> whatsar"
fi

echo ""
echo "Selesai. Buka /settings/whatsapp di Sainteku untuk pairing QR."