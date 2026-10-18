# CONTEXT — Equipate Full / Guayuco Digital

> Archivo de contexto del proyecto. Debe mantenerse siempre completo y actualizado.

## 1. Qué es el proyecto

Aplicación web académica (PHP + MySQL/MariaDB) tipo **tienda en línea**.
Creada aproximadamente en 2020 (según el volcado SQL: 28-01-2020), en su mayoría
escrita a mano por el autor como proyecto universitario.

- Nombre del sitio: **Equipate Full**
- Nombre interno / base de datos: **guayuco_digital**
- Autores/usuarios de prueba: Felix Añez (admin), Nico Gonzalez (admin),
  Joselin Castillo, Daniel Tornillo, Leslie Betancor (clientes).
- Moneda mostrada: `bsS` (bolívares).
- No es un repositorio Git (no hay `.git`).

## 2. Stack técnico

- Lenguaje: PHP (procedimental, sin frameworks, sin Composer).
- Base de datos: MySQL/MariaDB vía `mysqli` (API procedimental/orientada a objetos mixta).
- Frontend: HTML5, CSS propio + un Bootstrap personalizado, algo de JavaScript "vanilla".
- Sesiones PHP para el carrito y la autenticación.
- Servidor de desarrollo histórico: XAMPP/WAMP (`localhost`, phpMyAdmin).

## 3. Estructura de archivos

```
/
├── index.html                  Página índice (enlaces a login y phpMyAdmin)
├── login.php                   Formulario de inicio de sesión
├── registrarse.php             Alta de cliente
├── recuperar_contrasena.php    Recuperación paso 1 (usuario/correo)
├── recuperar_contrasena2.php   Recuperación paso 2 (nueva contraseña)
├── mainpage.php                Página principal del cliente (lista de productos)
├── ver_carrito.php             Carrito de compras
├── ver_facturas.php            Listado de facturas del cliente
├── ver_facturas_detallado.php  Detalle de una factura
├── admin.php                   Panel de administrador
├── admin-usuario.php           Alta/edición de usuarios (admin)
├── admin-producto.php          Alta/edición de productos (admin)
├── lista_de_usuarios.php       Listado de usuarios (admin)
├── lista_de_productos.php      Listado de productos (admin)
├── borrar.php                  Borrado de usuario/producto (por GET)
├── conexion.php                Datos de conexión a MySQL
├── background1.jpg             Imagen de fondo
├── README.txt                  "Coming soon..." (vacío)
├── bd/
│   └── guayuco_digital.sql     Volcado de la base de datos
├── css/
│   ├── bootstrapguayuco.css    Bootstrap personalizado
│   ├── bootstrapguayuco.css.map
│   ├── guayucostyles.css       Estilos propios + paleta de colores
│   └── paleta_colores.txt      Notas de la paleta
├── funciones/                  Funciones reutilizables (helpers)
│   ├── adminFunciones.php
│   ├── funcionesListaProductos.php
│   ├── funcionesListaUsuarios.php
│   ├── mostrarProductosMainpage.php
│   ├── sesionActiva.php        Redirige a mainpage si hay sesión
│   ├── sesionInactiva.php      Redirige a cerrar_sesion si no hay sesión
│   ├── verCarrito.php
│   ├── verFacturas.php
│   ├── verFacturaDetallado.php
│   ├── verPrecioTotal.php
│   ├── recuperar_contrasena/
│   │   ├── idCliente.php
│   │   └── mostrarError.php
│   └── validacion_login/
│       ├── mostrarError.php
│       ├── mostrarExito.php
│       └── sesionActiva.php
├── javascript/
│   ├── formLogin.js
│   ├── validacion_registro_usuario.js
│   ├── validacion_modificacion_usuario.js
│   ├── validacion_registro_producto.js
│   └── validacion_modificacion_producto.js
└── procesos/                   Controladores de acciones (POST/GET)
    ├── login_proceso.php
    ├── registrarse_proceso.php
    ├── admin_proceso.php
    ├── producto_proceso.php
    ├── carrito.php
    ├── compra_proceso.php
    ├── vaciar_carrito.php
    ├── cerrar_sesion.php
    ├── recuperar_contrasena_proceso.php
    └── recuperar_contrasena2_proceso.php
```

