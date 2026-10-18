# Equipate Full — v2

Tienda **Guayuco Digital** reconstruida como **sitio web (Astro)** y **app de
escritorio (Python/Tkinter)** que comparten un mismo núcleo de datos y reglas.

## Estructura (raíz = proyecto Astro)

```
/
├── astro.config.mjs        Config del sitio (output: static)
├── package.json            Scripts: dev, build, test, exe
├── tsconfig.json
├── public/                 Estáticos → se sirven en la raíz del sitio
│   ├── EquipateFull.exe    ← app de escritorio (32-bit, portable)
│   └── favicon.svg
├── src/                    Código del sitio (guía de Astro)
│   ├── pages/index.astro   Página principal del sitio
│   ├── layouts/BaseLayout.astro
│   ├── scripts/            app.js · core.js · i18n.js · store.js · theme.js
│   │                       · validate.js · selftest.mjs
│   └── styles/global.css
├── core/                   Fuente única: schema, rules, i18n, tokens, seed, cases
├── desktop/                App de Windows (todo lo demás)
│   ├── src/                Código Python (paquete `equipate`)
│   ├── packaging/          .spec, version_info, build.cmd, firma
│   ├── assets/             icono (icon.ico / icon.png)
│   ├── tools/              gen_icon.py
│   ├── dist/               .exe compilado (ignorado por git)
│   ├── run.cmd             ejecutar en desarrollo
│   └── selftest.cmd
└── legacy/                 Proyecto original en PHP (v1, solo referencia)
```

- **Raíz** = sitio web (Astro) y contiene el `.exe` en `public/`.
- **`desktop/`** = el resto de la app de Windows (código, empaquetado, recursos).
- **`legacy/`** = los archivos PHP originales.

## Ejecutar

| Qué | Comando |
|---|---|
| Web (desarrollo) | `npm install` y luego `run-web.cmd` (o `npm run dev`) |
| Web (compilada) | `npm run build` → `dist/` (estático, se puede subir a cualquier hosting) |
| Escritorio | `desktop\run.cmd` |
| App `.exe` | `desktop\dist\EquipateFull.exe` (o descárgala en `/EquipateFull.exe`) |

## Compilar el `.exe` de Windows

Se compila **32-bit** a propósito: un solo binario corre en Windows de 32 y 64 bits.
Incluye Python, Tkinter y `core/` (no requiere instalar nada).

```
desktop\packaging\build.cmd
```

Salida: `desktop\dist\EquipateFull.exe` y se copia a `public\EquipateFull.exe`.

Si el equipo no tiene Python 32-bit:

```
winget install Python.Python.3.12 --architecture x86 --scope user
py -3.12-32 -m pip install pyinstaller
```

Firma opcional: `desktop\packaging\sign.ps1` + `desktop\packaging\install-cert.cmd`.

## Pruebas

```
npm run test        # web (26) + escritorio (26), mismas reglas de core/
```

## Credenciales de ejemplo

| Usuario | Contraseña | Nivel |
|---|---|---|
| `jfelya` | `admin123` | admin |
| `nicoandroid` | `nico123` | admin |
| `josecas` | `cliente1` | cliente |

## Cómo funciona la paridad

`core/` es la fuente única. Cada app implementa un intérprete de esas reglas:
`src/scripts/validate.js` (web) y `desktop/src/equipate/validate.py` (escritorio).
Cambiar una regla en `core/` afecta a **ambas**.

## Notas

- Contraseñas con SHA-256 (no texto plano); sin SQLi (la web no usa SQL); sin XSS.
- El historial de facturas guarda el nombre del producto y no se borra en cascada.
- El sitio es 100% estático: sin backend ni base de datos remota (datos en IndexedDB).
- Mobile-first; idioma y tema con un solo botón cada uno.
