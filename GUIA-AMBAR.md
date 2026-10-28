# Guía para seguir mejorando Venta Dinámica

Esta guía tiene 4 tareas, cada una con un prompt listo para Claude Free (el chat en el navegador). Al final hay prompts para las mismas tareas pensados para Claude Code, por si activás el pase de invitado.

## Dos reglas

1. Nunca le pidas a Claude que suba nada a GitHub. El `git push` lo hacés vos, a mano.
2. Nunca le pidas a Claude que lea ni que toque el `.env`, y no lo adjuntes en el chat.

## Cómo usar los prompts con Claude Free

Claude en el navegador no ve tu proyecto: tenés que adjuntar los archivos a mano desde esta carpeta. Si no entran todos juntos, mandalos en varios mensajes y pedile que espere hasta que le avises que terminaste.

En cada tarea adjuntá esta base común, más los archivos que se indican en la tarea:

- `composer.json`
- `package.json`
- `vite.config.js`
- `resources/css/app.css`
- `resources/views/layouts/app.blade.php`
- `phpunit.xml`
- `tests/TestCase.php`

Para correr los tests, desde PowerShell en la carpeta del proyecto:

```powershell
docker compose exec app php artisan test
```

Ojo: `php artisan migrate:fresh --seed` borra todos los datos de la base (ver el README).

## 1. Componentes React del catálogo

**Objetivo:** pasar las tarjetas y el listado de productos a React, montándolos desde Blade igual que el contador del carrito. Laravel sigue entregando los datos y las rutas.

**Archivos a adjuntar:** la base común y estos archivos:

- `resources/js/app.jsx`
- `resources/js/components/ContadorCarrito.jsx`
- `resources/js/carrusel.js`
- `resources/views/components/tarjeta-producto.blade.php`
- `resources/views/inicio.blade.php`
- `resources/views/productos/buscar.blade.php`
- `resources/views/layouts/header.blade.php`
- `app/Http/Controllers/CatalogoController.php`
- `app/Http/Controllers/CarritoController.php`
- `app/Models/Producto.php`
- `routes/web.php`
- `tests/Feature/CatalogoTest.php`

**Prompt para copiar:**

```text
Estoy aprendiendo y quiero convertir las tarjetas y el listado del catálogo de Venta Dinámica en componentes React reutilizables, montados desde Blade con createRoot en resources/js/app.jsx, siguiendo el ejemplo de ContadorCarrito. Te adjunto los archivos actuales.

Mantené los nombres en español, la paleta existente y el stack Laravel 13 + Blade + React 19 + Vite + Tailwind 4 + MariaDB. No agregues dependencias. No conviertas el sitio en una SPA ni inventes archivos existentes: si necesitás más contexto, pedime el archivo.

Empezá por inicio y búsqueda. Conservá los datos de Laravel, los órdenes de mangas/novedades/ranking, el carrusel, los enlaces, los precios en pesos, las señales de stock, las imágenes opcionales y los estados vacíos. Pasá los datos y las URLs desde Blade con serialización segura; no expongas información de usuarios. Conservá formularios POST para agregar al carrito con token CSRF y la validación del servidor. Evitá que Blade y React dibujen dos veces las mismas tarjetas. No rompas el contador.

Explicame props, montaje y paso de datos con ejemplos del proyecto. Separá archivos existentes a modificar de archivos nuevos que proponés crear. Dame pasos pequeños y el código necesario, con su ubicación. Revisá qué pruebas del catálogo deben adaptarse: una prueba HTTP no ejecuta React. Mantené las comprobaciones del servidor y explicame qué debo verificar a mano en el navegador.
```

**Cómo probar:** abrí el inicio y buscá Naruto; comprobá nombres, portadas, precios, enlaces y orden de las secciones. Probá una búsqueda sin resultados. Revisá un producto sin stock si hay uno disponible. Entrá como cliente, agregá un producto y verificá carrito y contador. Sin sesión, agregar debe pedir login. Revisá en pantalla angosta, con teclado y con la consola del navegador abierta: no debería haber errores de React. Corré los tests.

