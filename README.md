# Equipate Full — v2

Reconstrucción de la tienda **Guayuco Digital** como dos clientes standalone que
comparten un mismo núcleo.

| Cliente | Stack | Datos | Ejecutar |
|---|---|---|---|
| Web | HTML + ES modules (sin build) | IndexedDB | `apps\web\run.cmd` |
| Escritorio | Python 3.14 + Tkinter/ttk | SQLite | `apps\desktop\run.cmd` |
| Escritorio (`.exe`) | PyInstaller onefile, **32-bit** | SQLite | `apps\desktop\dist\EquipateFull.exe` |

La web es **mobile-first** (diseño base para móvil). Idioma y tema se controlan con
**un solo botón cada uno** en ambas versiones.

Los archivos PHP de la raíz son la **versión 1** (referencia histórica); la v2 vive
en `core/` y `apps/`.

## Estructura

```
core/            Fuente única: schema, rules, i18n, tokens, seed, cases
apps/web/        Cliente web standalone
apps/desktop/    Cliente de escritorio
docs/            Documentación
CONTEXT.md       Contexto y decisiones del proyecto
```

## Credenciales de ejemplo

| Usuario | Contraseña | Nivel |
|---|---|---|
| `jfelya` | `admin123` | admin |
| `nicoandroid` | `nico123` | admin |
| `josecas` | `cliente1` | cliente |

## Self-tests (paridad)

```
apps\desktop\selftest.cmd      # 26/26
node apps\web\src\selftest.mjs # 26/26
```

Ambos leen los mismos `core/cases/validation.json`.

## Cómo funciona la paridad
- `core/schema.json` define entidades y campos.
- `core/rules.json` define validaciones y reglas de negocio.
- `core/i18n/*.json` define todos los textos ES/EN.
- `core/tokens.json` define la paleta (claro/oscuro).
- Cada app implementa un intérprete de esas reglas:
  `apps/web/src/validate.js` y `apps/desktop/src/equipate/validate.py`.

Cambiar una regla en `core/` afecta a **ambas** apps.

## Compilar el ejecutable de Windows

El `.exe` se compila **32-bit** a propósito: un solo binario corre en Windows de
32 y 64 bits. Incluye Python, Tkinter y `core/` (no requiere instalar nada).

```
apps\desktop\packaging\build.cmd
```

Salida: `apps\desktop\dist\EquipateFull.exe` (~9.5 MB, portable).

En GitHub, el workflow `.github/workflows/build.yml` corre los self-tests, compila
el `.exe` 32-bit y lo adjunta como artifact. Al empujar un tag (`v2.0.0`) lo publica
en **Releases** para descargar directo.

Si el equipo aún no tiene Python 32-bit:

```
winget install Python.Python.3.12 --architecture x86 --scope user
py -3.12-32 -m pip install pyinstaller
```

Firma opcional (evita el aviso de editor desconocido):

```
powershell -ExecutionPolicy Bypass -File apps\desktop\packaging\sign.ps1
apps\desktop\packaging\install-cert.cmd
```

## Notas

- Contraseñas con SHA-256 (no texto plano).
- El historial de facturas guarda el nombre del producto y no se borra en cascada.
- La web no necesita servidor de aplicación: solo un servidor estático
  (`py -m http.server`) porque los navegadores no permiten `fetch` de JSON por
  `file://`. No hay backend ni base de datos remota.
