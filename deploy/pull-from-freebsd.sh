#!/bin/sh
# Pull kode production FreeBSD → server dev Ubuntu (arah yang benar).
# .env lokal TIDAK di-overwrite.
set -eu

REMOTE="rifky@10.87.117.3"
REMOTE_DIR="/usr/local/www/saintekku"
LOCAL_DIR="$(cd "$(dirname "$0")/.." && pwd)"

echo "==> Pull dari FreeBSD: $REMOTE:$REMOTE_DIR"
echo "==> Ke lokal: $LOCAL_DIR"

rsync -avz \
  -e "ssh -p 1977 -o StrictHostKeyChecking=no" \
  --exclude '.env' \
  --exclude '.git/' \
  --exclude 'vendor/' \
  --exclude 'vendor.bak*/' \
  --exclude 'node_modules/' \
  --exclude 'node_modules.bak*/' \
  --exclude 'storage/' \
  "$REMOTE:$REMOTE_DIR/" \
  "$LOCAL_DIR/"

echo "==> Selesai. Jalankan di lokal jika perlu:"
echo "    composer install && npm ci && npm run build"
echo "    php artisan view:clear"