# SilkRoad 🐫 — Documentación técnica

Marketplace de servicios freelance entre estudiantes universitarios. Un
**freelancer** publica un servicio (con precio y un punto de encuentro físico
geolocalizado), cualquier visitante lo puede ver en `/services`, y un
**admin** administra categorías y usuarios. El proyecto expone la misma
lógica de negocio por dos canales: paginas web (Inertia) y una API REST en
JSON (Sanctum).

## 1. Que es / que hace

- Cualquier persona (sin cuenta) puede ver el listado publico de servicios.
- Un usuario se registra eligiendo su rol: **cliente** o **freelancer**
  (nunca admin — esas cuentas se crean por seeder o las promueve otro admin).
- Un **freelancer** puede publicar, editar y borrar sus propios servicios.
  Al publicar, el backend geocodifica la direccion de encuentro que escribio
  (texto libre, ej. "Zocalo de Puebla") contra la API publica de OpenStreetMap
  Nominatim, y guarda lat/lng para poder dibujar un mapa (Leaflet) en el
  frontend.
- Un **admin** administra categorias (CRUD completo) y usuarios (cambiar rol,
  activar/desactivar cuentas). Un usuario desactivado no puede iniciar
  sesion aunque su contraseña sea correcta.
- Todo lo anterior tambien esta disponible como API REST en JSON bajo
  `/api/*`, autenticada con tokens Sanctum, pensada para integrarse desde
  Postman o cualquier cliente externo.

## 2. Stack tecnico

| Capa | Tecnologia |
|---|---|
| Backend | Laravel 11 (PHP ^8.2) |
| Puente back/front | Inertia.js 2 (SPA sin API intermedia para las paginas web) |
| Frontend | Vue 3 + Vite + Tailwind CSS |
| Autenticacion de sesion | Laravel Breeze (stack Inertia + Vue) |
| Autenticacion de API | Laravel Sanctum 4 (tokens personales) |
| Base de datos | MySQL |
| Mapas | Leaflet + tiles de OpenStreetMap |
| Geocodificacion (API de terceros) | OpenStreetMap Nominatim (sin API key) |
| Tests | PHPUnit + `RefreshDatabase` |

## 3. Arquitectura

Patron **Repository + Service** para la entidad principal (`Service`), para
que toda la escritura pase por un solo lugar y los controllers queden
delgados:

```
Controller (HTTP)  →  Service (reglas de negocio)  →  Repository (persistencia)  →  Model
```

- `App\Repositories\ServiceRepositoryInterface` — contrato (`all`, `create`,
  `update`).
- `App\Repositories\EloquentServiceRepository` — implementacion con Eloquent.
  Enlazada en `App\Providers\AppServiceProvider::register()`.
- `App\Services\ServiceService` — logica de negocio: asigna el dueño y el
  estado al publicar, dispara la geocodificacion, delega la persistencia al
  repository.
- Los `Controllers` (`ServiceController`, `Api\ApiServiceController`) solo
  validan la request, llaman al Service y devuelven la respuesta
  (Inertia o JSON). No tienen logica de negocio.

Category y AdminUser son mas simples (CRUD directo en el controller) porque
no tienen reglas de negocio adicionales mas alla de la autorizacion.

## 4. Modelo de datos

### Tabla `users`

| Columna | Tipo | Notas |
|---|---|---|
| id | bigint PK | |
| name, email, password | string | estandar de Laravel |
| **role** | string | `admin` \| `freelancer` \| `cliente`, default `cliente` |
| **is_active** | boolean | default `true`; `false` = cuenta desactivada, bloquea login |
| email_verified_at | timestamp nullable | |

### Tabla `categories`

| Columna | Tipo | Notas |
|---|---|---|
| id | bigint PK | |
| name | string | |
| slug | string unico | generado con `Str::slug()` |

### Tabla `services`

| Columna | Tipo | Notas |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | `cascadeOnDelete` |
| category_id | FK → categories | `restrictOnDelete` (no se puede borrar una categoria con servicios) |
| title | string | |
| description | text | |
| price | decimal(10,2) | |
| status | string | default `active` |
| **meeting_address** | string nullable | texto libre que escribe el freelancer |
| **meeting_lat**, **meeting_lng** | decimal(10,7) nullable | los llena el backend via Nominatim; quedan `null` si no se pudo geocodificar |

### Tabla `personal_access_tokens`

Estandar de Sanctum — guarda los tokens que emite `POST /api/login`.

### Relaciones

- `User` 1—N `Service` (`user->services()`)
- `Category` 1—N `Service` (`category->services()`)
- `Service` N—1 `User`, N—1 `Category`

### Modelos (`app/Models`)

- **`User`**: `HasApiTokens` (Sanctum) + `HasFactory` + `Notifiable`.
  `fillable`: name, email, password, role, is_active. `is_active` casteado a
  boolean.
