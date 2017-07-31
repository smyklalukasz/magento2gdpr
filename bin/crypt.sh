#!/bin/bash
DIR=`dirname $0`
if [ -f "${DIR}/variables.sh" ]
then
	if [ -z "${ENCRYPTED}" ]
	then
		echo -e "\033[1;31mPlease provide ENCRYPTED environment variable.\033[0m"
		echo "It contains a string IV:KEY"
		echo "You'll find thoses values inside wiki or you can generate with following command:"
		echo -e "\033[1;32mopenssl enc -aes-256-cbc -k *secret* -P -md sha1\033[0m"
		echo "If you generate new credentials, don't forget to copy then inside wiki AND continuous integration."
		exit 1
	fi
	ENCRYPTED_IV=`echo ${ENCRYPTED} | sed -e 's/:/ /g' | awk '{print $1}'`
	ENCRYPTED_KEY=`echo ${ENCRYPTED} | sed -e 's/:/ /g' | awk '{print $2}'`
	openssl enc -aes-256-cbc -K ${ENCRYPTED_KEY} -iv ${ENCRYPTED_IV} -in ${DIR}/variables.sh -out ${DIR}/variables.sh.enc
	cd ${DIR}/..
	if [ -d secrets ]
	then		
		tar zcvf secrets.tar.gz secrets
		openssl enc -aes-256-cbc -K ${ENCRYPTED_KEY} -iv ${ENCRYPTED_IV} -in secrets.tar.gz -out secrets.tar.gz.enc
		rm secrets.tar.gz
	fi
fi
