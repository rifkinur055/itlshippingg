#!/bin/sh
set -e

# Railway/runtime guard: ensure Apache loads only ONE MPM.
echo "[entrypoint] user=$(id -u) group=$(id -g) whoami=$(whoami)" >&2

# If this fails due to permissions, we WANT to see it (no || true)
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf

# Enable only prefork MPM
a2enmod mpm_prefork >/dev/null 2>&1 || true
# Best-effort disable others if present
( a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true )

a2enmod rewrite >/dev/null 2>&1 || true

echo "[entrypoint] enabled MPMs:" >&2
apachectl -M 2>/dev/null | grep mpm >&2 || true

exec apache2-foreground