## 4. Base de datos (`guayuco_digital`)

Tablas (InnoDB, utf8):

- `usuarios` — id_cliente PK, nombre, apellido, usuario, correo, contrasena,
  nivel (`admin`/`cliente`), pregunta_secreta, respuesta_secreta.
- `producto` — id_producto PK, nombre, stock, precio.
- `factura` — id_factura PK, id_cliente FK→usuarios, fecha (varchar), preciototal.
- `detalle` — id_detalle PK, id_factura FK→factura, id_producto FK→producto,
  cantidad, precio. FKs con ON DELETE CASCADE.

Datos semilla: 5 usuarios, 5 productos, 8 facturas, 9 detalles.

## 5. Flujo funcional

1. **Login** (`login.php` → `procesos/login_proceso.php`): valida usuario y
   contraseña; según `nivel` redirige a `mainpage.php` (cliente) o `admin.php`.
2. **Registro** (`registrarse.php` → `procesos/registrarse_proceso.php`).
3. **Carrito**: `mainpage.php` lista productos; `procesos/carrito.php` agrega al
   carrito guardado en `$_SESSION["carrito"]` (máx. 5 posiciones, con cantidad).
   `ver_carrito.php` muestra y calcula total; `vaciar_carrito.php` lo limpia.
4. **Compra** (`procesos/compra_proceso.php`): crea `factura` + `detalle`, descuenta
   stock y redirige a la factura detallada.
5. **Facturas**: `ver_facturas.php` lista las del cliente; detalle en
   `ver_facturas_detallado.php`.
6. **Admin**: panel `admin.php` → alta/edición de usuarios y productos,
   listados y borrado (`borrar.php`).
7. **Recuperar contraseña**: por usuario o correo + pregunta secreta.

## 6. Problemas conocidos (detectados en la revisión)

### Seguridad (crítico)
- **Inyección SQL** en prácticamente todas las consultas: concatenación directa de
  `$_POST`/`$_REQUEST` sin `prepared statements` ni escapado.
- **Contraseñas en texto plano**: se guardan y comparan sin `password_hash()`/`verify`.
- **Sin control de rol**: `admin.php`, `admin-usuario.php`, `admin-producto.php`,
  `lista_de_*`, `borrar.php` y los procesos de admin sólo comprueban que exista
  sesión, no que el `nivel` sea `admin`. Cualquier cliente puede entrar.
- **XSS almacenado**: los campos de la BD se imprimen sin `htmlspecialchars()`.
- **CSRF / acciones por GET**: borrados y cambios de carrito vía enlaces GET sin token.
- **Credenciales en claro** en `conexion.php` (`guayuco`/`digital`).

### Errores funcionales / de lógica
- `ver_facturas_detallado.php`: la condición de redirección está invertida; con
  `id_factura` presente redirige a `mainpage.php`, por lo que el detalle es inaccesible.
- `procesos/compra_proceso.php`: el INSERT de `factura` está **dentro** del bucle de
  items; con más de un producto falla (PK duplicada) sin comprobar el error.
- `funciones/verPrecioTotal.php`: usa `Location(...)` (función inexistente) en lugar
  de `header(...)`.
- `borrar.php`: incluye `sesionActiva()`, que expulsa a mainpage a cualquier usuario
  con sesión, dejando el borrado inservible.
- Redirecciones relativas incorrectas (`mainpage.php`, `procesos/cerrar_sesion.php`)
  cuando se ejecutan desde `procesos/` → rutas rotas.
- Límites de stock/precio **hardcodeados** (stock 7–82, precio 10.000–7.000.000).
- Carrito limitado a 5 productos; `array_values()` sin reasignar provoca huecos.
- Contraseñas obligadas a ser **únicas entre todos los usuarios**.

