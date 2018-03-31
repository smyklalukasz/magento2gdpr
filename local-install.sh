#!/bin/bash
set -x
DEPLOY_ENVIRONMENT=Build
DATABASE_NAME=$(grep "'dbname'" secrets/${DEPLOY_ENVIRONMENT}/app/etc/env.php | sed -e "s/'/ /g" | awk '{print $3}')
DATABASE_USER=$(grep "'username'" secrets/${DEPLOY_ENVIRONMENT}/app/etc/env.php | sed -e "s/'/ /g" | awk '{print $3}')
DATABASE_PASSWORD=$(grep "'password'" secrets/${DEPLOY_ENVIRONMENT}/app/etc/env.php | sed -e "s/'/ /g" | awk '{print $3}')
cd web
rm -Rf var/cache/* var/generation/* var/di/* var/page_cache/*
php7.0 bin/magento setup:install -vvv \
		--admin-email=admin@example.com \
		--admin-firstname="Dev Team" \
		--admin-lastname=Adfab \
		--admin-password="${DATABASE_PASSWORD}" \
		--admin-user=admin \
		--admin-use-security-key=0 \
		--backend-frontname="admin" \
		--base-url=http://localhost \
		--base-url-secure=https://localhost \
		--cleanup-database \
		--currency=EUR \
		--db-host=localhost \
		--db-name="${DATABASE_NAME}" \
		--db-user="${DATABASE_USER}" \
		--db-password="${DATABASE_PASSWORD}" \
		--language=fr_FR \
		--session-save="files" \
		--timezone=Europe/Paris \
		--use-sample-data \
		--use-rewrites=1 \
		--no-interaction
