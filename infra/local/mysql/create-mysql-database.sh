#!/bin/sh

if [ -f "infra/local/.env" ]; then
    # Load Environment Variables
    export $(cat "infra/local/.env" | grep -v '#' | sed 's/\r$//' | awk '/=/ {print $1}' )
fi

if [ ! "$(docker ps -q -f name=${ALIF_INFRA_DB})"  ]; then
	echo "First you need to start the ${ALIF_INFRA_DB} container"
	exit -1;
fi
docker exec alif-infra-mysql mysql -u root -p${ALIF_INFRA_DB_PASSWORD} -e "CREATE DATABASE ${ALIF_INFRA_DB_DATABASE}"
