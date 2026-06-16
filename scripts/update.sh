#!/usr/bin/env bash
# Sainteku — update deploy (pull + deps + migrate + build)
#
# Usage:
#   bash scripts/update.sh
#   bash scripts/update.sh --branch ar
#   bash scripts/update.sh --no-git --restart-whatsar

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
# shellcheck source=_lib.sh
source "${SCRIPT_DIR}/_lib.sh"

DEV_MODE=false
NO_GIT=false
SKIP_BUILD=false
SKIP_MIGRATE=false
WITH_WHATSAR=false
RESTART_WHATSAR=false
GIT_BRANCH=""

usage() {
    usage_header
    cat <<'HELP'
Usage: bash scripts/update.sh [OPTIONS]

Update rutin setelah ada perubahan kode di server/VPS.

Options:
  --branch NAME      Git pull dari branch (default: branch aktif)
  --no-git           Lewati git pull (hanya deps + migrate + build)
  --dev              composer install dengan dev-deps
  --skip-build       Lewati npm run build
  --skip-migrate     Lewati database migrate
  --with-whatsar     Update Whatsar (Linux: binary, FreeBSD: rebuild native; perlu sudo)
  --restart-whatsar  Restart whatsar setelah update (systemd / rc.d)
  --help, -h         Bantuan

Contoh production:
  bash scripts/update.sh

Contoh tanpa git (rsync manual):
  bash scripts/update.sh --no-git --restart-whatsar
HELP
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --branch) GIT_BRANCH="$2"; shift 2 ;;
        --no-git) NO_GIT=true; shift ;;
        --dev) DEV_MODE=true; shift ;;
        --skip-build) SKIP_BUILD=true; shift ;;
        --skip-migrate) SKIP_MIGRATE=true; shift ;;
        --with-whatsar) WITH_WHATSAR=true; shift ;;
        --restart-whatsar) RESTART_WHATSAR=true; shift ;;
        --help|-h) usage; exit 0 ;;
        *) log_warn "Opsi tidak dikenal: $1"; shift ;;
    esac
done

cd "$APP_DIR"
log_info "Update Sainteku di ${APP_DIR}"
log_platform

require_php
require_cmds composer

if [[ "$NO_GIT" != "true" ]]; then
    if [[ -z "$GIT_BRANCH" ]]; then
        GIT_BRANCH="$(git -C "$APP_DIR" rev-parse --abbrev-ref HEAD 2>/dev/null || echo main)"
    fi
    git_pull_latest "$GIT_BRANCH"
fi

composer_install "$DEV_MODE"

if [[ "$SKIP_MIGRATE" != "true" ]]; then
    run_migrate "false"
else
    log_warn "Migrate dilewati (--skip-migrate)"
fi

npm_install_and_build "$SKIP_BUILD"
ensure_storage_link
fix_permissions

if [[ "$DEV_MODE" == "true" ]]; then
    clear_laravel_caches
else
    optimize_laravel
fi

maybe_install_whatsar "$WITH_WHATSAR"
if [[ "$RESTART_WHATSAR" == "true" ]]; then
    maybe_restart_whatsar "true"
else
    ensure_whatsar_running
fi
print_done "update"