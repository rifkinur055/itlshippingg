#!/bin/sh
set -e

# Railway/runtime guard: ensure Apache loads only ONE MPM.
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf || true

a2enmod mpm_prefork >/dev/null 2>&1 || true
# best-effort disable others if present
( a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true )

a2enmod rewrite >/dev/null 2>&1 || true

# Optional debug (uncomment if needed)
# apachectl -M | grep mpm || true

exec apache2-foreground
