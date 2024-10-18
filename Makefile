# DOCKER_USERNAME=www-data

.PHONY: build
build:
	docker compose build --pull --no-cache
	docker compose up -d

.PHONY: stop
stop:
	docker compose stop

.PHONY: down
down:
	docker compose down --remove-orphans

.PHONY: bash
bash:
	docker compose exec php bash

.PHONY: install
install:
	composer install --prefer-dist --no-progress --no-scripts --no-interaction

.PHONY: start
start:
	build
	bash
	install