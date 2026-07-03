#!/bin/sh
# Pastikan public/storage mengarah ke storage/app/public di server ini.
# Perbaiki otomatis jika symlink hilang atau mengarah ke path salah (mis. path dev Ubuntu).
#
# Usage (FreeBSD production):
#   sh deploy/ensure-storage-link.sh
#   sh deploy/ensure-storage-link.sh /usr/local/www/saintekku
#
# Dari Ubuntu dev (remote):
#   ssh -p 1977 rifky@10.87.117.3 "doas sh /usr/local/www/saintekku/deploy/ensure-storage-link.sh"

set -eu

APP_DIR="${1:-/usr/local/www/saintekku}"
WEB_USER="${WEB_USER:-www}"
LINK="${APP_DIR}/public/storage"
TARGET="${APP_DIR}/storage/app/public"
BACKUP_DIR="${APP_DIR}/storage/backups"

abs_path() {
    _dir="$1"
    _base=""
    case "$_dir" in
        /*) _base="$_dir" ;;
        *) _base="$(pwd)/$_dir" ;;
    esac
    echo "$_base" | sed 's|/\./|/|g; s|/\.$||; s|//|/|g'
}

EXPECTED="$(abs_path "${TARGET}")"

mkdir -p "${TARGET}" "${BACKUP_DIR}"

backup_link_state() {
    _stamp="$(date +%Y%m%d-%H%M%S)"
    _file="${BACKUP_DIR}/storage-symlink.${_stamp}.txt"
    {
        echo "checked_at=${_stamp}"
        echo "expected=${EXPECTED}"
        if [ -L "${LINK}" ]; then
            echo "type=symlink"
            echo "current=$(readlink "${LINK}")"
            ls -la "${LINK}"
        elif [ -e "${LINK}" ]; then
            echo "type=non-symlink"
            ls -la "${LINK}"
        else
            echo "type=missing"
        fi
    } > "${_file}" 2>&1
    chown "${WEB_USER}:${WEB_USER}" "${_file}" 2>/dev/null || true
    echo "    backup: ${_file}"
}

fix_ownership() {
    if id -u "${WEB_USER}" >/dev/null 2>&1; then
        chown -h "${WEB_USER}:${WEB_USER}" "${LINK}" 2>/dev/null || true
    fi
}

verify_link() {
    if [ ! -L "${LINK}" ]; then
        echo "ERROR: ${LINK} bukan symlink setelah perbaikan" >&2
        return 1
    fi

    _current="$(readlink "${LINK}")"
    if [ "${_current}" != "${EXPECTED}" ]; then
        echo "ERROR: symlink masih salah: ${_current} (harus ${EXPECTED})" >&2
        return 1
    fi

    if [ ! -d "${LINK}" ]; then
        echo "ERROR: target symlink tidak dapat diakses: ${LINK}" >&2
        return 1
    fi

    echo "    OK storage:link -> ${_current}"
    return 0
}

echo "==> Cek storage:link di ${APP_DIR}"

if [ -L "${LINK}" ]; then
    _current="$(readlink "${LINK}")"
    if [ "${_current}" = "${EXPECTED}" ] && [ -d "${LINK}" ]; then
        echo "    Sudah benar: ${LINK} -> ${_current}"
        exit 0
    fi
    echo "    Symlink salah atau rusak: ${_current}"
    echo "    Harus mengarah ke: ${EXPECTED}"
    backup_link_state
    rm -f "${LINK}"
elif [ -e "${LINK}" ]; then
    echo "ERROR: ${LINK} ada tetapi bukan symlink — perbaiki manual" >&2
    backup_link_state
    exit 1
else
    echo "    Symlink belum ada, akan dibuat"
    backup_link_state
fi

ln -s "${EXPECTED}" "${LINK}"
fix_ownership

if ! verify_link; then
    exit 1
fi

echo "==> storage:link siap"