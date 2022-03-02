include infra/local/.env

#init:
first-run:
	docker login ginger.alifshop.uz:443
	make build
	chmod o+rw storage bootstrap/
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
	docker-compose -f ./infra/local/docker-compose.yml --env-file infra/local/.env --project-name ${APP_NAME} ${COMMAND}


#mysql
create-mysql-database:
	./infra/local/mysql/create-mysql-database

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
	echo "STATIC ANALYZE CHECK..." && make analyze \
	&& echo "CS-FIXER CHECK..." && make cs-check

# linters
cs-check:
	make run-on-image COMMAND="./vendor/bin/php-cs-fixer fix -vvv --dry-run --show-progress=dots --config=./infra/config/.php-cs-fixer.php --allow-risky=yes"
cs-fix:
	make run-on-image COMMAND="./vendor/bin/php-cs-fixer fix -vvv --show-progress=dots --config=./infra/config/.php-cs-fixer.php --allow-risky=yes"
analyze:
	make run-on-image COMMAND="./vendor/bin/phpstan analyse --memory-limit=2G --configuration='infra/config/phpstan.neon'"

laravel:
	make run-inside-container COMMAND="php artisan ${name}"

# когда нам нужны зависимости от базы, других сервисов или запуск команд которые связаны с сетью, то используем run-inside-container
run-inside-container:
	docker exec -it ${APP_CONTAINER_NAME} ${COMMAND}
# когда нам нужно запускать команды без зависимостей и без необходимости в контексте приложения, одноразовые команды
run-on-image:
	docker run --rm -v "${PWD}"/:/app ${IMAGE_PHP} ${COMMAND}
