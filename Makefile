PHP_CONTAINER = grokhotov-php
DB_CONTAINER = grokhotov-db
NGINX_CONTAINER = grokhotov-nginx

bash:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash

composer-install:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash -c "composer install"

migrate:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash -c "bin/console doctrine:migrations:migrate"

parse-books:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash -c "bin/console app:parse-books"

admin:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash -c "bin/console app:create-admin"

up:
	docker compose up -d

down:
	docker stop $(PHP_CONTAINER) $(DB_CONTAINER) $(NGINX_CONTAINER)
