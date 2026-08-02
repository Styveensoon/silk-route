# Styveen — Backend (rama `backend`)

**Espera a que la rama `database` de Galo este fusionada a `main`.**

Antes de empezar:
```powershell
git checkout main
git pull origin main
git checkout backend
git merge main
```

Ejecuta 1.md a 7.md en orden y ve haciendo push. El **commit 8 es distinto**:
se hace sobre la rama `test`, no sobre `backend` (asi activamos esa rama
que ya existe en el repo pero esta vacia). El propio 8.md te dice los
comandos exactos.

Al terminar el 7 (antes del 8):
```powershell
git push origin backend
```
Y abres el Pull Request `backend -> main`.