- **`Category`**: `fillable`: name, slug. Relacion `services()`.
- **`Service`**: `fillable`: user_id, category_id, title, description, price,
  status, meeting_address, meeting_lat, meeting_lng. `price` casteado a
  `decimal:2`, lat/lng a `decimal:7`. Relaciones `user()` y `category()`.

## 5. Migraciones

En orden de ejecucion:

1. `create_users_table` / `create_cache_table` / `create_jobs_table` — scaffold estandar de Laravel.
2. `create_categories_table` — tabla `categories`.
3. `create_services_table` — tabla `services` (sin punto de encuentro todavia).
4. `add_role_to_users_table` — agrega `role` e `is_active` a `users`.
5. `add_meeting_point_to_services_table` — agrega `meeting_address`, `meeting_lat`, `meeting_lng` a `services`.
6. `create_personal_access_tokens_table` — la crea Sanctum (`php artisan install:api`).

```bash
php artisan migrate
```

## 6. Seeders (datos de demo)

`php artisan db:seed` corre, en orden:

1. **`DatabaseSeeder`** — crea 3 usuarios, uno por rol, todos con la
   contraseña `password` (default de `UserFactory`):
   - `admin@example.com` → role `admin`
   - `freelancer@example.com` → role `freelancer`
   - `cliente@example.com` → role `cliente`
2. **`CategorySeeder`** — crea 6 categorias fijas: Tutorias, Programacion,
   Diseño grafico, Redaccion y traduccion, Fotografia y video, Musica.
3. **`ServiceSeeder`** — crea 12 servicios via `Service::factory()`.

`database/factories/ServiceFactory.php` genera cada servicio con un
freelancer nuevo (`role: freelancer`), una categoria aleatoria ya sembrada, y
un punto de encuentro **fijo** tomado de una lista de lugares reales de
Puebla (Zocalo, Angelopolis, CU BUAP, UTP) — a proposito no llama a
Nominatim durante el seeding, para no depender de internet ni hacerlo lento.

## 7. Autenticacion y autorizacion

### Roles

`admin` | `freelancer` | `cliente`. Se elige entre `cliente`/`freelancer` al
registrarse (`role` es un campo requerido del formulario de registro);
`admin` nunca es seleccionable ahi.

### Sesion web (Breeze)

- Login/registro redirigen a `/services` (no al Dashboard generico de
  Breeze, que no se usa en este proyecto pero se dejo montado en `/dashboard`
  por si se necesita).
- `AuthenticatedSessionController::store()` valida ademas que `is_active`
  sea `true`; si no, cierra la sesion recien creada y lanza un error de
  validacion ("Tu cuenta esta desactivada...").

### Autorizacion

- **`App\Http\Middleware\EnsureUserHasRole`** (alias `role:`) — compara
  `$request->user()->role` contra los roles permitidos que se le pasan como
  parametro (`->middleware(['auth', 'role:admin'])`). Si no matchea, `403`.
- **`App\Policies\ServicePolicy`** — `update`/`delete`: solo el dueño del
  servicio (`user_id === $user->id`) o un `admin`. Se invoca con
  `Gate::authorize('update', $service)` (Laravel 11 ya no trae el trait
  `AuthorizesRequests` por defecto en el controller base).

### API (Sanctum)

- `POST /api/login` — recibe email/password, regresa `{ token, user }`. El
  token se manda como `Authorization: Bearer <token>` en los endpoints
  protegidos.
- `config/sanctum.php` — configuracion estandar publicada por
  `php artisan install:api`.

## 8. Validaciones (Form Requests)

| Request | Campos | Reglas |
|---|---|---|
| `RegisteredUserController::store` (inline) | name, email, password, role | `role` limitado a `in:cliente,freelancer` |
| `Auth\LoginRequest` | email, password | rate limiting (5 intentos) antes de autenticar |
| `StoreServiceRequest` | title, description, price, category_id, meeting_address | title max 120, description max 2000, price numeric ≥ 0, category_id debe existir, meeting_address requerido |
| `ProfileUpdateRequest` | name, email | email unico ignorando al propio usuario |
| `CategoryController` (inline) | name | max 80 |
| `AdminUserController::update` (inline) | role, is_active | role `in:admin,freelancer,cliente`, is_active boolean |

## 9. Rutas web (`routes/web.php`, `routes/auth.php`)

| Metodo | Ruta | Quien | Descripcion |
|---|---|---|---|
| GET | `/` | publico | Home: hero + ultimos 6 servicios activos |
| GET | `/services` | publico | listado de servicios activos |
| GET/POST | `/services/create`, `/services` | auth | publicar servicio |
| GET/PUT/DELETE | `/services/{service}/edit`, `/services/{service}` | dueño o admin | editar/borrar (via `ServicePolicy`) |
| GET/POST/PUT/DELETE | `/categories*` | `role:admin` | CRUD de categorias |
| GET/PUT | `/admin/users`, `/admin/users/{user}` | `role:admin` | listar usuarios, cambiar rol/estado |
| GET/POST | `/login`, `/register`, `/logout`, `/forgot-password`, `/reset-password` | Breeze estandar | autenticacion |
| GET/PATCH/DELETE | `/profile` | auth | editar perfil / borrar cuenta (scaffold de Breeze, sin enlace en el navbar actual) |
| GET | `/dashboard` | auth + verified | pantalla generica de Breeze, no enlazada desde el navbar (el flujo real usa `/services`) |

