# SilkRoad — Guia de estudio

Este documento no es referencia tecnica (para eso esta `DOCUMENTACION.md`).
Es para que puedas **explicar el proyecto con tus propias palabras** — en
una defensa oral, un examen, o si alguien te pregunta "¿y esto como
funciona?". Cada seccion explica el *que* y el *por que*, no solo enumera
archivos.

---

## 1. Que es esto, en una frase

SilkRoad es un marketplace (como un Mercado Libre chiquito) donde
freelancers universitarios publican servicios, con un mapa que muestra en
donde se van a ver con el cliente. Tiene paginas web normales y, por
separado, una API en JSON para que otras apps se conecten.

---

## 2. El stack, explicado como si no lo conocieras

Un proyecto web moderno casi siempre tiene tres preguntas que resolver:
¿donde vive la logica de negocio y la base de datos?, ¿como se ve en el
navegador?, y ¿como se comunican esas dos partes? Aqui esta la respuesta
de cada tecnologia:

| Pieza | Que es | Que problema resuelve aqui |
|---|---|---|
| **PHP + Laravel** | Un framework de backend (el "cerebro" del lado del servidor) | Rutas, autenticacion, validacion, conexion a la base de datos, todo lo que pasa *antes* de que algo llegue al navegador |
| **MySQL** | Base de datos relacional | Guarda usuarios, categorias y servicios en tablas relacionadas entre si |
| **Eloquent** | El ORM de Laravel (Object-Relational Mapper) | Te deja escribir `Service::where('status', 'active')->get()` en vez de SQL crudo |
| **Inertia.js** | El "pegamento" entre Laravel y Vue | Deja que el backend siga controlando las rutas y los datos, pero la pantalla se sienta como una app moderna (sin recargar toda la pagina) |
| **Vue 3** | Framework de frontend | Construye la interfaz: los formularios, las tarjetas, el navbar |
| **Tailwind CSS** | Framework de utilidades CSS | En vez de escribir `.mi-boton { ... }` en un CSS aparte, se combinan clases como `rounded-full bg-clay-primary` directo en el HTML |
| **Vite** | Empaquetador de assets | Junta y optimiza el JS/CSS de Vue para que el navegador los pueda cargar rapido |
| **Sanctum** | Paquete de Laravel para tokens de API | Le da identidad a quien llama a `/api/*` sin usar cookies de sesion |

**La pregunta clasica: "¿por que Inertia y no una API + React/Vue separados
del todo?"** Porque asi no duplicas logica de negocio: las reglas de quien
puede editar un servicio, las validaciones, todo vive *una sola vez* en
Laravel. Inertia solo le manda al componente de Vue los datos ya listos
(`Inertia::render('Services/Index', ['services' => ...])`); el navegador no
tiene que pedirle nada a una API aparte para pintar la pagina.

---

## 3. El viaje de una peticion (lo mas importante para entender "como
funciona todo")

Cuando alguien visita `/services`, esto pasa en orden:

```
Navegador → Ruta (routes/web.php) → Middleware → Controller
   → Form Request (valida) → Service (regla de negocio)
   → Repository (le habla a la base de datos) → Model (Eloquent)
   → de vuelta al Controller → Inertia::render() → Vue pinta la pagina
```

Explicado con el ejemplo real de **publicar un servicio**:

1. **Ruta** (`routes/web.php`): `Route::post('/services', [ServiceController::class, 'store'])`
   — Laravel ve la URL y el metodo HTTP (POST) y decide que codigo ejecutar.
2. **Middleware**: nada especial aqui (cualquiera logueado puede publicar),
   pero en categorias/admin pasa primero por `role:admin` — si no eres
   admin, ni siquiera llega al controller, responde `403` de una vez.
3. **Controller** (`ServiceController::store`): recibe la peticion, pero
   **no valida el ni decide reglas de negocio el mismo** — eso lo delega.
4. **Form Request** (`StoreServiceRequest`): antes de que el metodo del
   controller se ejecute, Laravel ya corrio las reglas de validacion. Si
   algo esta mal, el usuario nunca llega al controller — regresa con los
   errores.
5. **Service** (`ServiceService::publish()`): aqui vive la regla de
   negocio real: "el dueño es el usuario logueado", "el estado inicial es
   `active`", "hay que geocodificar la direccion". El controller no sabe
   *como* se hace eso, solo le dice al Service "publica esto".
6. **Repository** (`EloquentServiceRepository`): el Service no le habla
   directo a Eloquent, le habla a un Repository. ¿Por que la capa extra?
   Porque si un dia cambias de Eloquent a otra cosa, solo reescribes el
   Repository — el Service (las reglas de negocio) no se toca.
