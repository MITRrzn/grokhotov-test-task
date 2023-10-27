PHP_CONTAINER = grokhotov-test-task-php-1
DB_CONTAINER = grokhotov-test-task-db-1
NGINX_CONTAINER = grokhotov-test-task-nginx-1

bash:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash

migrate:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash -c "bin/console doctrine:migrations:migrate"

parse-books:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash -c "bin/console app:parse-books"

up:
	docker compose up -d

down:
	docker stop $(PHP_CONTAINER) $(DB_CONTAINER) $(NGINX_CONTAINER)
