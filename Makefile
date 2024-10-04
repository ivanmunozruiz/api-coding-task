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
build: build-container composer-install ## Construye las dependencias del proyecto

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

enter-container: ## Entra en el contenedor de PHP
	docker exec -it $(shell docker ps -qf "name=php") /bin/sh

asyncapi-resolve: ## AsyncAPI generation
	docker run --rm -v $(PWD)/doc/asyncapi:/project wework/speccy resolve asyncapi.yaml > gen/asyncapi.yaml
	## git add ./gen/asyncapi.yaml

asyncapi-lint: ## AsyncAPI validation
	docker run --rm -v $(PWD)/doc/asyncapi:/tmp -w /tmp stoplight/spectral lint asyncapi.yaml
	docker run --rm -v $(PWD)/gen:/tmp -v $(PWD)/doc/asyncapi/.spectral.yaml:/tmp/.spectral.yaml -w /tmp stoplight/spectral lint asyncapi.yaml

openapi-resolve: ## Genera la especificación OpenAPI
	docker run --rm -v $(PWD)/doc/openapi:/project wework/speccy resolve openapi.yaml > gen/openapi.yaml

openapi-lint: ## OpenAPI validation
	docker run --rm -v $(PWD)/doc/openapi:/tmp -w /tmp stoplight/spectral lint openapi.yaml
	docker run --rm -v $(PWD)/gen:/tmp -v $(PWD)/doc/openapi/.spectral.yaml:/tmp/.spectral.yaml -w /tmp stoplight/spectral lint openapi.yaml




