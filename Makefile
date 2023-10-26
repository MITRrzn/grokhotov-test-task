PHP_CONTAINER = grokhotov-test-task-php-1
DB_CONTAINER = grokhotov-test-task-db-1
NGINX_CONTAINER = grokhotov-test-task-nginx-1

bash:
	docker exec -it -w /../srv $(PHP_CONTAINER) sh

up:
	docker compose up -d

down:
	docker stop $(PHP_CONTAINER) $(DB_CONTAINER) $(NGINX_CONTAINER)

psql:
	docker exec -it $(DB_CONTAINER) psql -h localhost --port 5432 -U grokhotov-user grokhotov-db
