# Plan de commits — Modulo de Servicios (SilkRoad)

Este es un plan de trabajo temporal, no es parte de la aplicacion. Se
elimina del repositorio una vez que los 24 commits esten aplicados y
fusionados a `main` (ultimo paso de este README).

## Que vamos a construir

Un modulo completo (vertical slice) del marketplace: **publicar y listar
servicios freelance**. Cubre base de datos, backend (Laravel, con los
patrones Repository y Service Layer que ya documentamos en el Producto 01)
y frontend (Vue 3 + Inertia).

## Reparto

| Persona  | Rol      | Rama       | Carpeta       |
|----------|----------|------------|---------------|
| Galo     | DBA      | `database` | `galo/`       |
| Styveen  | Backend  | `backend`  | `styveen/`    |
| Karina   | Frontend | `frontend` | `karina/`     |

Cada carpeta tiene 8 archivos `1.md` a `8.md`. Se ejecutan **en orden**,
uno por uno: abres el `.md`, copias el codigo donde indica, y corres el
commit exacto que trae al final.

## Orden de todo el equipo (importante, hay dependencias)

El backend necesita las tablas de Galo. El frontend necesita las rutas de
Styveen. No es paralelo del todo: es una posta con una parte en paralelo.

```
1. GALO trabaja su rama `database` (sus 8 commits) -> push
2. GALO abre Pull Request database -> main -> se revisa -> se fusiona
   (mientras Galo termina, KARINA puede ir adelantando SUS commits 1-3,
    que son de layout/UI y no dependen del backend)
3. STYVEEN hace `git checkout main && git pull origin main`
   luego `git checkout backend && git merge main` (trae las tablas)
   trabaja sus 8 commits -> push
4. STYVEEN abre Pull Request backend -> main -> se revisa -> se fusiona
5. KARINA hace `git checkout main && git pull origin main`
   luego `git checkout frontend && git merge main` (trae las rutas/API)
   termina sus commits 4-8 (los que sí dependen del backend) -> push
6. KARINA abre Pull Request frontend -> main -> se revisa -> se fusiona
```

Revision cruzada sugerida (para que quede evidencia de revision real):
Galo revisa el PR de Styveen, Styveen revisa el de Karina, Karina revisa
el de Galo.

## Como ejecutar un .md (para quien no tenga Claude Code)

1. Abre el archivo `N.md` de tu carpeta.
2. Ve la seccion **Codigo**: dice la ruta exacta del archivo y su
   contenido completo. Si el archivo no existe, lo creas. Si ya existe,
   reemplazas su contenido completo por el del bloque (salvo que el .md
   diga explicitamente "agrega esto a" — ahi solo agregas esa parte).
3. Guarda.
4. Copia y pega los comandos de la seccion **Comandos a ejecutar** en tu
   terminal (PowerShell), uno por uno.
5. Pasa al siguiente numero.

## Al terminar (los 3 fusionados a main)

Borrar esta carpeta de instrucciones en un commit final sobre `main`:

```powershell
git checkout main
git pull origin main
git rm -r commits
git commit -m "chore: eliminar carpeta temporal de instrucciones de commits"
git push origin main
```

## Convencion de mensajes de commit

Usamos Conventional Commits (ya documentado en `CONVENTIONS.md` del repo):
`feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`. Cada `.md` ya
trae el mensaje exacto a usar, no hay que inventarlo.
