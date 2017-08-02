#!/bin/bash
DIR=`dirname $0`
if [ -f "${DIR}/variables.sh.enc" ]
then
	if [ -z "${ENCRYPTED}" ]
	then
		echo -e "\033[1;31mPlease provide ENCRYPTED environment variable.\033[0m"
		echo "It contains a string IV:KEY"
		echo "You'll find thoses values inside wiki."
		exit 0
	fi
	echo "++++++++++++++++++++++++++"
	echo ${ENCRYPTED}
	echo "++++++++++++++++++++++++++"
	ENCRYPTED_IV=`echo ${ENCRYPTED} | sed -e 's/:/ /g' | awk '{print $1}'`
	ENCRYPTED_KEY=`echo ${ENCRYPTED} | sed -e 's/:/ /g' | awk '{print $2}'`
	openssl enc -aes-256-cbc -K ${ENCRYPTED_KEY} -iv ${ENCRYPTED_IV} -in ${DIR}/variables.sh.enc -out ${DIR}/variables.sh -d
	cd ${DIR}/..
	if [ -f secrets.tar.gz.enc ]
	then
		openssl enc -aes-256-cbc -K ${ENCRYPTED_KEY} -iv ${ENCRYPTED_IV} -in secrets.tar.gz.enc -out secrets.tar.gz -d
		tar zxvf secrets.tar.gz
		rm secrets.tar.gz
	fi
fi
