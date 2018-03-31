#!/bin/bash
. "$(dirname "$0")/common.sh"
rm -Rf secrets
[ -n "${DOMAIN}" ] && curl -s -X BAN -H "Host: ${DOMAIN}" http://localhost/ -o /dev/null || true