#!/bin/bash
. "$(dirname "$0")/common.sh"
if [ -n "$1" ]
then
	case "$1" in
		Continuous|Test|Preproduction|Production)
			;;
		*)
			echo "Deploy project to hosting"
			echo "	Usage : $0 [Continuous|Test|Preproduction|Production]"
			exit 1
			;;
	esac
fi
if [ $# -lt 1 ]
then
	if [ "${BRANCH}" = "master" ] ;
	then
		DEPLOY_ENVIRONMENT="Production"
	else
		DEPLOY_ENVIRONMENT="Continuous"
	fi
else
	DEPLOY_ENVIRONMENT=$1
fi
if [ "${DEPLOY_ENVIRONMENT}" == "Production" ] && [ "${BRANCH}" != "master" ] ;
then
	echo "Only master branch can be deployed in production"
	exit 1
else
	echo -e "\033[1;32mDeploying to \"${DEPLOY_ENVIRONMENT}\"\033[0m"
fi
/usr/bin/cap "${DEPLOY_ENVIRONMENT}" deploy --dry-run

### Tag
if [ "${DEPLOY_ENVIRONMENT}" != "Continuous" ]
then
	if [ -n "${BUILD_URL}" ]
	then
		TAG_COMMENT="Automatic deployment from ${BUILD_URL}"
	else
		TAG_COMMENT="Manual deployment ($(whoami))"
	fi
	# shellcheck disable=SC2086
	git tag -a "$(echo ${DEPLOY_ENVIRONMENT} | tr '[:upper:]' '[:lower:]')-$(date --utc '+%F-%H-%M-%S')" -m "${TAG_COMMENT}"
	git push --tags
fi
