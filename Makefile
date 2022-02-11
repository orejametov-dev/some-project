#init:
up:
	make docker-compose COMMAND="up -d"
build:
	make docker-compose COMMAND="up --build -d"
down:
	make docker-compose COMMAND="down"
docker-compose:
	docker-compose -f ./infra/local/docker-compose.yml --env-file infra/local/.env --project-name alif-merchant-service ${COMMAND}

#composer
install:
	make run-on-image COMMAND="composer i"
update:
	make run-on-image COMMAND="composer u"
require:
	make run-on-image COMMAND="composer require ${name}"

# linters
cs-check:
	make run-inside-container COMMAND="./vendor/bin/php-cs-fixer fix -vvv --dry-run --show-progress=dots --config=.php-cs-fixer.php --allow-risky=yes"
cs-fix:
	make run-inside-container COMMAND="./vendor/bin/php-cs-fixer fix -vvv --show-progress=dots --config=.php-cs-fixer.php --allow-risky=yes"
analyse:
	make run-inside-container COMMAND="./vendor/bin/phpstan analyse --memory-limit=-1G"

run-inside-container:
	docker exec -it alif-service-merchant-app ${COMMAND}

run-on-image:
	docker run --rm -v "${PWD}"/:/app alif-merchant-service_app ${COMMAND}