7. **Model** (`Service`): finalmente aqui es donde Eloquent convierte el
   array de datos en un `INSERT INTO services (...)`.
8. **De vuelta arriba**: el controller redirige a `/services` con un
   mensaje de exito, Inertia le manda esos datos nuevos a Vue, y Vue
   actualiza la pantalla sin recargar.

**Por que separar Controller / Service / Repository si podria ser un solo
metodo gigante:** cada capa tiene una sola responsabilidad. Si te
preguntan "¿donde valido?", "¿donde esta la regla de negocio?", "¿donde se
guarda en la base?" — la respuesta siempre es una capa distinta, no todo
mezclado. Esto se llama **patron Repository + Service** y es exactamente lo
que pide la rubrica del proyecto (Producto 01, seccion 5.1-5.2).

---

## 4. Los modulos, uno por uno

### 4.1 Autenticacion (Auth)

- **Laravel Breeze** genero el scaffolding base: login, registro,
  verificacion de correo, recuperar contraseña. Es codigo estandar de
  Laravel, no se inventa desde cero.
- **Lo que se le agrego encima**: al registrarte, eliges un `role`
  (`cliente` o `freelancer` — nunca `admin`). Al iniciar sesion, si tu
  cuenta esta `is_active = false`, te bloquea aunque la contraseña sea
  correcta (`AuthenticatedSessionController::store()`).
- **Sesion vs Token**: cuando entras desde el navegador, Laravel usa una
  **cookie de sesion** (por eso no tienes que reenviar tu contraseña en
  cada clic). Cuando alguien usa la **API**, no hay navegador ni cookie —
  por eso existe Sanctum (ver seccion 6).

### 4.2 Servicios (el corazon de la app)

CRUD completo (Crear, Leer, Actualizar, Borrar) sobre la tabla `services`.
Lo interesante no es el CRUD en si, es:

- **Quien puede editar/borrar**: no cualquiera, solo el dueño o un admin.
  Eso no se checa "a mano" con un `if` en el controller — se usa una
  **Policy** (ver seccion 7).
- **El punto de encuentro**: cuando publicas, el texto que escribes
  ("Zocalo de Puebla") se manda a una API externa para conseguir
  coordenadas reales (ver seccion 8).

### 4.3 Categorias

CRUD simple, pero **solo accesible para admin** (`role:admin` en la ruta).
El `destroy()` primero revisa si la categoria tiene servicios asociados —
si los tiene, no la deja borrar y regresa un mensaje claro, en vez de
dejar que la base de datos truene con un error feo de llave foranea.

### 4.4 Administracion de usuarios

Un admin puede ver todos los usuarios, cambiarles el rol, o desactivarlos.
**A proposito no hay boton de "borrar usuario"**: si borraras un usuario
se irian en cascada todos sus servicios (por el `cascadeOnDelete` de la
migracion). Desactivar es reversible, borrar no.

### 4.5 API REST propia ("Web Service propio")

Los mismos datos y reglas de negocio de "Servicios", pero expuestos como
JSON en `/api/services`, pensados para que **otro programa** (Postman, un
app movil, otro sitio) los consuma — no un humano viendo una pagina.

**Diferencia clave con las rutas web**: las rutas web regresan
`Inertia::render(...)` (una pagina); las rutas de API regresan
`ServiceResource` (JSON puro). Reutilizan el **mismo** `ServiceService` —
la logica de negocio no se copia dos veces, solo cambia el "empaque" de la
respuesta.

---

## 5. Validaciones — como Laravel evita que basura entre a la base de datos

Cada formulario tiene una clase **Form Request** dedicada (ejemplo:
`StoreServiceRequest`). Dentro tiene un metodo `rules()`:

```php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:120'],
        'price' => ['required', 'numeric', 'min:0'],
        'category_id' => ['required', 'exists:categories,id'],
    ];
}
```

**Como funciona en la practica**: Laravel corre estas reglas *antes* de
que el codigo del controller se ejecute siquiera. Si algo falla, el
usuario nunca "entra" al controller — Laravel regresa automaticamente a la
pagina anterior con los errores, y en el frontend Vue los muestra debajo de
cada campo (`form.errors.title`).

**Por que una clase aparte y no `$request->validate([...])` dentro del
controller**: porque para formularios grandes, mezclar 10 reglas de
validacion con la logica del controller lo hace ilegible. Al separarlo,
el controller se queda limpio y solo dice "recibe algo ya validado".
(Nota: algunos controllers de este proyecto, como `CategoryController`,
si validan inline con `$request->validate()` porque son formularios de un
solo campo — no toda validacion necesita su propia clase.)

