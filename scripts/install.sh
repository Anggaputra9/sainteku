#!/usr/bin/env bash
# Sainteku — instalasi awal (fresh deploy)
#
# Usage:
#   bash scripts/install.sh
#   bash scripts/install.sh --seed --dev
#   bash scripts/install.sh              # production: Whatsar otomatis (butuh sudo)
#   bash scripts/install.sh --dev        # dev: tanpa Whatsar
#   bash scripts/install.sh --skip-whatsar

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
# shellcheck source=_lib.sh
source "${SCRIPT_DIR}/_lib.sh"

DEV_MODE=false
WITH_SEED=false
SKIP_BUILD=false
SKIP_MIGRATE=false
WITH_WHATSAR=true
SKIP_WHATSAR=false
SKIP_OPTIMIZE=false
NON_INTERACTIVE=false

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
  --skip-whatsar   Lewati install Whatsar (default: aktif di mode production)
  --with-whatsar   Paksa install Whatsar (berguna di --dev)
  --non-interactive  Lewati wizard input .env (CI/script)
  --help, -h       Bantuan

Contoh production (wizard interaktif + Whatsar):
  sudo bash install.sh

Contoh staging + seeder:
  bash install.sh --seed --dev
HELP
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --dev) DEV_MODE=true; shift ;;
        --seed) WITH_SEED=true; shift ;;
        --skip-build) SKIP_BUILD=true; shift ;;
        --skip-migrate) SKIP_MIGRATE=true; shift ;;
        --skip-optimize) SKIP_OPTIMIZE=true; shift ;;
        --skip-whatsar) SKIP_WHATSAR=true; shift ;;
        --with-whatsar) WITH_WHATSAR=true; shift ;;
        --non-interactive) NON_INTERACTIVE=true; shift ;;
        --help|-h) usage; exit 0 ;;
        *) log_warn "Opsi tidak dikenal: $1"; shift ;;
    esac
done

if [[ "$DEV_MODE" == "true" && "$WITH_WHATSAR" == "true" ]]; then
    : # --with-whatsar eksplisit di dev → tetap install
elif [[ "$DEV_MODE" == "true" ]]; then
    WITH_WHATSAR=false
fi

if [[ "$SKIP_WHATSAR" == "true" ]]; then
    WITH_WHATSAR=false
fi

cd "$APP_DIR"
log_info "Instalasi Sainteku di ${APP_DIR}"
log_platform

require_php
require_cmds composer

ensure_env_file

if [[ "$NON_INTERACTIVE" != "true" ]]; then
    run_interactive_env_setup "$WITH_WHATSAR" "$DEV_MODE"
else
    log_info "Wizard .env dilewati (--non-interactive)"
fi

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