#!/bin/bash
LOCALDIR=`dirname $0`
. ${LOCALDIR}/common.sh
cd ${LOCALDIR}/../web
DIR=`pwd`
mkdir -p ${DIR}/../build
php vendor/phpunit/phpunit/phpunit \
	--configuration dev/tests/unit/phpunit.xml.dist \
	--log-junit ${DIR}/../build/test.xml \
	app/code/Adfab/Gdpr/Test/Unit/