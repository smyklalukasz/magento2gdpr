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
	magento/module-catalog-sample-data \
	magento/module-configurable-sample-data \
	magento/module-cms-sample-data \
	magento/module-sales-sample-data
mkdir -p app/code/Adfab/Gdpr
cd app/code/Adfab/Gdpr
for FILE in `ls ../../../../../ | grep -v web`
do
	ln -s ../../../../../${FILE}
done
cd ${DIR}/web/
case "${DEPLOY_ENVIRONMENT}" in
	Continuous)
	Test)
	Production)
		;;
	*)
		DEPLOY_ENVIRONMENT=Build
		;;
esac
if [ -d ../secrets/${DEPLOY_ENVIRONMENT} ]
then
	rsync -arc ../secrets/${DEPLOY_ENVIRONMENT}/ ./
fi