#!/bin/bash
LOCALDIR=`dirname $0`
. ${LOCALDIR}/common.sh
cd ${LOCALDIR}/../web
DIR=`pwd`
php vendor/phpunit/phpunit/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/Adfab/Gdpr/Test/Unit/