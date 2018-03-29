#!/bin/bash
. "$(dirname "$0")/common.sh"
mkdir -p ${DIR}/build
cd web
php vendor/phpunit/phpunit/phpunit \
	--configuration dev/tests/unit/phpunit.xml.dist \
	--log-junit ${DIR}/build/test.xml \
	app/code/Adfab/Gdpr/Test/Unit/
