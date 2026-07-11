# SilkRoad 🐫

Marketplace de servicios freelance para estudiantes universitarios. Conecta talento estudiantil (diseño, desarrollo, tutorías, redacción, edición de video, etc.) con quienes necesitan esos servicios dentro del entorno universitario.

**Proyecto académico** — Universidad Tecnológica de Puebla, Tecnologías de la Información, Desarrollo Web Integral.

## Equipo

- Galo Eduardo Martínez Ortuño
- Styveen Emiliano Rizo Hernández
- Karina Yáñez González

**Docente:** Pedro Martínez Galaviz
**Cuatrimestre:** Mayo – Agosto 2026

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 11 |
| Frontend | Vue 3 (Composition API) |
| Puente Backend-Frontend | Inertia.js |
| Base de datos | MySQL 8 |
| Caché / Colas | Redis |
| WebSockets | Laravel Reverb |
| Bundler | Vite |

## Requisitos previos

Antes de clonar, asegúrate de tener instalado:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18 y **npm**
- **MySQL** >= 8.0
- **Git**

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/Styveensoon/silk-route.git
   cd silk-route
   ```

2. Instala las dependencias de PHP:
   ```bash
   composer install
   ```

3. Instala las dependencias de Node:
   ```bash
   npm install
   ```

4. Copia el archivo de entorno y genera la clave de aplicación:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
   *(en Linux/Mac usa `cp` en vez de `copy`)*

5. Configura la base de datos en `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=silk_route
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Crea la base de datos vacía en MySQL:
   ```sql
   CREATE DATABASE silk_route;
   ```

6. Corre las migraciones:
   ```bash
   php artisan migrate
   ```

## Cómo levantar el proyecto (desarrollo)

Necesitas **dos terminales abiertas al mismo tiempo**, ambas en la raíz del proyecto:

**Terminal 1 — Vite (compila y sirve los assets del frontend):**
```bash
npm run dev
```

**Terminal 2 — Servidor de Laravel:**
```bash
php artisan serve
```

Con ambas corriendo, abre [http://127.0.0.1:8000](http://127.0.0.1:8000) en el navegador.

> Si ves el error `Vite manifest not found`, significa que `npm run dev` no está corriendo — Laravel busca los assets compilados y Vite aún no los sirvió.

## Estructura del proyecto

```
silk-route/
├── app/
│   ├── Http/Controllers/     # Controladores (reciben petición, devuelven respuesta)
│   ├── Http/Middleware/      # Middlewares (incluye HandleInertiaRequests)
│   ├── Models/                # Modelos Eloquent
│   ├── Repositories/          # Patrón Repository (acceso a datos desacoplado)
│   └── Services/               # Patrón Service Layer (lógica de negocio)
├── database/
│   └── migrations/             # Definición de tablas
├── resources/
│   ├── js/
│   │   ├── Pages/               # Componentes Vue que renderiza Inertia
│   │   └── app.js               # Punto de entrada del frontend
│   └── views/
│       └── app.blade.php        # Vista raíz que monta Inertia
├── routes/
│   └── web.php                  # Rutas de la aplicación
└── vite.config.js
```

## Flujo de ramas (Git)

| Rama | Propósito |
|---|---|
| `main` | Rama estable, base del proyecto |
| `backend` | Desarrollo de lógica de servidor (Laravel) |
| `frontend` | Desarrollo de interfaz (Vue) |
| `database` | Migraciones, seeders, cambios de esquema |
| `web-services` | APIs externas, SMTP, integraciones |
| `test` | Pruebas unitarias y de integración |

Cada módulo se desarrolla en su rama correspondiente y se integra a `main` mediante merge tras revisión.

## Arquitectura

Monolito modular con separación cliente-servidor. Ver documentación completa del caso de estudio (Producto 01) para detalle de patrones de diseño (Repository, Service Layer, Observer, Strategy) y justificación de arquitectura.