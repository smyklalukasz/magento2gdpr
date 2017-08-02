#!/bin/bash
LOCALDIR=`dirname $0`
. ${LOCALDIR}/common.sh
cd ${LOCALDIR}/..
DIR=`pwd`
if [ -z "${DOMAIN}" ]
then
	DOMAIN=`basename ${DIR}`
fi
rm -Rf secrets secrets.tar.gz.enc bin/variables.sh bin/variables.sh.enc
if [ ! -z "`echo ${DOMAIN} | grep '\.'`" ]
then
	curl -s -X BAN -H "Host: ${DOMAIN}" http://localhost/ -o /dev/null
fi
