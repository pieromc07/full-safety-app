# Plan de Testing — fullSafety backend

## 1. Diagnóstico de la base actual

- Solo existe `tests/Unit/ExampleTest.php` usando `PHPUnit\Framework\TestCase` (no `Laravel\TestCase`).
- No existe `tests/Feature/`, ni `tests/TestCase.php` base.
- `phpunit.xml` tiene comentado `DB_CONNECTION=sqlite :memory:`. **SQLite no es viable** porque `database/migrations/0001_01_01_000000_create_functions_database.php` crea funciones MySQL custom (`CUID_19D_B10`, `CUID_TO_DATETIME`, etc.) que el código usa intensivamente para soft-delete y timestamps.
- `database/factories/UserFactory.php` está desactualizada (usa `name` en vez de `fullname`, falta `id_enterprises`, `status`).

## 2. Estrategia de BD para tests

- BD MySQL paralela `fullsafety_test_db` dentro del mismo contenedor `mysql`.
- `phpunit.xml` apunta a `DB_CONNECTION=mysql_testing` (definida en `config/database.php`).
- **No se usa `RefreshDatabase`** (lento + frágil con triggers/funciones MySQL custom). En su lugar, los tests usan `DatabaseTransactions` y la BD de prueba se prepara con `migrate:fresh --seed` reusando los seeders existentes (`DatabaseSeeder`, `TestDataSeeder`).
- Comandos nuevos en `Makefile`:
  - `make test-setup` — crea `fullsafety_test_db` si no existe.
  - `make test-fresh` — corre `migrate:fresh --seed --database=mysql_testing --force` para resetear/preparar la BD de prueba.
- Flujo: `make test-fresh` antes del primer run (o cuando se modifiquen migraciones/seeders); luego `make test` cuantas veces se quiera. Cada test envuelve sus inserts en una transacción con rollback automático.

## 3. Infraestructura base a crear

| Archivo | Propósito |
|---|---|
| `tests/TestCase.php` | Base que extiende `Illuminate\Foundation\Testing\TestCase`, usa `CreatesApplication` |
| `tests/Feature/` | Carpeta nueva |
| `tests/Traits/AuthenticatesApiUser.php` | Helper para generar JWT y autenticar en tests API |
| `tests/Traits/AuthenticatesWebUser.php` | Helper que hace `actingAs($user)` con permisos vía spatie |
| `database/factories/UserFactory.php` | Reescribir alineado al modelo real |
| `database/factories/EnterpriseFactory.php` | Necesaria — User depende de `id_enterprises` |
| `database/factories/EnterpriseTypeFactory.php` | Cabecera de la cadena empresa→user |
| `database/factories/InspectionTypeFactory.php` | Necesaria para tests de Sync |
| `database/factories/CategoryFactory.php` | Padres + subcategorías |
| `database/factories/EvidenceFactory.php` | Tests CRUD evidencias |
| `database/factories/EmployeeFactory.php` | Tests CRUD empleados |

Resto de factories se irán agregando bajo demanda; no se crean todas de entrada (overkill).

## 4. Tests planificados

### A. Unit (lógica pura, sin BD cuando se pueda)

| Test | Cubre |
|---|---|
| `EvidenceAccessorTest` | Verifica que `$evidence->category` retorna el padre vía subcategoría (regresión del bug arreglado en `Evidence::getCategoryAttribute`). Requiere BD. |
| `SyncControllerHelpersTest` | `parseDate` y `validateRequired` (privates, expuestos vía reflection). Sin BD. |
| `UserIsActiveTest` | `User::isActive()` y `disable()`/`enable()` con `cuid_deleted`. Requiere BD. |
| `BaseRepositorySoftDeleteTest` | `BaseRepository::all()` filtra `cuid_deleted`. Requiere BD. |

### B. Feature — API (`tests/Feature/Api/`)

