export DOCKER_BUILDKIT=1
DC = docker compose
EXEC = $(DC) exec -it php
COMPOSER = $(EXEC) composer

php:
	@$(EXEC) /bin/bash

install:
	@$(DC) build
	@$(MAKE) start -s

composer-update:
	@$(COMPOSER) update

composer-install:
	@$(COMPOSER) install --optimize-autoloader

package-remove:
	@$(COMPOSER) remove

start:
	@$(DC) up -d --remove-orphans --no-recreate

stop:
	@$(DC) kill
	@$(DC) rm -v --force

db-make-migrate:
	@$(EXEC) bin/console make:migration

db-run-migrate:
	@$(EXEC) bin/console doctrine:migrations:migrate

create-user:
	@$(EXEC) bin/console app:create:user  $(EMAIL) $(PASSWORD)

tests:
	@$(EXEC) bin/console doctrine:database:drop --force --env=test || true
	@$(EXEC) bin/console doctrine:database:create --env=test
	@$(EXEC) bin/console doctrine:schema:create --env=test
	@$(EXEC) bin/phpunit $@

.PHONY: tests
