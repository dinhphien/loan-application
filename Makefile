PROJECT_NAME=loan-application
PHPUNIT=vendor/bin/phpunit
PHPCS=vendor/bin/phpcs
PHPCBF=vendor/bin/phpcbf
PHPSTAN=vendor/bin/phpstan

COMPOSE_FILE = docker/docker-compose.yml
RUN_IN_CONTAINER = docker-compose -p $(PROJECT_NAME) -f $(COMPOSE_FILE) run --rm app
RUN_IN_CONTAINER_BASH = $(RUN_IN_CONTAINER) sh -c

############################################################################################################
###	Start / Down / Rebuild Section
############################################################################################################
install: composer_install

startup: down
	docker-compose -p ${PROJECT_NAME} -f docker/docker-compose.yml up -d

startup-not-detached: down
	docker-compose -p ${PROJECT_NAME} -f docker/docker-compose.yml up

down:
	docker-compose -p ${PROJECT_NAME} -f docker/docker-compose.yml down -v --remove-orphans

rebuild: down ## Rebuild FPM
	#docker-compose -p ${PROJECT_NAME} -f $(COMPOSE_FILE) pull
	#docker-compose -p ${PROJECT_NAME} -f $(COMPOSE_FILE) build
	docker-compose -p ${PROJECT_NAME} -f $(COMPOSE_FILE) build --pull --force-rm --no-cache

############################################################################################################
### Composer
############################################################################################################

composer_install:  ## Install Composer dependencies
	$(RUN_IN_CONTAINER_BASH) "composer install --no-progress$(ARGS)"

composer_update:
	$(RUN_IN_CONTAINER_BASH) "composer update $(ARGS)"

composer_autoload:
	$(RUN_IN_CONTAINER_BASH) "composer dump-autoload"

composer_no_dev:
	@echo "Removing Dev Dependencies"
	$(RUN_IN_CONTAINER_BASH) "composer install -o --no-dev"

############################################################################################################
### Testing
############################################################################################################

test_unit: composer_autoload
	@echo ""
	@echo "+++ Run unit tests (with coverage) +++"
	$(RUN_IN_CONTAINER) php $(PHPUNIT) -c tests/unit/config/phpunit.xml $(ARGS)

test_integration: composer_autoload
	@echo ""
	@echo "+++ Run integration tests +++"
	docker-compose -p $(PROJECT_NAME) -f $(COMPOSE_FILE) down
	$(RUN_IN_CONTAINER) php $(PHPUNIT) -c tests/integration/config/phpunit.xml $(ARGS)

phpstan:
	$(RUN_IN_CONTAINER) $(PHPSTAN) analyze -c tests/phpstan.neon --xdebug

sniff:
	@echo ""
	@echo "+++ Run code sniffer +++"
	$(RUN_IN_CONTAINER) $(PHPCS)  --runtime-set ignore_warnings_on_exit true --extensions=php -p -n --encoding=UTF-8 --standard=tests/phpcs-ruleset.xml src/ tests/unit/ tests/integration

phpcbf:
	@echo "+++ Run phpcbf to fix formatting"
	$(RUN_IN_CONTAINER) $(PHPCBF) --standard=tests/phpcs-ruleset.xml src/ tests/

