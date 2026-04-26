DC := docker compose
APP := $(DC) exec app

.PHONY: help build up down restart logs shell mysql composer artisan migrate fresh seed test cache-clear permissions hosts

help:
	@echo "Comandos disponibles:"
	@echo "  make build          Construye las imagenes Docker"
	@echo "  make up             Levanta los contenedores en background"
	@echo "  make down           Detiene y elimina los contenedores"
	@echo "  make restart        Reinicia los contenedores"
	@echo "  make logs           Muestra logs en vivo"
	@echo "  make shell          Abre bash en el contenedor app"
	@echo "  make mysql          Abre cliente mysql"
	@echo "  make composer cmd=...   Ejecuta composer (ej: make composer cmd=install)"
	@echo "  make artisan cmd=...    Ejecuta artisan (ej: make artisan cmd=route:list)"
	@echo "  make migrate        Ejecuta migraciones"
	@echo "  make fresh          migrate:fresh --seed"
	@echo "  make seed           db:seed"
	@echo "  make test           Corre PHPUnit"
	@echo "  make cache-clear    Limpia caches de Laravel"
	@echo "  make permissions    Corrige permisos de storage y bootstrap/cache"
	@echo "  make hosts          Muestra como anadir fullsafety.test al hosts"

build:
	UID=$$(id -u) GID=$$(id -g) $(DC) build

up:
	UID=$$(id -u) GID=$$(id -g) $(DC) up -d

down:
	$(DC) down

restart:
	$(DC) restart

logs:
	$(DC) logs -f --tail=100

shell:
	$(APP) bash

mysql:
	$(DC) exec mysql sh -c 'mysql -u$$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'

composer:
	$(APP) composer $(cmd)

artisan:
	$(APP) php artisan $(cmd)

migrate:
	$(APP) php artisan migrate

fresh:
	$(APP) php artisan migrate:fresh --seed

seed:
	$(APP) php artisan db:seed

test:
	$(APP) php artisan test

cache-clear:
	$(APP) php artisan optimize:clear

permissions:
	$(APP) chown -R appuser:appuser storage bootstrap/cache
	$(APP) chmod -R ug+rwX storage bootstrap/cache

hosts:
	@echo "Agrega esta linea a /etc/hosts (requiere sudo):"
	@echo ""
	@echo "127.0.0.1   fullsafety.test www.fullsafety.test pma.fullsafety.test mail.fullsafety.test"
	@echo ""
	@echo "Comando rapido:"
	@echo "  echo '127.0.0.1   fullsafety.test www.fullsafety.test pma.fullsafety.test mail.fullsafety.test' | sudo tee -a /etc/hosts"
