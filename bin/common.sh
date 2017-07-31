#!/bin/bash
DIR=`dirname $0`
UNAME=`uname`
if [ "${UNAME}" == "Darwin" -a -f ~/.bash_profile ]
then
	source ~/.bash_profile
fi
if [ -f ${DIR}/variables.sh ]
then
	source ${DIR}/variables.sh
	export $(cut -d= -f1 ${DIR}/variables.sh)
fi
cd ${DIR}/..
if [ -z "${BRANCH_NAME}" ]
then
	if [ -d .git ]
	then
		BRANCH_NAME=`git rev-parse --abbrev-ref HEAD`
		if [ "${BRANCH_NAME}" == "HEAD" ]
		then
			BRANCH_NAME=`git branch --remote --verbose --no-abbrev --contains | sed -rne 's/^[^\/]*\/([^\ ]+).*$/\1/p'`
		fi
	fi
fi
if [ ! -z "${BRANCH_NAME}" ]
then
	export BRANCH=${BRANCH_NAME}
fi
if [ ! -z "${TRAVIS_BUILD_NUMBER}" ]
then
	export JOB=${TRAVIS_BUILD_NUMBER}
fi
if [ ! -z "${BUILD_NUMBER}" ]
then
	export JOB=${BUILD_NUMBER}
fi