# Plan de commits — Producto 03 (SilkRoad)

Continuacion directa del modulo de Servicios (Producto 02). Aqui se
agrega: login + roles, se completa el CRUD de Servicios, se agrega
Categorias como entidad secundaria completa, administracion de
usuarios, el Web Service propio (API REST con Sanctum) y el consumo
del Web Service de terceros (mapa del punto de encuentro con
OpenStreetMap/Nominatim, sin API key).

Esta carpeta cubre TODO lo programable. La documentacion tecnica en
PDF (portada, diagrama ER, evidencias, etc.) se arma aparte, cuando
esto este terminado.

## Orden ESTRICTO (posta, no paralelo)

A diferencia del Producto 02, aqui NO se trabaja en paralelo. Es una
posta de 3 etapas, cada una necesita que la anterior este mergeada a
`main` antes de empezar:

```
1. GALO   -> rama `database` -> 5 commits  -> PR -> merge a main
2. STYVEEN-> rama `backend`  -> 11 commits -> PR -> merge a main
3. KARINA -> rama `frontend` -> 9 commits  -> PR -> merge a main
```

Total: 25 commits. Es mucho mas grande que el Producto 02 porque aqui
entra login, roles, una entidad nueva completa, panel de admin, la API
REST completa y la integracion del mapa — es el "producto grande" del
cuatrimestre, tomenlo con el tiempo que merece.

## Antes de que cada quien empiece

```powershell
git checkout main
git pull origin main
git checkout <tu-rama>
git merge main
```

## Al terminar TODO (los 3 fusionados)

```powershell
git checkout main
git pull origin main
git rm -r commits_p3
git commit -m "chore: eliminar carpeta temporal de instrucciones de commits (Producto 03)"
git push origin main
```

## Usuarios de prueba (despues del seeding de Galo)

| Correo | Password | Rol |
|---|---|---|
| admin@example.com | password | admin |
| freelancer@example.com | password | freelancer |
| cliente@example.com | password | cliente |

## Decisiones de diseño que ya se tomaron (para el documento despues)

- **Entidad secundaria:** Categoria (CRUD completo, solo administrador).
- **Roles:** campo `role` en `users` (admin / freelancer / cliente) +
  middleware propio `EnsureUserHasRole`, en vez de un paquete externo —
  mas simple de explicar en la presentacion.
- **Login:** Laravel Breeze, stack Inertia + Vue (gratis, oficial de
  Laravel, hecho para este stack exacto).
- **Web Service propio:** capa nueva en `routes/api.php`, separada de
  las rutas Inertia, protegida con Laravel Sanctum (tokens). Se
  reutiliza el mismo `ServiceService`/`ServiceRepositoryInterface` que
  ya existia — no se duplica logica de negocio, solo se expone por un
  segundo canal.
- **Web Service de terceros:** OpenStreetMap Nominatim (geocodificacion,
  sin API key) + Leaflet (mapa, sin API key) para mostrar el punto de
  encuentro de cada servicio. La geocodificacion se hace en el
  backend (no en el navegador) para no exponer el limite de uso.