## 2. CRUD de productos en el panel de administrador

**Objetivo:** permitir crear, ver, editar y borrar productos desde el panel, hoy de solo lectura, con acceso exclusivo de administradores.

**Archivos a adjuntar:** la base común y estos archivos:

- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/LoginController.php`
- `app/Models/Administrador.php`
- `app/Models/Producto.php`
- `app/Models/Serie.php`
- `app/Models/DetalleCompra.php`
- `resources/views/admin/panel.blade.php`
- `resources/views/layouts/header.blade.php`
- `resources/views/layouts/mensajes.blade.php`
- `routes/web.php`
- `config/auth.php`
- `database/migrations/2026_09_27_033930_create_productos_table.php`
- `database/migrations/2026_10_22_033751_create_detalle_compras_table.php`
- `tests/Feature/LoginTest.php`
- `tests/Feature/BaseDeDatosTest.php`

**Prompt para copiar:**

```text
Quiero completar el CRUD de productos del panel de Venta Dinámica. Hoy AdminController solo lista productos. Usá los archivos adjuntos como fuente y pedime cualquier otro que necesites.

Mantené nombres en español, la paleta y el stack Laravel 13 + Blade + React 19 + Vite + Tailwind 4 + MariaDB. No agregues dependencias. Trabajá con AdminController y el grupo existente de routes/web.php protegido por auth:admin; el guard web es para clientes y no debe dar acceso a ninguna operación de administración.

Agregá formularios Blade para crear y editar productos con los campos reales del modelo y de la migración, selector de serie opcional y validación del servidor. Conservá CSRF, métodos HTTP apropiados, mensajes en español y los datos ingresados cuando hay errores. Para imagen usá una ruta de portada existente, sin implementar subidas en esta tarea. Permití borrar con confirmación, pero si el producto tiene detalle de compras, mostrá un mensaje claro y conservá el historial: no elimines las restricciones ni borres compras para permitirlo.

Explicame cada paso y la diferencia entre autenticación y autorización. Distinguí archivos existentes a modificar de nuevos a crear. Incluí pruebas de acceso sin sesión, como cliente y como admin, validación, creación, edición, borrado y rechazo del borrado de un producto vendido. No reconstruyas la base ni cambies migraciones existentes para resolver el CRUD.
```

**Cómo probar:** sin sesión y después como cliente, intentá entrar al panel y comprobá que no podés administrarlo. Como admin, creá un producto de prueba usando una portada existente, editá precio y stock y mirá el resultado en el catálogo. Probá precio negativo y nombre vacío: deben aparecer errores. Borrá el producto de prueba; intentá borrar uno con compras de ejemplo y verificá que se conserve con un mensaje claro. Corré los tests, incluidos los nuevos controles de permisos para cada operación.

## 3. Favoritos

**Objetivo:** que cada cliente pueda marcar, listar y quitar sus propios favoritos. La tabla y el modelo ya existen; faltan rutas, acciones y la parte visible.

**Archivos a adjuntar:** la base común y estos archivos:

- `app/Models/Favorito.php`
- `app/Models/Cliente.php`
- `app/Models/Producto.php`
- `database/migrations/2026_09_27_040037_create_favoritos_table.php`
- `app/Http/Controllers/CatalogoController.php`
- `app/Http/Controllers/CarritoController.php`
- `resources/views/components/tarjeta-producto.blade.php`
- `resources/views/productos/detalle.blade.php`
- `resources/views/layouts/header.blade.php`
- `resources/views/layouts/mensajes.blade.php`
- `routes/web.php`
- `config/auth.php`
- `tests/Feature/BaseDeDatosTest.php`
- `tests/Feature/CarritoTest.php`

Si ya hiciste la tarea 1, adjuntá además los componentes React que creaste y las versiones actuales de `resources/js/app.jsx`, `resources/views/inicio.blade.php` y `resources/views/productos/buscar.blade.php`. Las tarjetas Blade pueden haber sido reemplazadas.

**Prompt para copiar:**

```text
Quiero implementar la parte visible de favoritos en Venta Dinámica. Ya existen Favorito, las relaciones con Cliente y Producto y la tabla favoritos con la combinación cliente_id/producto_id única. Revisá los adjuntos y reutilizá esa estructura, sin crear otra tabla ni cambiar las migraciones existentes.

