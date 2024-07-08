setup:
	@make prepare
	@make build
	@make up
	@make composer-update
	@make migrate
	@make db-test
prepare:
	cp .env.example .env
build:
	docker-compose build --no-cache --force-rm
up:
	docker-compose up -d
down:
	docker-compose down
composer-update:
	docker exec bank-api-php bash -c "composer install"
migrate:
	docker exec bank-api-php bash -c "php artisan migrate"
db-test:
	touch database/database.sqlite

