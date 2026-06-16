#!/usr/bin/env bash
# Sainteku — instalasi awal (fresh deploy)
#
# Usage:
#   bash scripts/install.sh
#   bash scripts/install.sh --seed --dev
#   sudo bash scripts/install.sh --with-whatsar

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
# shellcheck source=_lib.sh
source "${SCRIPT_DIR}/_lib.sh"

DEV_MODE=false
WITH_SEED=false
SKIP_BUILD=false
SKIP_MIGRATE=false
WITH_WHATSAR=false
SKIP_OPTIMIZE=false

usage() {
    usage_header
    cat <<'HELP'
Usage: bash scripts/install.sh [OPTIONS]

Instalasi pertama: composer, npm, .env, migrate, build, optimize.

Options:
  --dev            composer install dengan dev-deps (tanpa --no-dev)
  --seed           Jalankan migrate --seed
  --skip-build     Lewati npm run build
  --skip-migrate   Lewati database migrate
  --skip-optimize  Lewati config/route/view cache
  --with-whatsar   Install Whatsar + systemd (perlu sudo)
  --help, -h       Bantuan

Contoh production:
  cp .env.example .env   # edit DB dulu
  bash scripts/install.sh

Contoh staging + seeder:
  bash scripts/install.sh --seed --dev
HELP
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --dev) DEV_MODE=true; shift ;;
        --seed) WITH_SEED=true; shift ;;
        --skip-build) SKIP_BUILD=true; shift ;;
        --skip-migrate) SKIP_MIGRATE=true; shift ;;
        --skip-optimize) SKIP_OPTIMIZE=true; shift ;;
        --with-whatsar) WITH_WHATSAR=true; shift ;;
        --help|-h) usage; exit 0 ;;
        *) log_warn "Opsi tidak dikenal: $1"; shift ;;
    esac
done

cd "$APP_DIR"
log_info "Instalasi Sainteku di ${APP_DIR}"

require_php
require_cmds composer

ensure_env_file
ensure_app_key

composer_install "$DEV_MODE"

if [[ "$SKIP_MIGRATE" != "true" ]]; then
    run_migrate "$WITH_SEED"
else
    log_warn "Migrate dilewati (--skip-migrate)"
fi

npm_install_and_build "$SKIP_BUILD"
ensure_storage_link
fix_permissions

if [[ "$SKIP_OPTIMIZE" != "true" && "$DEV_MODE" != "true" ]]; then
    optimize_laravel
else
    clear_laravel_caches
fi

maybe_install_whatsar "$WITH_WHATSAR"
print_done "install"