## 10. API REST (`routes/api.php`) — Web Service propio

5 endpoints en JSON, montados en `/api/services`, reusando el mismo
`ServiceService`/Repository que las paginas web (no se duplica logica de
negocio, solo se expone por un segundo canal):

| Metodo | Ruta | Auth | Descripcion |
|---|---|---|---|
| POST | `/api/login` | — | login, regresa token Sanctum |
| GET | `/api/services` | publico | listado (paginado por `ServiceResource::collection`) |
| GET | `/api/services/{service}` | publico | detalle |
| POST | `/api/services` | Sanctum | crear |
| PUT | `/api/services/{service}` | Sanctum + `ServicePolicy::update` | editar |
| DELETE | `/api/services/{service}` | Sanctum + `ServicePolicy::delete` | borrar |

`App\Http\Resources\ServiceResource` da forma a la respuesta: id, title,
description, price, status, `category{id,name}`, `user{id,name}`,
`meeting_point{address,lat,lng}`, created_at ISO 8601.

## 11. API de terceros: geocodificacion con Nominatim

`ServiceService::attachCoordinates()` (llamada desde `publish()` y
`updateService()`):

1. Toma `meeting_address` (texto libre).
2. Llama a `GET https://nominatim.openstreetmap.org/search` con
   `q`, `format=json`, `limit=1`, mandando el header `User-Agent` que pide
   la politica de uso de Nominatim (no requiere API key; limite: 1
   request/segundo por origen, de sobra para esta app).
3. **Manejo de errores explicito**: si la respuesta falla o no encuentra la
   direccion, el servicio se guarda igual pero con `meeting_lat`/`meeting_lng`
   en `null` — nunca bloquea la publicacion del servicio. El frontend
   simplemente no dibuja el mapa (`MapPoint.vue`) cuando no hay coordenadas.

## 12. Frontend

- **Layout**: `resources/js/Layouts/AppLayout.vue` — navbar con enlaces
  condicionados por sesion/rol (Publicar solo si `freelancer`, Categorias/
  Usuarios solo si `admin`).
- **`resources/js/Components/ServiceCard.vue`** — tarjeta reusable
  (titulo, precio, categoria, descripcion, autor, punto de encuentro).
- **`resources/js/Components/MapPoint.vue`** — mapa Leaflet que dibuja un
  marcador si hay `lat`/`lng`; si no, muestra un aviso de "sin ubicacion".
- **Paginas** (`resources/js/pages`):
  - `Home.vue` — landing con hero y grilla de servicios recientes.
  - `Services/Index.vue`, `Create.vue`, `Edit.vue` — CRUD de servicios,
    `Edit.vue` incluye el mapa de la ubicacion guardada.
  - `Categories/Index.vue`, `Create.vue`, `Edit.vue` — CRUD de categorias.
  - `Admin/Users.vue` — tabla editable de usuarios (rol + activo).
  - `Auth/*`, `Profile/*`, `Dashboard.vue`, `Welcome.vue` — scaffold de
    Breeze, sin tocar salvo lo necesario para el flujo de roles.

## 13. Tests

`php artisan test` — 25 tests, PHPUnit + `RefreshDatabase`.

- **`tests/Feature/ServiceApiTest.php`** (el que pide explicitamente el
  flujo de este proyecto):
  1. `GET /api/services` es publico y regresa JSON.
  2. `POST /api/services` sin token → `401`.
  3. `POST /api/services` autenticado (`Sanctum::actingAs`) → `201` y el
     servicio queda en la base. Usa `Http::fake()` para no depender de
     Nominatim/internet en el test.
- Resto: suite estandar de Breeze (`AuthenticationTest`, `RegistrationTest`,
  `ProfileTest`, `EmailVerificationTest`, etc.), ajustada a las decisiones de
  este proyecto (redirect a `/services` en vez de `dashboard`, `role`
  requerido al registrarse).

## 14. Como correrlo en local

```bash
composer install
npm install
cp .env.example .env      # configurar DB_* para MySQL local
php artisan key:generate
php artisan migrate
php artisan db:seed        # opcional: datos de demo (3 usuarios + categorias + servicios)
npm run dev                 # o: npm run build
php artisan serve
```

Usuarios de prueba tras el seed (contraseña `password` para los tres):

| Email | Rol |
|---|---|
| admin@example.com | admin |
| freelancer@example.com | freelancer |
| cliente@example.com | cliente |
