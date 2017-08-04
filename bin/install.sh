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
