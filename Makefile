.PHONY: all

# CONFIG ---------------------------------------------------------------------------------------------------------------
ifneq (,$(findstring xterm,${TERM}))
    BLACK   := $(shell tput -Txterm setaf 0)
    RED     := $(shell tput -Txterm setaf 1)
    GREEN   := $(shell tput -Txterm setaf 2)
    YELLOW  := $(shell tput -Txterm setaf 3)
    BLUE    := $(shell tput -Txterm setaf 4)
    MAGENTA := $(shell tput -Txterm setaf 5)
    CYAN    := $(shell tput -Txterm setaf 6)
    WHITE   := $(shell tput -Txterm setaf 7)
    RESET   := $(shell tput -Txterm sgr0)
else
    BLACK   := ""
    RED     := ""
    GREEN   := ""
    YELLOW  := ""
    BLUE    := ""
    MAGENTA := ""
    CYAN    := ""
    WHITE   := ""
    RESET   := ""
endif

COMMAND_COLOR := $(GREEN)
HELP_COLOR := $(BLUE)

IMAGE_NAME=graphicresources/itpg-api-coding-task
IMAGE_TAG_BASE=base
IMAGE_TAG_DEV=development

ASYNC_IMAGE_NAME := asyncapi/generator
ASYNC_OUTPUT_DIRECTORY := asyncapi-docs

PHPUNIT = ./vendor/bin/phpunit
BEHAT = ./vendor/bin/behat
COVERAGE_DIR = coverage/
EXEC_APP = docker exec -it $(shell docker ps -qf "name=php")
EXEC_APP_NO_IT = docker exec $(shell docker ps -qf "name=php")


# DEFAULT COMMANDS -----------------------------------------------------------------------------------------------------
all: help

help: ## Listar comandos disponibles en este Makefile
	@echo "╔══════════════════════════════════════════════════════════════════════════════╗"
	@echo "║                           ${CYAN}.:${RESET} AVAILABLE COMMANDS ${CYAN}:.${RESET}                           ║"
	@echo "╚══════════════════════════════════════════════════════════════════════════════╝"
	@echo ""
	@grep -E '^[a-zA-Z_0-9%-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "${COMMAND_COLOR}%-40s${RESET} ${HELP_COLOR}%s${RESET}\n", $$1, $$2}'
	@echo ""


# BUILD COMMANDS -------------------------------------------------------------------------------------------------------
build: build-container composer-install setup-hooks ## Construye las dependencias del proyecto

build-container: ## Construye el contenedor de la aplicación
	docker build --no-cache --target development -t $(IMAGE_NAME):$(IMAGE_TAG_DEV) .

composer-install: ## Instala las dependencias via composer
	docker run --rm -v ${PWD}:/var/www -w /var/www $(IMAGE_NAME):$(IMAGE_TAG_DEV) composer install --verbose

composer-update: ## Actualiza las dependencias via composer
	docker run --rm -v ${PWD}:/var/www -w /var/www $(IMAGE_NAME):$(IMAGE_TAG_DEV) composer update --verbose

composer-require: ## Añade nuevas dependencias de producción
	docker run --rm -ti -v ${PWD}:/var/www -w /var/www $(IMAGE_NAME):$(IMAGE_TAG_DEV) composer require --verbose

composer-require-dev: ## Añade nuevas dependencias de desarrollo
	docker run --rm -ti -v ${PWD}:/var/www -w /var/www $(IMAGE_NAME):$(IMAGE_TAG_DEV) composer require --dev --verbose

enter-container-php: ## Entra en el contenedor de PHP
	$(EXEC_APP) /bin/sh

# DOC COMMANDS -------------------------------------------------------------------------------------------------------

asyncapi-resolve: ## AsyncAPI generation
	docker run --rm -v $(PWD)/doc/asyncapi:/project wework/speccy resolve asyncapi.yaml > gen/asyncapi.yaml
	git add ./gen/asyncapi.yaml

asyncapi-lint: ## AsyncAPI validation
	docker run --rm -v $(PWD)/doc/asyncapi:/tmp -w /tmp stoplight/spectral lint asyncapi.yaml
	docker run --rm -v $(PWD)/gen:/tmp -v $(PWD)/doc/asyncapi/.spectral.yaml:/tmp/.spectral.yaml -w /tmp stoplight/spectral lint asyncapi.yaml

openapi-resolve: ## Genera la especificación OpenAPI
	docker run --rm -v $(PWD)/doc/openapi:/project wework/speccy resolve openapi.yaml > gen/openapi.yaml
	git add ./gen/openapi.yaml

openapi-lint: ## OpenAPI validation
	docker run --rm -v $(PWD)/doc/openapi:/tmp -w /tmp stoplight/spectral lint openapi.yaml
	docker run --rm -v $(PWD)/gen:/tmp -v $(PWD)/doc/openapi/.spectral.yaml:/tmp/.spectral.yaml -w /tmp stoplight/spectral lint openapi.yaml

php-lint:
	$(EXEC_APP_NO_IT) ./vendor/bin/phpcbf

rector:
	$(EXEC_APP_NO_IT) ./vendor/bin/rector process

# HELPER COMMANDS -------------------------------------------------------------------------------------------------------
setup-hooks: ## Configure git hooks
	@git config core.hooksPath ./hooks/

pre-commit: php-lint rector unit-test  ## Execute precommit tasks

local-ci:
	make openapi-resolve
	make asyncapi-resolve
	make bdd-test

##@ Testing
UNIT_TEST_PATH :=

clean-reports:
	@rm -rf report/*

unit-test: clean-reports ## Execute unit tests with no coverage
ifneq ($(strip $(UNIT_TEST_PATH)),)
	$(EXEC_APP_NO_IT) php -d memory_limit=-1 ./vendor/bin/phpunit --no-coverage ${UNIT_TEST_PATH}
else
	$(EXEC_APP_NO_IT) php -d memory_limit=-1 ./vendor/bin/phpunit --no-coverage --stop-on-failure
endif

unit-test-coverage: clean-reports ## Execute unit tests with coverage
	$(EXEC_APP) php -d memory_limit=-1 ./vendor/bin/phpunit --stop-on-failure

MUTANT_TEST_PATH :=

mutant-test: unit-test-coverage ## Execute mutant tests
	ifneq ($(strip $(MUTANT_TEST_PATH)),)
		$(EXEC_APP) php -d memory_limit=-1 ./vendor/bin/infection --filter=$(MUTANT_TEST_PATH) --threads=${NUM_PROCESSORS} --coverage=report --skip-initial-tests --show-mutations
	else
		$(EXEC_APP) php -d memory_limit=-1 ./vendor/bin/infection --threads=${NUM_PROCESSORS} --coverage=report --skip-initial-tests --show-mutations
	endif


BDD_TEST_PATH :=

bdd-test: ## Execute behat tests
	$(EXEC_APP) rm -rf /tmp/symfony-cache
	$(EXEC_APP) php bin/console doctrine:database:drop --env=test --quiet --no-interaction --if-exists --force
	$(EXEC_APP) php bin/console doctrine:database:create --env=test --quiet --no-interaction
	$(EXEC_APP) php bin/console doctrine:migrations:migrate --env=test --quiet --no-interaction --all-or-nothing
	ifneq ($(strip $(BDD_TEST_PATH)),)
		$(EXEC_APP) php -d memory_limit=-1 ./vendor/bin/behat --no-snippets --strict -vvv $(BDD_TEST_PATH)
	else
		$(EXEC_APP) php -d memory_limit=-1 ./vendor/bin/behat --no-snippets --strict -vvv
	endif


