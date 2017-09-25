#!/bin/bash
LOCALDIR=`dirname $0`
. ${LOCALDIR}/common.sh
cd ${LOCALDIR}/..
UNAME=`uname`
DIR=`pwd`
if [ -d web ]
then
	rm -Rf web
fi
if ! composer config http-basic.repo.magento.com.username
then
	composer config --global http-basic.repo.magento.com "${MAGENTO_PACKAGIST_BASIC_AUTH_USERNAME}" "${MAGENTO_PACKAGIST_BASIC_AUTH_PASSWORD}"
fi
composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition web
cd web
composer require \
	fzaninotto/faker \
	magento/module-catalog-sample-data \
	magento/module-configurable-sample-data \
	magento/module-cms-sample-data \
	magento/module-sales-sample-data \
	sabas/edifact
mkdir -p app/code/Adfab/Gdpr
cd app/code/Adfab/Gdpr
for FILE in `ls ../../../../../ | grep -v web`
do
	ln -s ../../../../../${FILE}
done
cd ${DIR}
case "${DEPLOY_ENVIRONMENT}" in
	Continuous|Test|Production)
		;;
	*)
		DEPLOY_ENVIRONMENT=Build
		;;
esac
if [ -d secrets/${DEPLOY_ENVIRONMENT} ]
then
	rsync -arc secrets/${DEPLOY_ENVIRONMENT}/ web/
fi
if [ "${TRAVIS}" == "true" ]
then
	DATABASE_NAME=$(grep "'dbname'" web/app/etc/env.php | sed -e "s/'/ /g" | awk '{print $3}')
	DATABASE_USER=$(grep "'username'" web/app/etc/env.php | sed -e "s/'/ /g" | awk '{print $3}')
	DATABASE_PASSWORD=$(grep "'password'" web/app/etc/env.php | sed -e "s/'/ /g" | awk '{print $3}')
	echo "CREATE USER '${DATABASE_USER}'@'localhost' IDENTIFIED BY '${DATABASE_PASSWORD}';
GRANT USAGE ON *.* TO '${DATABASE_USER}'@'localhost' IDENTIFIED BY '${DATABASE_PASSWORD}' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
CREATE DATABASE IF NOT EXISTS \`${DATABASE_NAME}\` ;
GRANT ALL PRIVILEGES ON \`${DATABASE_NAME}\`.* TO '${DATABASE_USER}'@'localhost' ;" | mysql -f
	cd web
	bin/magento help setup:install \
		--db-host=localhost \
		--db-name="${DATABASE_NAME}" \
		--db-user="${DATABASE_USER}" \
		--db-password="${DATABASE_PASSWORD}" \
		--base-url=http://localhost \
		--base-url-secure=http://localhost \
		--language=fr \
		--timezone=Europe/Paris \
		--currency=EUR \
		--admin-user=admin \
		--admin-password="${DATABASE_PASSWORD}" \
		--admin-email=admin@example.com \
		--admin-firstname="Dev Team" \
		--admin-lastname=Adfab \
		--cleanup-database \
		--use-sample-data
	cd ..
fi
if [ ! -z "${SHARED_DIR}" ]
then
	for FILE in web/pub/media web/pub/static web/var
	do
		mkdir -p ${SHARED_DIR}/${FILE}
		if [ -e ${FILE} ]
		then
			rm -Rf ${FILE}
		fi
		ln -s ${SHARED_DIR}/${FILE} ${FILE}
	done
fi
cd web
php bin/magento cache:clean
rm -Rf \
	pub/static/* \
	var/cache/* \
	var/composer_home/* \
	var/generation/* \
	var/page_cache/* \
	var/view_preprocessed/*
php bin/magento setup:static-content:deploy
php bin/magento setup:upgrade
#php bin/magento setup:di:compile
