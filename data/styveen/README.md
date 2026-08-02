# Styveen — Backend (rama `backend`)

**Espera a que `database` de Galo este fusionada a `main`.**

Antes de empezar:
```powershell
git checkout main
git pull origin main
git checkout backend
git merge main
```

Ejecuta 1.md a 10.md en orden (el 1 y el 8 son distintos a los demas:
son comandos que instalan paquetes y generan archivos automaticamente,
no codigo para copiar). El **commit 11 va en la rama `test`**, no en
`backend` — el propio 11.md trae los comandos exactos.

Al terminar el 10:
```powershell
git push origin backend
```
Y abres el Pull Request `backend -> main`.