**Reglas mas usadas en el proyecto y que significan**:
- `required` — no puede venir vacio.
- `string` / `numeric` / `boolean` — el tipo de dato esperado.
- `max:120` — limite de caracteres/valor.
- `exists:categories,id` — el id que mandaron debe existir de verdad en
  esa tabla (evita que alguien mande `category_id: 9999`).
- `unique:users,email` (en registro) — no puede repetirse.
- `in:cliente,freelancer` — solo esos dos valores son validos.

---

## 6. La base de datos — Eloquent, migraciones y relaciones

### ¿Que es una migracion?

Un archivo PHP que describe un cambio a la base de datos, en codigo (no en
SQL a mano). Ventaja: el historial de cambios queda versionado en Git,
igual que el codigo. Ejemplo real del proyecto:

```php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->constrained()->restrictOnDelete();
    $table->string('title');
    $table->decimal('price', 10, 2);
});
```

- `foreignId('user_id')->constrained()` — crea la columna Y la relacion
  con la tabla `users` en una linea.
- `cascadeOnDelete()` vs `restrictOnDelete()` — que hacer si se borra el
  "padre": en `user_id`, si se borra el usuario se borran sus servicios
  (cascada). En `category_id`, si intentas borrar una categoria con
  servicios, la base de datos **rechaza** el borrado (restrict) — por eso
  `CategoryController::destroy()` revisa antes de intentarlo.

### ¿Que es Eloquent (el ORM)?

Un ORM traduce entre "objetos de PHP" y "filas de una tabla SQL", para no
escribir SQL a mano. Comparacion directa:

| SQL crudo | Eloquent |
|---|---|
| `SELECT * FROM services WHERE status='active'` | `Service::where('status', 'active')->get()` |
| `INSERT INTO services (...) VALUES (...)` | `Service::create([...])` |
| `SELECT * FROM services JOIN users ...` | `Service::with('user')->get()` |

### Relaciones (como se conectan las tablas)

```php
// En el modelo Service:
public function user() { return $this->belongsTo(User::class); }
public function category() { return $this->belongsTo(Category::class); }

// En el modelo Category:
public function services() { return $this->hasMany(Service::class); }
```

`belongsTo` = "yo tengo la llave foranea" (un `Service` le pertenece a un
`User`). `hasMany` = "el otro tiene la llave foranea apuntando a mi" (una
`Category` tiene muchos `Service`). Gracias a esto, `$service->user->name`
funciona solo, sin escribir un JOIN a mano.

---

## 7. Autorizacion — quien puede hacer que

Dos mecanismos distintos, para dos tipos de pregunta distinta:

