# Karina — Frontend (rama `frontend`)

Los commits 1-3 (fix del glob, layout, tarjeta) **no dependen del
backend**, los puedes ir haciendo aunque Styveen no haya terminado.

Los commits 4-8 SI necesitan que la rama `backend` de Styveen ya este
fusionada a `main` (usan las rutas /services que el crea).

Antes de empezar (commits 1-3):
```powershell
git checkout main
git pull origin main
git checkout frontend
git merge main
```

Antes del commit 4, vuelve a traer main (ya con backend fusionado):
```powershell
git checkout main
git pull origin main
git checkout frontend
git merge main
```

Al terminar el 8:
```powershell
git push origin frontend
```
Y abres el Pull Request `frontend -> main`.
