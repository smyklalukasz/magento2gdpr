#!/bin/bash
BINDIR=$(dirname $0)
cd "${BINDIR}/.."
export DIR=$(pwd)
export UNAME=$(uname)
set -e
[ "${UNAME}" == "Darwin" ] && [ -f ~/.bash_profile ] && source ~/.bash_profile
[ "${UNAME}" == "Darwin" ] && alias sed=$(command -v gsed)
if [ -f "${BINDIR}/variables.sh" ]
then
	source "${BINDIR}/variables.sh"
	export $(cut -d= -f1 "${BINDIR}/variables.sh")
fi
if [ -f .env ]
then
	source .env
	export $(grep = .env | cut -d= -f1)
fi
BRANCH=${CI_COMMIT_REF_NAME:-${CI_BUILD_REF_NAME:-${TRAVIS_BRANCH:-${BRANCH_NAME:-${BRANCH}}}}}
if [ -z "${BRANCH}" ] && [ -d .git ]
then
	BRANCH=$(git rev-parse --abbrev-ref HEAD)
	if [ "${BRANCH}" == "HEAD" ]
	then
		BRANCH=$(git branch --remote --verbose --no-abbrev --contains | sed -rne 's/^[^\/]*\/([^\ ]+).*$/\1/p')
	fi
fi
export BRANCH
export JOB=${CI_JOB_ID:-${CI_BUILD_ID:-${TRAVIS_BUILD_NUMBER:-${BUILD_NUMBER:-1}}}}
[[ ! ${JOB} =~ ^[0-9]+$ ]] && export JOB=1
export REGISTRY_TOKEN=${REGISTRY_TOKEN:-${CI_JOB_TOKEN}}
export REGISTRY_IMAGE=${REGISTRY_IMAGE:-${CI_REGISTRY_IMAGE}}
export REGISTRY=$(echo ${REGISTRY_IMAGE} | sed -E 's#/.*##g')
[ -z "${DOMAIN}" ] && basename "${DIR}" | grep -q '\.' && export DOMAIN=$(basename ${DIR}) || true
