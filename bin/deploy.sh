#!/bin/bash
if [ $# -lt 1 ]
then
	echo "Deploy project to hosting"
	echo "	Usage : $0 [Continuous|Test|Preproduction|Production]"
	exit 1
fi
LOCALDIR=`dirname $0`
. ${LOCALDIR}/common.sh
cd ${LOCALDIR}/..
DEPLOY_ENVIRONMENT=$1
if [ "${DEPLOY_ENVIRONMENT}" == "Production" -a "${BRANCH}" != "master" ] ;
then
	echo "Only master branch can be deployed in production"
	exit 1
else
	echo -e "\033[1;32mDeploying to \"${DEPLOY_ENVIRONMENT}\"\033[0m"
fi
/usr/bin/cap ${DEPLOY_ENVIRONMENT} deploy
