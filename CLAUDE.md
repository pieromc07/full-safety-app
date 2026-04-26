# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Stack

Laravel 10 + PHP 8.2 + MySQL 8. Backend de un sistema SST (Seguridad y Salud en el Trabajo) que sirve dos consumidores:

- **Web admin** (Blade + tema Metronic/KeenIcons) bajo middleware `auth` (sesión).
- **API móvil** (`/api/*`) con auth JWT (`tymon/jwt-auth`); el endpoint principal es `SyncController` para sincronización masiva desde la app móvil.

## Entorno de desarrollo

Todo corre en Docker. **No hay flujo soportado para correr `php artisan` desde el host** porque `DB_HOST=mysql` solo resuelve dentro de la red Docker.

```bash
make build              # Construye la imagen PHP-FPM (primera vez o si tocas docker/php/*)
make up                 # Levanta el stack (app, nginx, mysql, mailpit, phpmyadmin)
make down               # Detiene todo
make logs               # tail -f de todos los servicios
make shell              # bash dentro del contenedor app
make permissions        # Corrige permisos de storage/ y bootstrap/cache
```

El stack expone HTTP por **Traefik** vía la red externa `proxy`. URLs locales (requieren `/etc/hosts`):

| URL | Servicio |
|---|---|
| http://fullsafety.test | App Laravel |
| http://pma.fullsafety.test | phpMyAdmin |
| http://mail.fullsafety.test | Mailpit (UI) |
| `localhost:3309` | MySQL para clientes externos (DBeaver, etc.) |

El primer arranque ejecuta [docker/scripts/entrypoint.sh](docker/scripts/entrypoint.sh) que: copia `.env.example` → `.env` si falta, instala vendor, genera `APP_KEY` y `JWT_SECRET`, y limpia caches de Laravel.

## Comandos comunes (todos vía Makefile)

```bash
make migrate                    # php artisan migrate
make fresh                      # migrate:fresh --seed (resetea la DB)
make seed                       # db:seed
make test                       # php artisan test (PHPUnit)
make cache-clear                # optimize:clear
make composer cmd="install"     # composer dentro del contenedor
make artisan cmd="route:list"   # cualquier artisan
make mysql                      # cliente mysql interactivo
```

Para correr un test puntual:

```bash
make artisan cmd="test --filter=NombreDelTest"
# o
docker compose exec app vendor/bin/phpunit --filter NombreDelTest
```

## Convenciones del codebase

### Modelos

- **Primary keys snake_case plural** prefijadas con `id_`: `id_users`, `id_employees`, `id_inspections`. Cada modelo declara `protected $primaryKey = 'id_<tabla>';`.
- **Soft-delete custom via `cuid_deleted`** (no usa `Illuminate\Database\Eloquent\SoftDeletes`). Para "borrar" se setea `cuid_deleted` con la función MySQL `CUID_19D_B10()`. Los listados filtran con `whereNull('cuid_deleted')` (ver [BaseRepository::all()](app/Repository/BaseRepository.php)).
- **Funciones MySQL custom** definidas en la primera migración [0001_01_01_000000_create_functions_database.php](database/migrations/0001_01_01_000000_create_functions_database.php): `CUID_19D_B10`, `CUID_13D_B36`, `CUID_TO_DATETIME`, `DATETIME_TO_CUID`. Si se reseteo la DB fuera de migraciones (ej: dump SQL), hay que recrear estas funciones.

### Controllers

- Web controllers en [app/Http/Controllers/](app/Http/Controllers/), uno por entidad, formato resource (index/create/store/show/edit/update/destroy) registrado manualmente en [routes/web.php](routes/web.php) (no `Route::resource`).
- API móvil en [app/Http/Controllers/Api/](app/Http/Controllers/Api/): `AuthController` (login/refresh/me/logout) y `SyncController` (endpoints de sincronización masiva: `/inspections/massive`, `/dialogue`, `/pauseactive`, `/alcoholtest`, `/controlgps`).
- Algunos controllers exponen `static $viewDir = '<carpeta>'` y resuelven vistas con `view($this::$viewDir . '.<archivo>')`. Convención inconsistente: la mayoría hardcodea el path. Cuando edites uno, sigue el patrón ya usado en ese archivo.

### Repositorios

`app/Repository/BaseRepository.php` provee CRUD genérico con soft-delete vía `cuid_deleted`. Los repos específicos (`EmployeeRepository`, `EnterpriseRepository`, etc.) lo extienden. **Solo algunos modelos pasan por repositorio**; la mayoría de controllers consultan Eloquent directo. No fuerces el patrón si el controller existente no lo usa.

### Auth

- **Web**: `Auth::routes(['register' => false, 'reset' => false])` + middleware `auth`. Roles/permisos vía `spatie/laravel-permission`.
- **API**: middleware `jwt.verify` (alias de [JwtMiddleware](app/Http/Middleware/JwtMiddleware.php), responde 401 con JSON estructurado).
- Login throttled (`throttle:5,1`) en `POST /api/login`.

### Vistas

Tema Metronic 8 (KeenIcons). Layout principal en [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php), sidebar en [resources/views/layouts/fragments/side-1.blade.php](resources/views/layouts/fragments/side-1.blade.php). El sidebar usa helpers Blade `$isActive(...)` y `$groupOpen(...)` definidos al inicio del archivo para marcar `active`/`here show` según `request()->routeIs(...)`. Si agregas un grupo accordion nuevo, replica el patrón.

Vistas por dominio (no siempre coinciden con el nombre de la entidad): `maintenance/`, `inspections/`, `dialogue/`, `active/`, `test/`, `control/`, `movements/`, `reports/`, `security/`, `employees/`, `workstations/`.

### Migraciones

32 migraciones, con nombres compuestos `create_<entidad>_table.php`. Tablas relacionales con sufijo `_rels_` (ej: `enterprise_rels_enterprises`, `targeted_rels_inspections`). Mantén ese estilo si agregas pivots.

## Notas operativas

- Cuando edites `.env` o `config/*`, corre `make cache-clear` (Laravel cachea config).
- El `php.ini` en local tiene `opcache.validate_timestamps=1` → cambios `.php` se reflejan al instante, no hace falta reiniciar `app`.
- Si tocas [docker/php/Dockerfile](docker/php/Dockerfile), [docker/php/php.ini](docker/php/php.ini) o [docker/nginx/default.conf](docker/nginx/default.conf): `make build && make up`. Para un cambio solo en nginx config: `docker compose restart nginx`.
- Tests están minimal (solo `ExampleTest.php`), pero la infraestructura PHPUnit está configurada en [phpunit.xml](phpunit.xml) con suites `Unit` y `Feature`.