**Middleware de rol** (`EnsureUserHasRole`, alias `role:`) responde:
*"¿tu rol te deja entrar a esta sección completa?"* — es un chequeo por
**ruta**, antes de que el controller se ejecute:

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
});
```

```php
// El middleware, resumido:
if (! in_array($request->user()->role, $roles, true)) {
    abort(403);
}
```

**Policy** (`ServicePolicy`) responde una pregunta mas fina: *"¿puedes
editar/borrar **este** servicio en particular?"* — no es por rol general,
es por **instancia** (¿es tuyo, o eres admin?):

```php
public function update(User $user, Service $service): bool
{
    return $user->id === $service->user_id || $user->role === 'admin';
}
```

```php
// En el controller:
Gate::authorize('update', $service); // 403 automatico si regresa false
```

**La diferencia en una frase para el examen**: el middleware protege
*rutas completas* segun el rol; la Policy protege *un registro especifico*
segun si te pertenece.

---

## 8. Las dos APIs — no confundirlas

Este proyecto usa la palabra "API" para dos cosas distintas, y es
justo el tipo de cosa que se presta a confusion en un examen:

### 8.1 La API que el proyecto **ofrece** ("Web Service propio")

`routes/api.php` — 5 endpoints REST (`GET/POST/PUT/DELETE /api/services`).
Es la app haciendose disponible para **que otros la consuman**. Protegida
con Sanctum: lectura publica, escritura requiere token.

```
POST /api/login  →  { "token": "1|abc123...", "user": {...} }
POST /api/services  (con header Authorization: Bearer 1|abc123...)
```

**¿Como sabe Sanctum quien eres sin cookie de sesion?** El token que te
dio `/api/login` se guarda en la tabla `personal_access_tokens`, ligado a
tu `user_id`. Cada request que llega con `Authorization: Bearer <token>`,
Sanctum busca ese token en la tabla, y si existe, "inicia sesion" a ese
usuario solo para esa peticion (sin cookies, sin estado).

### 8.2 La API que el proyecto **consume** (API de terceros: Nominatim)

`ServiceService::attachCoordinates()` le pide a
`nominatim.openstreetmap.org` que convierta un texto ("Zocalo de Puebla")
en coordenadas (`lat`/`lng`). Es la app **usando el servicio de otro**.

```php
$response = Http::withHeaders([
    'User-Agent' => 'SilkRoad-UTP/1.0 (proyecto academico UTP, ...)',
])->get('https://nominatim.openstreetmap.org/search', [
    'q' => $address, 'format' => 'json', 'limit' => 1,
]);
```

**Manejo de errores explicito** (esto es justo lo que la rubrica pide
como "manejo adecuado de respuestas y errores"): si Nominatim no responde
bien o no encuentra la direccion, el codigo **no truena** — el servicio se
guarda igual, solo con `meeting_lat`/`meeting_lng` en `null`. El mapa del
frontend simplemente no se dibuja (`MapPoint.vue` revisa si hay
coordenadas antes de intentar pintar el mapa).

**Pregunta tipica: "¿por que no usar Google Maps?"** Nominatim no pide
API key ni tarjeta de credito para usarse en proyectos pequeños — ideal
para un proyecto academico. La contraparte: solo permite 1 request por
segundo por origen (de sobra para el trafico de esta app).

---

## 9. Preguntas rapidas tipo examen (con respuesta corta)

- **¿Que es Inertia y por que no es lo mismo que una API REST?**
  Inertia le manda a Vue los datos ya listos desde el controller de
  Laravel (`Inertia::render`), en la misma peticion que carga la pagina.
  Una API REST separada esperaria a que el frontend haga un `fetch`
  aparte despues de cargar la pagina vacia.

- **¿Por que existe el Repository si el Service ya podria hablarle
  directo a Eloquent?**
  Para que la logica de negocio (Service) no dependa de *como* se guardan
  los datos (Eloquent). Si cambia el motor de persistencia, solo se
  reescribe el Repository.

- **¿Donde se checa que un freelancer no pueda editar el servicio de
  otro?**
  En `ServicePolicy::update()`, invocada con `Gate::authorize('update',
  $service)` dentro del controller.

- **¿Que pasa si Nominatim esta caido cuando alguien publica un
  servicio?**
  El servicio se guarda de todos modos, con las coordenadas en `null`. No
  se pierde la publicacion por un problema externo.

- **¿Como sabe la API quien eres, si no usa cookies?**
  Por el token Sanctum que mandas en el header `Authorization: Bearer
  <token>`, obtenido antes en `POST /api/login`.

- **¿Cual es la diferencia entre el middleware `role:admin` y la
  `ServicePolicy`?**
  El middleware bloquea *rutas completas* por rol. La Policy autoriza
  *un registro especifico* (este servicio, ese usuario).

- **¿Por que `category_id` usa `restrictOnDelete` y `user_id` usa
  `cascadeOnDelete`?**
  Porque borrar un usuario si debe llevarse sus servicios (tiene sentido
  que ya no existan). Borrar una categoria con servicios activos **no**
  deberia borrar esos servicios de rebote — por eso se bloquea el borrado
  en vez de arrastrar todo.

- **¿Donde viven las reglas de validacion del formulario de publicar un
  servicio?**
  En `App\Http\Requests\StoreServiceRequest::rules()`. Se ejecutan antes
  de que el metodo del controller corra.

---

## 10. Glosario corto

- **ORM**: capa que traduce objetos de PHP a filas de SQL (Eloquent, en
  este caso).
- **Middleware**: codigo que se ejecuta *antes* de que una ruta llegue a
  su controller (ej. revisar si estas logueado, o si tienes el rol
  correcto).
- **Policy**: clase que decide si un usuario puede hacer una accion sobre
  un registro especifico.
- **Form Request**: clase dedicada a validar los datos de un formulario
  antes de que el controller los use.
- **Migracion**: archivo versionado que describe un cambio a la base de
  datos.
- **Seeder**: script que llena la base de datos con datos de prueba.
- **Factory**: plantilla para generar datos falsos/aleatorios de un
  modelo (usada por los seeders y los tests).
- **Resource** (`ServiceResource`): clase que decide exactamente que
  forma tiene el JSON que regresa la API.
- **Token (Sanctum)**: cadena unica que identifica a un usuario en
  peticiones de API, en vez de una cookie de sesion.
