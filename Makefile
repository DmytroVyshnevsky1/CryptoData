DOCKER_CONTAINER = symfony_app
CONSOLE = bin/console
DOCKER_EXEC = docker exec $(DOCKER_CONTAINER)
DOCKER_CONSOLE = $(DOCKER_EXEC) $(CONSOLE)

# Docker Compose
up:
	docker compose up -d --build --remove-orphans
	$(MAKE) composer-install

down:
	docker compose down

# Composer
composer-install:
	$(DOCKER_EXEC) composer install

# Symfony Cache
cache-clear:
	$(DOCKER_CONSOLE) cache:clear

.PHONY: up down composer-install cache-clear