Mantené nombres en español, la paleta y el stack Laravel 13 + Blade + React 19 + Vite + Tailwind 4 + MariaDB. No agregues dependencias. Respetá la implementación actual de las tarjetas: si son React, integrá los controles ahí; si siguen en Blade, usá Blade. Pedime los archivos que falten.

Quiero marcar y desmarcar desde catálogo y detalle, un acceso desde el header y una lista de mis favoritos con estado vacío. Protegé las operaciones con auth:web y CSRF. Obtené el cliente desde la sesión; no confíes en un cliente_id enviado por el navegador. No permitas favoritos duplicados ni quitar los de otra persona. Las modificaciones no deben usar GET. Sin sesión, explicá cómo iniciar sesión para usar favoritos. Conservá enlaces al producto y acciones del carrito.

Explicame las relaciones y la autorización con ejemplos. Indicá archivos existentes a cambiar y archivos nuevos a crear, con pasos y código. Incluí tests de login requerido, agregar, repetir sin duplicar, listar solo los propios y quitar sin afectar a otro cliente. No reinicies la base.
```

**Cómo probar:** como cliente, marcá dos productos desde catálogo y detalle, abrí la lista y quitá uno. Repetí una marca y comprobá que no se duplique. Cerrá sesión y volvé a entrar: la selección debe persistir. Creá otra cuenta desde registro y comprobá que su lista esté vacía. Sin sesión, el control debe orientar al login y el servidor debe rechazar las modificaciones. Corré los tests, incluida la protección de favoritos ajenos.

## 4. Cambiar la cantidad de un producto en el carrito

**Objetivo:** editar la cantidad de un ítem existente desde el carrito. Hoy se puede sumar al agregar y quitar un ítem completo, pero no hay un control para establecer otra cantidad.

**Archivos a adjuntar:** la base común y estos archivos:

- `app/Http/Controllers/CarritoController.php`
- `app/Models/Carrito.php`
- `app/Models/CarritoItem.php`
- `app/Models/Producto.php`
- `database/migrations/2026_09_30_225223_create_carritos_table.php`
- `resources/views/carrito.blade.php`
- `resources/views/layouts/header.blade.php`
- `resources/views/layouts/mensajes.blade.php`
- `resources/js/app.jsx`
- `resources/js/components/ContadorCarrito.jsx`
- `routes/web.php`
- `tests/Feature/CarritoTest.php`

**Prompt para copiar:**

```text
Quiero editar la cantidad de un producto que ya está en mi carrito de Venta Dinámica. Actualmente CarritoController permite ver, agregar y quitar; agregar acumula unidades. Quiero un campo de cantidad y botón Actualizar por ítem, con formularios Blade y recarga normal.

Mantené nombres en español, la paleta y el stack Laravel 13 + Blade + React 19 + Vite + Tailwind 4 + MariaDB. No agregues dependencias. Usá las rutas existentes como referencia y agregá una operación de actualización protegida por auth:web, CSRF y un método HTTP apropiado.

La cantidad nueva reemplaza a la anterior, no se suma. Validala en el servidor como entero de 1 a 99 y no mayor al stock actual. Cero no borra: para eso queda Quitar. Verificá que el ítem pertenezca al cliente autenticado y mantené el criterio de transacción y bloqueo del producto que ya usa agregar. No reduzcas stock por editar el carrito ni aceptes precios del navegador. Si baja el stock, mostrá el error y permití corregir la cantidad. Conservá el carrito si la entrada es inválida.

