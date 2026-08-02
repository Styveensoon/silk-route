# Esquema — SilkRoad (Producto 03)

## Tabla `users` (agregado en Producto 03)

| Columna   | Tipo    | Notas                                  |
|-----------|---------|------------------------------------------|
| role      | string  | 'admin' \| 'freelancer' \| 'cliente', default 'cliente' |
| is_active | boolean | default true; false = cuenta desactivada |

## Tabla `categories`

| Columna    | Tipo         | Notas |
|------------|--------------|-------|
| id         | bigint PK    |       |
| name       | string       |       |
| slug       | string unico |       |
| timestamps | -            |       |

## Tabla `services` (agregado en Producto 03)

| Columna         | Tipo           | Notas                                    |
|-----------------|----------------|-------------------------------------------|
| meeting_address | string, nullable | direccion en texto libre                |
| meeting_lat     | decimal(10,7), nullable | llenado por geocodificacion (Nominatim) |
| meeting_lng     | decimal(10,7), nullable | llenado por geocodificacion (Nominatim) |

(resto de columnas de `services` igual que en Producto 02: user_id,
category_id, title, description, price, status)

## Relaciones

- `User` 1—N `Service`
- `Category` 1—N `Service`
- `User` 1—N `PersonalAccessToken` (tokens de Sanctum para la API, la
  crea el comando `php artisan install:api` en la rama backend)