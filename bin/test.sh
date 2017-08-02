#!/bin/bash
LOCALDIR=`dirname $0`
. ${LOCALDIR}/common.sh
cd ${LOCALDIR}/../web
DIR=`pwd`
php vendor/phpunit/phpunit/phpunit \
	--configuration dev/tests/unit/phpunit.xml.dist \
	--log-junit ${LOCALDIR}/../build/text.xml
	app/code/Adfab/Gdpr/Test/Unit/