# Plan de remediación — fullSafety backend

Plan derivado del review de seguridad/bugs. Está ordenado por **impacto × esfuerzo**: arriba lo crítico y barato, abajo lo importante pero más invasivo. Cada ítem indica archivos, qué cambiar, criterio de "hecho" y riesgo.

Convenciones:
- 🔴 Crítico (seguridad / data leak / bypass de auth)
- 🟠 Alto (bug funcional comprobado)
- 🟡 Medio (calidad, mantenibilidad)
- ⏱ Esfuerzo estimado (S = <30min, M = 1-3h, L = >3h)

## Estado

| # | Item | Estado |
|---|---|---|
| 1 | `/api/sync` detrás de jwt.verify | ✅ HECHO |
| 2 | Middleware de permisos en rutas web | ⏳ pendiente (próxima sesión) |
| 3 | Login API valida `cuid_deleted` | ✅ HECHO |
| 4 | Eliminar `users.token` (decisión: borrar) | ✅ HECHO (migration, model, AuthController, Controller) |
| 5 | `saveImage` valida MIME real + whitelist de carpetas | ✅ HECHO |
| 6 | Unificar `role_id` en SecurityController + arreglar `{{ $view }}` suffix bug | ✅ HECHO |
| 7 | `username max:16` (decisión: cortos como `pmerino`) | ✅ HECHO |
| 8 | Borrar `AccessMaster()` muerto | ✅ HECHO |
| 9 | Borrar `replaceOrVerifyEnterprise()` roto | ✅ HECHO |
| 10 | `REPORT_EMAIL` en `.env`/`.env.example` | ✅ HECHO |
| 11 | `destroyUser` bloquea borrar al rol master | ✅ HECHO |
| 12 | `$request->all()` → `$request->only(...)` en SecurityController | ✅ HECHO |
| 13 | `disable()` / `enable()` consistentes con `status` | ⏳ requiere decisión |
| 14 | Cascada soft-delete sobre pivots Spatie | ✅ HECHO |
| 15 | Paginar `SyncController::sync` | 📋 backlog |
| 16 | Escapar wildcards LIKE | 📋 backlog |
| 17 | Cleanup `respondWithToken` | ✅ HECHO (incluido en #4) |
| 18 | Resolver rutas duplicadas `*massive` | 📋 backlog |
| 19 | Tests mínimos | 📋 backlog |
| 20 | Documentar `users.token` | ✅ N/A (columna eliminada) |
| 21 | Consistencia `viewDir` | 📋 backlog |
| 22 | Naming en API móvil | 📋 backlog |

**Bug extra detectado durante ejecución:** el partial `users/fields.blade.php` agregaba `{{ $view }}` al `name=` de cada input → en la vista de **edit** los inputs se llamaban `fullnameedit`, `usernameedit`, etc., así que **ninguno** llegaba al controller. La edición de usuarios estaba completamente rota desde hace tiempo. Resuelto en #6.

---

## Fase 1 — Cierres críticos de seguridad

### [1] 🔴 Proteger `GET /api/sync` ⏱ S

**Problema:** endpoint público dumpea catálogo completo (empresas, RUCs, emails, teléfonos, empleados con DNI, productos).

**Archivo:** [routes/api.php](routes/api.php)

**Cambio:** mover la línea `Route::get('/sync', ...)` dentro del grupo `Route::middleware('jwt.verify')->group(...)`.

**Hecho cuando:** `GET /api/sync` sin token devuelve 401; con token válido devuelve 200 con la data.

**Riesgo:** la app móvil tendría que hacer login antes de sincronizar. Verificar con el equipo móvil que el flujo lo permite (debería: el login no depende de `/sync`).

---

### [2] 🔴 Aplicar middleware de permisos a rutas administrativas ⏱ M

**Problema:** Spatie Permission está instalado y los roles seedeados, pero ninguna ruta lo usa. Cualquier usuario con sesión puede llamar acciones de admin (borrar usuarios, crear roles).

**Archivos:**
- [routes/web.php](routes/web.php)
- (verificar) [config/auth.php](config/auth.php) tenga el guard `web` con provider `users`

**Estrategia:** agrupar rutas por permiso. Reemplazo en bloques:

```php
// Antes
Route::get('/users', [SecurityController::class, 'users'])->name('users');

// Después
Route::middleware('permission:users.view')->group(function () {
    Route::get('/users', ...)->name('users');
    Route::get('/users/{user}', ...)->name('users.show');
});
Route::middleware('permission:users.create')->group(function () {
    Route::get('/users/create', ...)->name('users.create');
    Route::post('/users', ...)->name('users.store');
});
Route::middleware('permission:users.edit')->group(function () { ... });
Route::middleware('permission:users.delete')->delete('/users/{user}', ...);
```

**Mapeo módulo → grupo de permisos** (ya seedeados):

| Rutas | Permiso base |
|---|---|
| `/users/*` | `users.{view,create,edit,delete}` |
| `/roles/*` | `roles.{view,create,edit,delete}` |
| `/permissions/*` | `permissions.{view,create,edit,delete}` |
| `/companies/*` | `companies.*` (solo `master`) |
| `/checkpoints/*` | `checkpoints.*` |
| `/enterprises/*` | `enterprises.*` |
| `/employees/*` | `employees.*` |
| `/products/*`, `/productenterprises/*` | `products.*`, `product_enterprises.*` |
| `/inspections/*` | `inspections.*` |
| `/dialogues/*` | `dialogues.*` |
| `/actives/*` | `actives.*` |
| `/tests/*` | `tests.*` |
| `/controls/*` | `controls.*` |
| `/unitmovements/*` | `unit_movements.*` |
| `/report/*` | `reports.view` |

**Hecho cuando:** un usuario con rol `inspector` recibe **403** al hacer `DELETE /users/{id}`, y **200** al hacer `GET /inspections`. Test manual con dos usuarios (master + inspector).

**Riesgo:** romper navegación si un permiso no se asignó al rol correcto. Mitigación: aplicar primero a `users/roles/permissions/companies` (solo master), validar, luego al resto.

---

### [3] 🔴 Validar `cuid_deleted` en login API ⏱ S

**Problema:** [AuthController::login()](app/Http/Controllers/Api/AuthController.php#L30) hace `JWTAuth::attempt` directo; usuarios soft-deleted siguen autenticando vía móvil.

**Archivo:** [app/Http/Controllers/Api/AuthController.php](app/Http/Controllers/Api/AuthController.php)

**Cambio:** después del `attempt`, antes del `respondWithToken`:

```php
$user = JWTAuth::user();
if (!$user->isActive()) {
    JWTAuth::invalidate(JWTAuth::getToken());
    return response()->json(['status' => false, 'message' => 'Usuario inactivo'], 401);
}
```

**Hecho cuando:** `POST /api/login` con credenciales válidas de un usuario con `cuid_deleted != null` responde 401.

**Riesgo:** mínimo. El web ya tiene esta validación.

---

### [4] 🔴 Decidir qué es `users.token` ⏱ S (decisión) + S/M (implementación)

**Problema:** el comentario en `AuthController::refresh` sugiere que `users.token` guarda la contraseña cifrada reversiblemente con la APP_KEY. El campo se serializa de vuelta al cliente en `respondWithToken`. Si confirma ser eso, es una grieta seria (DB + APP_KEY → todas las contraseñas).

**Acción:**
1. **Confirmar con el equipo** qué representa `users.token` hoy.
2. Si es password cifrado: borrar el campo, eliminar `decryptText`/`encryptText` del Controller base, dejar de exponerlo en `respondWithToken`.
3. Si es token de dispositivo / sesión activa: documentarlo (CLAUDE.md), no exponerlo en respuestas.

**Archivos afectados (caso 2):**
- [app/Http/Controllers/Api/AuthController.php](app/Http/Controllers/Api/AuthController.php) (quitar `'token' => ...->token` y código comentado)
- [app/Http/Controllers/Controller.php](app/Http/Controllers/Controller.php) (quitar `encryptText`/`decryptText`)
- Migración nueva: `ALTER TABLE users DROP COLUMN token`
- [app/Models/User.php](app/Models/User.php) (quitar `token` de `$fillable`)
- [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php) (no setear `token`)

**Hecho cuando:** ningún archivo PHP referencia `->token` en User; columna eliminada por migration.

**Riesgo:** si la app móvil lee `response.user.token` para algo, se rompe. Confirmar antes.

---

### [5] 🔴 `Controller::saveImage()` valida MIME, no extensión ⏱ S

**Problema:** [Controller.php:40-69](app/Http/Controllers/Controller.php#L40) confía en `getClientOriginalExtension()`. Subir `payload.php.jpg` o `payload.php` con MIME falsificado podría dejar un archivo ejecutable bajo `public/uploads/`.

**Archivo:** [app/Http/Controllers/Controller.php](app/Http/Controllers/Controller.php)

**Cambios:**
1. Validar antes con `getimagesizefromstring` o `$image->getMimeType()` contra una whitelist (`image/jpeg`, `image/png`, `image/webp`).
2. Forzar la extensión a partir del MIME real, no del cliente.
3. Validar que `$folder` sea uno de un set conocido (no input de usuario).

**Hecho cuando:** subir un archivo `.php` renombrado a `.jpg` retorna `null` o lanza excepción y no se escribe en disco.

**Riesgo:** controllers que actualmente suben otros formatos podrían romperse. Buscar usos de `saveImage(` para validar el set permitido cubre los casos reales.

---

## Fase 2 — Bugs funcionales comprobados

### [6] 🟠 Unificar `role_id` vs `id_roles` en SecurityController ⏱ S

**Problema:** [storeUser](app/Http/Controllers/SecurityController.php#L315) valida `role_id`, [updateUser](app/Http/Controllers/SecurityController.php#L372) valida `id_roles`. Uno de los dos forms está roto en silencio.

**Acción:**
1. Inspeccionar `resources/views/security/users/create.blade.php` y `.../edit.blade.php` para ver qué `name=` mandan.
2. Estandarizar a uno (recomendado: `role_id` ya que es el primero y más legible).
3. Actualizar la vista que use el otro nombre.

**Hecho cuando:** crear usuario asigna roles y editar usuario también, ambos con el mismo nombre de input.

---

### [7] 🟠 Alinear validación `username` con la columna SQL ⏱ S

**Problema:** [users migration](database/migrations/2014_10_12_000000_create_users_table.php#L16) tiene `string('username', 16)`. Los validadores en `User::$rules` y `SecurityController::storeUser` aceptan `max:255`.

**Decisión:** ¿16 alcanza? Si el negocio usa emails como username, expandir a `100`. Si son códigos cortos, dejar 16 y bajar la validación.

**Archivos:**
- [app/Models/User.php](app/Models/User.php) — actualizar `$rules` y `$rulesLogin`
- [app/Http/Controllers/SecurityController.php](app/Http/Controllers/SecurityController.php#L311) — actualizar regla inline
- (si se decide expandir) nueva migration `ALTER TABLE users MODIFY username VARCHAR(N)`

**Hecho cuando:** validador y columna coinciden; intentar registrar un username demasiado largo retorna error de validación legible, no excepción de DB.

---

### [8] 🟠 Eliminar `AccessMaster()` y `authenticateMasterUser()` muertos ⏱ S

**Problema:** [AccessMaster()](app/Http/Controllers/Controller.php#L107) lee `session('role')` que solo se setea en código que nunca se invoca. Si una vista lo usa para mostrar UI de admin, está siempre cerrado.

**Acción:**
1. `grep -r "AccessMaster\|authenticateMasterUser" app/ resources/` para encontrar usos.
2. Si hay usos en views/controllers, reemplazar por `auth()->user()->hasRole('master')`.
3. Eliminar los dos métodos muertos.

**Hecho cuando:** ningún archivo referencia `AccessMaster()` ni `authenticateMasterUser()`; views usan `@hasrole('master')` (Spatie blade directive).

---

### [9] 🟠 `replaceOrVerifyEnterprise()` está roto ⏱ S

**Problema:** [Controller.php:115-120](app/Http/Controllers/Controller.php#L115) usa `Request::has(...)` como facade sin importarla. Si se llama, lanza error fatal.

**Acción:** `grep` de uso. Si no se usa, borrar. Si se usa, importar `use Illuminate\Support\Facades\Request;` (o mejor, recibir `Request $request` por inyección).

---

### [10] 🟠 `app.report_email` no existe ⏱ S

**Problema:** [SyncController.php:245](app/Http/Controllers/Api/SyncController.php#L245) usa `config('app.report_email', 'admin@example.com')` pero la key no está en `config/app.php`. Todos los reportes van al fallback (buzón muerto).

**Archivos:**
- [config/app.php](config/app.php) — agregar `'report_email' => env('REPORT_EMAIL'),`
- [.env.example](.env.example) y [.env](.env) — agregar `REPORT_EMAIL=`

**Hecho cuando:** `php artisan tinker` → `config('app.report_email')` retorna el valor del `.env`.

---

### [11] 🟠 `destroyUser`: bloquear borrar a master ⏱ S

**Problema:** un no-master con sesión puede borrar al master (combinado con #2). Aún resuelto #2, conviene defensa en profundidad.

**Archivo:** [SecurityController.php:411](app/Http/Controllers/SecurityController.php#L411)

**Cambio:**

```php
if ($user->hasRole('master')) {
    return redirect()->route('users')->with('error', 'No se puede eliminar a un usuario con rol master.');
}
```

(Después del check de auto-eliminación.)

---

### [12] 🟠 Reemplazar `$request->all()` por `validated()` o `only()` ⏱ S-M

**Archivos y líneas:**

- [SecurityController.php:94](app/Http/Controllers/SecurityController.php#L94) `Permission::create($request->all())` → `Permission::create($request->only(['name','description','group','guard_name']))`
- [SecurityController.php:121](app/Http/Controllers/SecurityController.php#L121) `$permission->update($request->all())` → idem
- [SecurityController.php:227](app/Http/Controllers/SecurityController.php#L227) `$role->update($request->all())` → `$role->update($request->only(['name','description','guard_name']))`
- Repasar [BranchController.php](app/Http/Controllers/BranchController.php), [WorkstationController.php](app/Http/Controllers/WorkstationController.php) (también usan `$request->all()`).

**Hecho cuando:** ningún `create()` ni `update()` recibe `$request->all()` directamente.

---

### [13] 🟠 Consistencia `disable()` / `enable()` con `status` ⏱ S

**Problema:** [User::disable()](app/Models/User.php#L163) toca `cuid_deleted`, `enable()` lo limpia, pero `status` queda colgado. Si `status` se usa en algún lado para filtrar, hay drift.

**Decisión:**
- **Opción A:** `status` es la única fuente. `disable() { $this->status = 0; $this->save(); }`. Eliminar `cuid_deleted` del filtro de login.
- **Opción B:** `cuid_deleted` es la única fuente. Eliminar `status` (migration drop).

Recomendado: **B**. `cuid_deleted` es más rico (timestamp implícito vía CUID) y consistente con el resto del sistema.

**Hecho cuando:** un solo campo determina "usuario activo".

---

## Fase 3 — Calidad y robustez

### [14] 🟡 Cascada de soft-delete sobre pivots Spatie ⏱ S

**Archivo:** [SecurityController.php:411](app/Http/Controllers/SecurityController.php#L411)

**Cambio:** dentro del try del `destroyUser`, antes del `softDelete`:

```php
$user->roles()->detach();
$user->permissions()->detach();
```

**Hecho cuando:** después de borrar un user, sus filas en `model_has_roles` y `model_has_permissions` desaparecen.

---

### [15] 🟡 Paginar / versionar `SyncController::sync` ⏱ M

**Problema:** la app móvil descarga 14 tablas completas en cada sync. Crece sin techo.

**Estrategia (versión simple):** aceptar `?since=<timestamp>` y filtrar por `cuid_inserted` + `cuid_updated`. Como CUID embebe la fecha (`SYSDATE * 100000`), se puede traducir a un threshold numérico.

**Estrategia (versión mínima):** al menos `->limit(N)` defensivo en cada query.

**Decisión:** dejarlo en backlog hasta tener métricas de tamaño real. Solo prioritizar si la app móvil reporta lentitud.

---

### [16] 🟡 Escapar wildcards en búsquedas LIKE ⏱ S

**Archivo:** [BaseRepository.php:60-85](app/Repository/BaseRepository.php#L60)

**Cambio:** helper `private function escapeLike(string $s): string { return str_replace(['\\','%','_'], ['\\\\','\\%','\\_'], $s); }` y aplicarlo a `$search` antes de los `like`.

---

### [17] 🟡 Cleanup `respondWithToken` ⏱ S

[AuthController.php:117-133](app/Http/Controllers/Api/AuthController.php#L117) llama `JWTAuth::user()` 4 veces. Asignar a `$user` una vez. Cosmético.

---

### [18] 🟡 Decidir el destino de las rutas duplicadas `*massive` ⏱ S

[routes/web.php:115](routes/web.php#L115) `POST /inspections/massive` (`InspectionController::storeMany`) y [routes/api.php:23](routes/api.php#L23) `POST /api/inspections/massive` (`SyncController::inspectionMassive`).

**Acción:** confirmar si el endpoint web está vivo. Si no, eliminarlo.

---

### [19] 🟡 Tests mínimos ⏱ M

Hoy `make test` corre solo `ExampleTest.php`. Mínimo viable:

- `tests/Feature/Auth/LoginTest.php` — login web válido / inválido / usuario inactivo.
- `tests/Feature/Api/AuthApiTest.php` — login API, refresh, logout.
- `tests/Feature/Api/SyncTest.php` — `/sync` sin token = 401, con token = 200.
- `tests/Feature/AuthorizationTest.php` — un inspector NO puede `DELETE /users/{id}`.

**Hecho cuando:** los 4 archivos pasan en `make test`.

---

## Fase 4 — Tareas opcionales / pendientes mayores

### [20] 🟡 Documentar `users.token` en CLAUDE.md una vez decidido ⏱ S

Después de #4, agregar al [CLAUDE.md](CLAUDE.md) una línea sobre el propósito definitivo del campo (o su eliminación).

### [21] 🟡 Hacer `viewDir` consistente o borrarlo ⏱ M

15 controllers tienen `static $viewDir = 'maintenance'` pero las vistas viven en carpetas distintas. La convención no se mantiene; está medio adoptada. Decidir: estandarizar todos o eliminar la propiedad y hardcodear los paths.

### [22] 🟡 Actualizar nombres de campos visibles en API móvil ⏱ M

`SyncController::sync` aliasa `id_<x> as <x>_id`. Si la app móvil ya está en producción, cualquier cambio rompe. Solo tomar nota; no tocar.

---

## Orden de ejecución sugerido

**Sprint 1 (cierre de huecos críticos)** — Fase 1 completa
1. [1] sync detrás de jwt
2. [3] login API valida cuid_deleted
3. [5] saveImage valida MIME real
4. [4] decisión sobre `users.token`
5. [2] middleware de permisos en rutas (el más invasivo, dejar para el final del sprint con tiempo a probar)

**Sprint 2 (bugs)** — Fase 2 completa
6, 7, 8, 9, 10, 11, 12, 13

**Sprint 3 (calidad)** — Fase 3 + tests
14, 16, 17, 19

**Backlog** — 15, 18, 20, 21, 22

---

## Notas operativas

- Cada cambio: branch corta + commit por fase. No mezclar fases en un solo commit.
- Después de Fase 1: probar manualmente login web + login API + un endpoint protegido de cada rol.
- Después de Fase 2: regenerar la DB (`make fresh`) para validar que seeders y migraciones siguen consistentes.
- Antes de mergear: `make test` + verificar que el navegador en `http://fullsafety.test` arranca el dashboard sin errores con el usuario master.