| Test | Endpoints / casos |
|---|---|
| `AuthLoginTest` | `POST /api/login` éxito; credenciales inválidas → 401; usuario inactivo (`cuid_deleted`) → 401 + token invalidado; throttle `5,1` → 429 al 6º intento |
| `AuthMeTest` | `POST /api/me` con/sin token; token expirado |
| `AuthLogoutTest` | `POST /api/logout` invalida token; segundo `/me` → 401 |
| `AuthRefreshTest` | `POST /api/refresh` devuelve nuevo token; token inválido → 401 |
| `JwtMiddlewareTest` | Cualquier endpoint protegido sin token → 401 con shape JSON |
| `SyncGetTest` | `GET /api/sync` retorna shape esperado (claves: enterprises, categories, evidences, …) |
| `SyncInspectionMassiveTest` | `POST /api/inspections/massive` happy path + payload inválido → ErrorLog escrito |
| `SyncDailyDialogTest` | `POST /api/dialogue` happy path |
| `SyncActivePauseTest` | `POST /api/pauseactive` happy path |
| `SyncAlcoholTestTest` | `POST /api/alcoholtest` con detalles |
| `SyncControlGpsTest` | `POST /api/controlgps` happy path |
| `PingTest` | `POST /api/ping` → 200 con `pong` |

Para Sync se usa **smoke tests** (1 happy + 1 caso de error por endpoint), no exhaustivos — el controller tiene 794 líneas y cubrir cada rama es trabajo de 1+ semana.

### C. Feature — Web (`tests/Feature/Web/`)

| Test | Cubre |
|---|---|
| `AuthWebTest` | `GET /login` 200; login OK redirige a `/home`; bad creds; logout |
| `MiddlewareAuthTest` | Acceso a `/home`, `/evidences`, etc. sin sesión → redirect `/login` |
| `EvidenceCrudTest` | index 200, store/update/destroy + soft-delete vía `cuid_deleted`, validación falla → errors flash |
| `CategoryCrudTest` | index, subcategorías (`category1`), store, destroy |
| `TargetedCrudTest` | Dirigidos + Tipo de Dirigidos (rutas `targeted` / `target`) |
| `EnterpriseCrudTest` | CRUD + assign + listar productos por proveedor/transporte |
| `EmployeeCrudTest` | CRUD básico |
| `InspectionCrudTest` | index filtrado por tipo + store con relaciones |
| `SecurityRolesTest` | Crear rol, asignar permisos, asignar a usuario |
| `SidebarRoutesSmokeTest` | Loop sobre todas las rutas GET nombradas → ninguna devuelve 500 (test paraguas barato y con alto valor) |

El último ataja regresiones tipo el de `Evidence::category` que rompió `/evidences`.

## 5. Convenciones internas

- Cada test usa `DatabaseTransactions`. La BD se prepara una vez con `make test-fresh`.
- Helper `seedMinimal()` en `TestCase` que crea un `User`, `Enterprise`, `EnterpriseType` mínimos compartidos.
- Para JWT: `JWTAuth::fromUser($user)` y se inyecta como `Authorization: Bearer …`.
- Validación de respuestas con `assertJsonStructure` cuando el shape importa, no solo `assertOk`.
- Sin mocks de DB (integración real).

## 6. Orden de ejecución

1. **Fase 1 — Infraestructura** (~1 sesión): TestCase base, factories core, BD test, smoke run.
2. **Fase 2 — API auth** (~1 sesión): los 5 tests de `AuthController` + `JwtMiddleware`.
3. **Fase 3 — Web smoke + auth** (~1 sesión): `AuthWebTest`, `MiddlewareAuthTest`, `SidebarRoutesSmokeTest`. Esto detecta regresiones masivas barato.
4. **Fase 4 — Web CRUD** (~2 sesiones): los CRUDs uno por uno.
5. **Fase 5 — API Sync** (~2 sesiones): smoke por endpoint.
6. **Fase 6 — Unit puntuales** (~1 sesión): los 4 unit tests.

## 7. Lo que *no* se incluye (y por qué)

- **No** tests de PDFs (`unitmovements.export.pdf`, `inspections.report`) — `dompdf` es difícil de testear sin acoplarse a su HTML.
- **No** tests de Mail (Mailpit) — basta con `Mail::fake()` en sync test cuando haga falta.
- **No** factories para los 30 modelos — solo las que un test necesita; el resto se queda sin factory.
- **No** cobertura ≥80% como objetivo. Apunto a smoke + happy path + casos críticos de auth/seguridad.
