#init:
up:
	make docker-compose COMMAND="up -d"
build:
	make docker-compose COMMAND="up --build"
down:
	make docker-compose COMMAND="down"
docker-compose:
	docker-compose -f ./infra/local/docker-compose.yml --env-file infra/local/.env --project-name alif-merchant-service ${COMMAND}
