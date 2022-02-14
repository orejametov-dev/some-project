include .env
#init:
first-run:
	make build
	make create-mysql-database
	make setup-hooks
up:
	docker network create alif-infra || true
	make docker-compose COMMAND="up -d"
build:
	docker network create alif-infra || true
	make docker-compose COMMAND="up --build -d"
down:
	make docker-compose COMMAND="down"
docker-compose:
	docker-compose -f ./infra/local/docker-compose.yml --env-file infra/local/.env --project-name alif-service-merchant ${COMMAND}


#mysql
create-mysql-database:
	./infra/local/mysql/create-mysql-database.sh

#composer
install:
	make run-on-image COMMAND="composer i"
update:
	make run-on-image COMMAND="composer u"
require:
	make run-on-image COMMAND="composer require ${name}"

#configs
setup-hooks:
	cp -r ./infra/config/hooks .git/ && chmod -R +x .git/hooks/

pre-commit-hook:
	echo "STATIC ANALYZE CHECK..." && make analyze > /dev/null 2>&1\
	&& echo "CS-FIXER CHECK..." && make cs-check > /dev/null 2>&1\

# linters
cs-check:
	make run-on-image COMMAND="./vendor/bin/php-cs-fixer fix -vvv --dry-run --show-progress=dots --config=./infra/config/.php-cs-fixer.php --allow-risky=yes"
cs-fix:
	make run-on-image COMMAND="./vendor/bin/php-cs-fixer fix -vvv --show-progress=dots --config=./infra/config/.php-cs-fixer.php --allow-risky=yes"
analyze:
	make run-on-image COMMAND="./vendor/bin/phpstan analyse --memory-limit=2G --configuration='infra/config/phpstan.neon'"

run-inside-container:
	docker exec -it alif-service-merchant-app ${COMMAND}

run-on-image:
	docker run --rm -v "${PWD}"/:/app alif-service-merchant_app ${COMMAND}