### Errores en el frontend
- Ficheros JS **referenciados pero inexistentes**: `javascript/SmoothScroll.min.js`,
  `javascript/lowerCase.js`, `javascript/letras_minusculas.js`,
  `javascript/letras_minusculas_recuperar_contrasena.js`.
- Nombres de formulario que no coinciden con los que espera el JS:
  `admin-usuario.php` usa `regUser`/`modUser` pero el JS busca
  `registro_usuario`/`modificacion_usuario` → validación rota.
- `validacion_*_producto.js` lee un campo `imagen` que no existe → error JS.
- `index.html`: `<meta>` fuera de `<head>`, `<head>` sin cerrar.

### Calidad de código
- Duplicación masiva de validaciones (PHP + JS + admin + registro).
- Uso de `=` (asignación) en lugar de `==` en condiciones (`producto_proceso.php`,
  `admin_proceso.php`).
- Sin manejo de errores: `fetch_assoc()` sobre resultados fallidos.
- Nombres de función duplicados entre `funciones/` (p. ej. `sesionActiva`,
  `mostrarErrorExito`) con lógicas distintas.
- Sin separación de capas (lógica, presentación y datos mezclados en cada archivo).
- Sin tests, sin Composer, sin `.gitignore`, sin documentación (README vacío).
- Encoding: en el volcado se ve `AÃ±ez` (UTF-8 mal interpretado).

## 7. Fortalezas

- Funcionalidad completa de extremo a extremo (login, catálogo, carrito, compra,
  facturación, administración, recuperación de contraseña).
- Separación básica razonable: `procesos/` (acciones), `funciones/` (helpers),
  `css/`, `javascript/`.
- Validaciones en cliente **y** en servidor (aunque duplicadas).
- Uso de sesiones, FKs en la BD y una paleta de colores definida.
- Código legible, con comentarios, coherente para alguien que aprendió por su cuenta.

## 8. Reglas del proyecto (aplican a todo texto y a cada respuesta)

1. **Contexto siempre completo y actualizado**: `CONTEXT.md` se mantiene al día en
   cada cambio relevante (estructura, decisiones, fases).
2. **Respuestas cortas y precisas**: bien explicadas pero simples, sin relleno.

## 9. Plan de reconstrucción (v2)

**Meta**: rehacer la app como **dos clientes standalone** (web + escritorio Windows)
que parten de **una misma estructura base**.

**Decisiones**:
- **Se abandona PHP + MySQL**. Causa raíz de los problemas de seguridad (SQLi,
  sesiones, texto plano) y no cumple el requisito "standalone".
- **Fuente única de verdad** en `core/` (JSON, agnóstico de lenguaje): esquema de
  datos, reglas de negocio, textos i18n y tokens de diseño. Ambos clientes la leen.
- **Web**: *inicialmente* Astro 5 + Tailwind v4. **Decisión final**: HTML + ES
  modules **sin build** (cero dependencias, 100% standalone), datos en **IndexedDB**,
  sin backend. Astro queda como envoltorio opcional (ver §10).
- **Escritorio**: Python 3.14 + Tkinter/ttk, **PyInstaller** single-file, datos en
  **SQLite**.
- Mismo **modelo de datos**, mismas **reglas**, mismas **claves i18n (ES/EN)**,
  misma **paleta**.

**Estructura monorepo**:
```
equipate-full/
├── core/                 # fuente única (lenguaje-agnóstica)
│   ├── schema.json       # entidades: usuarios, productos, facturas, detalle, carrito
│   ├── rules.json        # validaciones y reglas de negocio
│   ├── i18n/es.json en.json
│   ├── tokens.json       # colores, espaciados, tipografía
│   └── cases/            # fixtures para el self-test de paridad
├── apps/web/             # Astro 5 + Tailwind v4 (estático, IndexedDB)
├── apps/desktop/         # Python 3.14 + Tkinter (SQLite, PyInstaller)
└── docs/
```