Después de actualizar, subtotal, total, resumen y contador del header deben reflejar la suma de unidades. Aprovechá la recarga del formulario y el montaje existente del contador. Explicame el recorrido desde el formulario hasta el modelo. Distinguí archivos existentes a modificar de nuevos que propongas. Agregá tests de reemplazo, límites, stock insuficiente, valores inválidos, usuario sin sesión y carrito ajeno. No reconstruyas la base y pedime cualquier archivo que falte.
```

**Cómo probar:** como cliente, agregá un producto con stock suficiente. Cambiá su cantidad de 1 a 2 y luego a 1: no debe acumular. Revisá subtotal, total, resumen y contador después de cada envío. Probá vacío, cero, negativo, decimal, texto, más de 99 y más que el stock: deben rechazarse sin cambiar la cantidad guardada. Desde las pruebas del servidor verificá también que otro cliente no pueda actualizar ese ítem. Confirmá que Quitar siga funcionando y corré todos los tests.

## Si activás la semana gratis de Claude Code (pase de invitado)

### Qué es

Son 7 días del plan Pro, que incluye Claude Code: un Claude que trabaja en la terminal, adentro de la carpeta del proyecto, y que lee los archivos y corre los comandos solo (no hace falta adjuntar nada).

Requisitos: una cuenta que nunca haya pagado, ser mayor de 18 años y cargar una tarjeta. **El día 7 se cobra solo.** Si no querés seguir, poné un recordatorio para cancelar el día 6 en Configuración → Facturación.

### Antes de activarla

Tené Docker Desktop andando, el proyecto levantado (ver el README) y un commit limpio (`git status --short` no muestra nada). Así los 7 días los usás programando y no configurando.

### Instalación en Windows

1. Instalá Claude Code con el instalador oficial. En PowerShell:

   ```powershell
   irm https://claude.ai/install.ps1 | iex
   ```

   En Windows, Claude Code necesita Git for Windows, que ya instalaste para el proyecto.

2. Abrí una PowerShell nueva en la carpeta del proyecto (donde está `compose.yaml`) y ejecutá:

   ```powershell
   claude
   ```

3. La primera vez te pide iniciar sesión: entrá con tu cuenta de Claude.

Las dos reglas de arriba valen igual: el push lo hacés vos y el `.env` no se toca.

### Prompts para Claude Code

Copiá el de la tarea que quieras hacer. Todos le piden lo mismo: leer los archivos, proponer un plan y esperar tu OK, hacer el cambio, correr los tests, mostrarte el resultado y explicarte qué hizo.

**1. Componentes React del catálogo**

```text
Estoy aprendiendo y quiero convertir las tarjetas y el listado del catálogo de Venta Dinámica en componentes React reutilizables, montados desde Blade con createRoot en resources/js/app.jsx, como ya se hace con ContadorCarrito.

Primero leé resources/js/app.jsx, resources/js/components/ContadorCarrito.jsx, resources/js/carrusel.js, resources/views/components/tarjeta-producto.blade.php, resources/views/inicio.blade.php, resources/views/productos/buscar.blade.php, app/Http/Controllers/CatalogoController.php, routes/web.php y tests/Feature/CatalogoTest.php. Después proponeme un plan corto y esperá mi OK antes de cambiar nada.

Mantené los nombres en español, la paleta de resources/css/app.css y el stack actual. No agregues dependencias. Conservá el orden de las secciones, el carrusel, los precios, las señales de stock, los formularios POST con CSRF para agregar al carrito y el contador. Pasá los datos desde Blade de forma segura y evitá que Blade y React dibujen dos veces las mismas tarjetas.

Cuando termines, corré docker compose exec app php artisan test, mostrame el resultado y explicame en 5 líneas qué hiciste y por qué. No leas ni modifiques el .env y no hagas push.
```

**2. CRUD de productos en el panel de administrador**

```text
Quiero completar el CRUD de productos del panel de Venta Dinámica: hoy AdminController solo lista productos.