**Módulos del dominio (idénticos en ambos clientes)**:
auth · catálogo · carrito · checkout · facturas · admin · i18n · tema.

**Fases**:
0. Definir contrato `core/` (schema, rules, i18n, tokens).
1. Web: esqueleto Astro + layout + i18n + tema.
2. Web: dominio + persistencia IndexedDB + seed.
3. Escritorio: esqueleto Tkinter + ttk + i18n + tema.
4. Escritorio: dominio + SQLite + seed.
5. Paridad: mismo seed y `core/cases/` como self-test en ambos.
6. Packaging: Cloudflare Pages (web) / PyInstaller + firma (escritorio).

**Reuso de stacks previos**:
- Web: tokens CSS-var, reveal, patrón i18n (`ui.ts`/`routes.ts`), formularios con
  validación inline. Export: CSV / impresión.
- Escritorio: `STRINGS` + `t()`, tema ttk en runtime, DPI-aware, worker thread,
  `openpyxl` / `python-docx` para exportar facturas, self-test harness.

**Opcional (futuro)**: sincronización multi-dispositivo vía Cloudflare Pages
Functions + D1 (sin romper el modo standalone).

## 10. Estado de implementación (Fases 0–5 completadas)

**Desviación del plan**: la web se hizo **sin build** (HTML + ES modules puros) en
lugar de Astro. Motivo: cero dependencias, arranque inmediato y 100% standalone.
Astro queda como envoltorio opcional para más adelante; la estructura de datos y
reglas no cambia.

**Hecho**:

| Fase | Resultado |
|---|---|
| 0. Contrato `core/` | `schema.json`, `rules.json`, `i18n/es|en.json`, `tokens.json`, `seed.json`, `cases/validation.json` |
| 1–2. Web | `apps/web/` (index.html + `src/*.js` + styles), IndexedDB, sin servidor de app |
| 3–4. Escritorio | `apps/desktop/` (Tkinter/ttk, SQLite, DPI-aware, temas e idioma en runtime) |
| 5. Paridad | `core/cases/` ejecutado por ambos: **26/26 en web y 26/26 en escritorio** |

**Verificado en navegador (flujo real)**: login → catálogo → carrito (cantidad 2) →
compra → factura `8,600 bsS` → detalle con producto y cantidad; stock 61→59; cambio
de tema (dark/light) e idioma (ES/EN); alta y baja de producto desde admin.

**Arquitectura de paridad**:
- Reglas declarativas en `core/rules.json`.
- Intérpretes espejo: `apps/web/src/validate.js` ↔
  `apps/desktop/src/equipate/validate.py`.
- Persistencia espejo: `apps/web/src/store.js` (IndexedDB) ↔
  `apps/desktop/src/equipate/store.py` (SQLite).

**Mejoras frente a v1**: contraseñas SHA-256, sin SQLi (no hay SQL en la web),
control de rol en admin, `detalle` guarda `nombre_producto` y no se borra en
cascada, validaciones sin duplicar, sin XSS (todo escapado).

**Pendiente / siguiente iteración**:
- Escritorio: recuperación de contraseña, edición de usuarios/productos en UI, export
  a Excel/Word (openpyxl/python-docx), `run.cmd` con `.spec` de PyInstaller + firma.
- Web: registro y recuperación de contraseña, página de detalle de producto,
  validación inline accesible (aria-invalid), envoltorio Astro opcional.
- Cobertura: pruebas del `store` web (IndexedDB) fuera del navegador.
- Los archivos PHP de la raíz son la **v1** (referencia); no forman parte de la v2.

## 11. Registro de cambios de contexto

- 2026-10-18: Análisis completo del proyecto original.
- 2026-10-18: Añadidas reglas del proyecto y plan de reconstrucción v2.
- 2026-10-18: Implementadas Fases 0–5 (core + web + escritorio + paridad),
  README, `.gitignore`; self-tests 26/26 en ambos clientes.