Primero leé app/Http/Controllers/AdminController.php, app/Models/Producto.php, app/Models/Serie.php, resources/views/admin/panel.blade.php, routes/web.php, config/auth.php, la migración database/migrations/2026_09_27_033930_create_productos_table.php y tests/Feature/LoginTest.php. Después proponeme un plan corto y esperá mi OK antes de cambiar nada.

Mantené los nombres en español, la paleta y el stack actual. No agregues dependencias. Todo va dentro del grupo auth:admin de routes/web.php: un cliente logueado no tiene que poder administrar nada. Formularios Blade con CSRF y validación en el servidor; para la imagen usá una ruta de portada existente, sin subir archivos. Si un producto tiene compras, no se borra: mostrá un mensaje claro y no cambies las restricciones ni las migraciones existentes. Agregá tests de acceso (sin sesión, cliente y admin), validación, crear, editar y borrar.

Cuando termines, corré docker compose exec app php artisan test, mostrame el resultado y explicame en 5 líneas qué hiciste y por qué. No leas ni modifiques el .env y no hagas push.
```

**3. Favoritos**

```text
Quiero implementar la parte visible de favoritos en Venta Dinámica. El modelo Favorito y la tabla favoritos (con cliente_id y producto_id únicos) ya existen.

Primero leé app/Models/Favorito.php, app/Models/Cliente.php, app/Models/Producto.php, database/migrations/2026_09_27_040037_create_favoritos_table.php, resources/views/components/tarjeta-producto.blade.php, resources/views/productos/detalle.blade.php, resources/views/layouts/header.blade.php, routes/web.php y tests/Feature/CarritoTest.php (como ejemplo de tests con sesión). Si las tarjetas ya están en React, leé también esos componentes. Después proponeme un plan corto y esperá mi OK antes de cambiar nada.

Mantené los nombres en español, la paleta y el stack actual. No agregues dependencias ni cambies las migraciones. Quiero marcar y desmarcar desde el catálogo y el detalle, un acceso en el header y una página con mis favoritos (con estado vacío). Protegé todo con auth:web y CSRF, tomá el cliente de la sesión, sin duplicados y sin poder tocar los favoritos de otra persona. Agregá los tests correspondientes.

Cuando termines, corré docker compose exec app php artisan test, mostrame el resultado y explicame en 5 líneas qué hiciste y por qué. No leas ni modifiques el .env y no hagas push.
```

**4. Cambiar la cantidad de un producto en el carrito**

```text
Quiero poder cambiar la cantidad de un producto que ya está en el carrito de Venta Dinámica. Hoy CarritoController permite ver, agregar (que suma unidades) y quitar.

Primero leé app/Http/Controllers/CarritoController.php, app/Models/Carrito.php, app/Models/CarritoItem.php, app/Models/Producto.php, resources/views/carrito.blade.php, resources/js/components/ContadorCarrito.jsx, routes/web.php y tests/Feature/CarritoTest.php. Después proponeme un plan corto y esperá mi OK antes de cambiar nada.

Mantené los nombres en español, la paleta y el stack actual. No agregues dependencias. Quiero un campo de cantidad y un botón Actualizar por ítem, con un formulario Blade y una ruta nueva dentro de auth:web, con CSRF. La cantidad nueva reemplaza a la anterior; validala en el servidor (entero de 1 a 99 y no mayor al stock) y verificá que el ítem sea del cliente logueado, con la misma transacción y bloqueo que usa agregar. Después de actualizar, el total y el contador del header tienen que coincidir. Agregá tests de reemplazo, límites, stock insuficiente, valores inválidos, sin sesión y carrito ajeno.

Cuando termines, corré docker compose exec app php artisan test, mostrame el resultado y explicame en 5 líneas qué hiciste y por qué. No leas ni modifiques el .env y no hagas push.
```
