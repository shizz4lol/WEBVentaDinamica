# Mejoras respecto al original

Punto de partida: `a11fc78` (versión original). Cada fila indica el commit que respalda el cambio. Las fases corresponden al historial de esta carpeta; algunas decisiones visuales fueron reemplazadas en fases posteriores.

## Fase 1: arranque y base de datos

Commit `f226842`.

| Archivo | Cambio | Por qué |
| --- | --- | --- |
| `Dockerfile` | Copia Node 22 desde su imagen oficial y comprueba Node/npm. | Evita que una descarga fallida deje la imagen sin npm. |
| `compose.yaml`, `.env.example` | Instala dependencias al arrancar; elimina `env_file` de `app` y el montaje de inicialización de MariaDB; configura host `db` y URL con 8001. | Permite arrancar desde cero y que Laravel lea los cambios de `.env`. |
| `database/migrations/2026_09_27_033930_create_productos_table.php`, `app/Models/Producto.php` | Alinea relación con serie, precio decimal, tipo, stock e imagen. | Hace coincidir las columnas con los datos del catálogo. |
| `database/migrations/0001_01_01_000000_create_clientes_table.php`, `app/Models/Cliente.php` | DNI, teléfono y código postal como texto; elimina número de tarjeta. | Conserva datos que no son cantidades y evita guardar números de tarjeta. |
| `database/migrations/2026_09_30_225223_create_carritos_table.php`, `app/Models/Carrito.php`, `app/Models/CarritoItem.php` | Separa carrito e ítems con producto y cantidad. | Un carrito puede contener varios productos. |
| `database/migrations/2026_09_27_040037_create_favoritos_table.php`, `app/Models/Favorito.php` | Alinea claves de cliente/producto y evita favoritos repetidos. | Mantiene relaciones consistentes. |
| `database/migrations/2026_10_22_033556_create_compras_table.php`, `database/migrations/2026_10_22_033751_create_detalle_compras_table.php` | Corrige claves, importes decimales y restricciones de borrado. | Protege el historial de compras y los productos vendidos. |
| `app/Models/Administrador.php`, `database/migrations/2026_09_27_025018_create_administradores_table.php` | Alinea tabla y modelo autenticable de administradores. | Prepara la autenticación con Laravel. |
| `database/seeders/AdministradorSeeder.php`, `database/seeders/ClienteSeeder.php`, `database/seeders/SerieSeeder.php`, `database/seeders/ProductoSeeder.php`, `database/seeders/DatabaseSeeder.php` | Carga cuentas de prueba, seis series y diez productos iniciales con portadas. | Permite probar con datos relacionados. |
| `tests/Feature/BaseDeDatosTest.php` | Prueba relaciones, seeders, portadas y restricciones. | Detecta errores en la estructura de datos. |

El commit `3249741` registra la revisión de esta fase **sin cambiar archivos**. Deja asentado que se reconstruye la base original con `migrate:fresh --seed`; no se agregó una migración de actualización. Ese comando borra los datos, como explica el README.

## Fase 2: catálogo, login y carrito

Commit principal `cb20fd1`; corrección `aafc75a`.

| Archivo | Cambio | Por qué |
| --- | --- | --- |
| `app/Http/Controllers/CatalogoController.php`, `routes/web.php`, `resources/views/productos/buscar.blade.php`, `resources/views/productos/detalle.blade.php` | Catálogo desde la base, detalle y búsqueda por nombre o serie (`cb20fd1`). | Reemplaza contenido fijo por productos del proyecto. |
| `app/Http/Controllers/LoginController.php`, `config/auth.php`, `resources/views/login.blade.php` | Login/logout con guards `web` y `admin`, validación y renovación de sesión (`cb20fd1`). | Distingue clientes de administradores y controla el acceso. |
| `app/Http/Controllers/AdminController.php`, `resources/views/admin/panel.blade.php` | Panel protegido de solo lectura (`cb20fd1`). | Deja una base para el futuro CRUD. |
| `app/Http/Controllers/CarritoController.php`, `app/Models/Carrito.php`, `resources/views/carrito.blade.php` | Agrega/quita productos y calcula total; valida stock y dueño del carrito (`cb20fd1`). | Evita cantidades superiores al stock y cambios en carritos ajenos. |
| `app/Http/Controllers/ContactoController.php`, `resources/views/extra/contacto.blade.php` | Valida y confirma el contacto, sin enviar correo (`cb20fd1`). | Da respuesta al formulario. |
| `resources/css/app.css`, `resources/views/layouts/header.blade.php`, `resources/js/menu.js` | Paleta en Tailwind, grillas adaptables, menú móvil y foco visible (`cb20fd1`). | Mejora el uso en celulares y con teclado. |
| `resources/js/app.jsx`, `resources/js/components/ContadorCarrito.jsx`, `resources/views/layouts/app.blade.php` | Monta el contador React desde Blade e integra Vite (`cb20fd1`). | Da un ejemplo para incorporar React gradualmente. |
| `vite.config.js` | Activa consulta periódica de cambios (`cb20fd1`). | Detecta archivos modificados en discos de Windows montados en Docker. |
| `tests/Feature/CatalogoTest.php`, `tests/Feature/LoginTest.php`, `tests/Feature/CarritoTest.php`, `tests/Feature/ContactoTest.php`, `tests/TestCase.php` | Agrega pruebas funcionales y desactiva Vite en tests (`cb20fd1`). | Comprueba el servidor sin necesitar Vite. |
| `app/Providers/AppServiceProvider.php`, `routes/web.php`, `tests/Feature/LoginTest.php` | Separa los límites de intentos de login y contacto (`aafc75a`). | El contacto no consume intentos para iniciar sesión. |

## Fase 2b: registro, diseño y sobre nosotros

Commit `4fc2b49`.

| Archivo | Cambio | Por qué |
| --- | --- | --- |
| `app/Http/Controllers/RegistroController.php`, `resources/views/registro.blade.php`, `routes/web.php` | Registro con usuario/email únicos, confirmación de contraseña e ingreso automático. | Permite crear cuentas sin depender del seeder. |
| `database/migrations/0001_01_01_000000_create_clientes_table.php` | Apellido y DNI opcionales. | Reduce los datos pedidos al registrarse. |
| `resources/views/extra/sobre-nosotros.blade.php`, `resources/views/layouts/footer.blade.php` | Nueva página y enlace desde el pie. | Presenta la tienda y lleva a contacto. |
| `resources/css/app.css`, `resources/views/inicio.blade.php`, `resources/views/components/tarjeta-producto.blade.php`, `resources/views/productos/detalle.blade.php` | Presentación con portadas, efectos, señales de stock y ficha de producto. | Facilita reconocer productos y disponibilidad. |
| `resources/views/carrito.blade.php`, `resources/views/productos/buscar.blade.php`, `resources/views/layouts/header.blade.php` | Resumen lateral, estados vacíos orientativos y página actual marcada. | Ayuda a navegar y leer en pantallas chicas. |
| `tests/Feature/RegistroTest.php`, `tests/Feature/SobreNosotrosTest.php` | Prueba registro y página informativa. | Verifica las nuevas funciones. |

## Fase 2c: distribución del inicio, tipografía y corrección

Commits `50bc2dd`, `ebaf1ba` y `ebb34bf`.

| Archivo | Cambio | Por qué |
| --- | --- | --- |
| `resources/views/inicio.blade.php`, `resources/views/layouts/header.blade.php`, `resources/css/app.css` | Distribución inspirada en MANGA Plus con collage, destacado, novedades y ranking, conservando la paleta original (`50bc2dd`). | Organiza el catálogo por secciones. |
| `app/Http/Controllers/CatalogoController.php` | Prepara destacado, novedades, ranking por unidades vendidas y portadas (`50bc2dd`). | Alimenta las secciones desde la base. |
| `package.json`, `package-lock.json`, `resources/css/app.css`, `public/img/logo.png`, `public/img/logo-icono.png`, `resources/views/layouts/app.blade.php`, `resources/views/layouts/header.blade.php` | Incorpora Dela Gothic One, logo e icono (`ebaf1ba`). | Refuerza la identidad visual. |
| `app/Http/Controllers/CatalogoController.php` | Ordena novedades por fecha y luego ID descendentes en una sola operación (`ebb34bf`). | Evita que el segundo orden reemplace al primero. |

## Ampliaciones del catálogo

Commits `a3ee92c`, `3c22b67` y `f9b3182`.

| Archivo | Cambio | Por qué |
| --- | --- | --- |
| `database/seeders/ProductoSeeder.php`, `database/seeders/SerieSeeder.php`, `public/img/portadas/hunter-x-hunter-1.jpg`, `public/img/portadas/one-punch-man-1.jpg`, `public/img/portadas/shingeki-no-kyojin-1.jpg`, `public/img/portadas/one-punch-man-webcomic.jpg` | Agrega esos mangas y el webcomic de ONE (`a3ee92c`). | Amplía los datos y distingue las versiones de One-Punch Man. |
| `app/Http/Controllers/CatalogoController.php` | En el ranking, si hay empate de ventas, van primero los mangas (`a3ee92c`). | Ante empates, el ranking muestra mangas antes que cómics. |
| `app/Models/Producto.php`, `database/migrations/2026_09_27_033930_create_productos_table.php`, `database/seeders/ProductoSeeder.php`, `database/seeders/SerieSeeder.php`, `app/Http/Controllers/CatalogoController.php` | Agrega mangas y el campo opcional `orden`, usado para priorizar destacados (`3c22b67`). | Permite un orden editorial además del alfabético. |
| `public/img/portadas/FUENTES.md` | Documenta fuentes de portadas (`3c22b67`, ampliado en `f9b3182`). | Deja referencias de las imágenes usadas. |
| `database/seeders/ProductoSeeder.php`, `database/seeders/SerieSeeder.php` | Agrega títulos y las partes 2 a 9 de JoJo; ajusta su orden (`f9b3182`). | Completa la selección del catálogo. |
| `database/seeders/CompraSeeder.php`, `database/seeders/DatabaseSeeder.php`, `app/Http/Controllers/CatalogoController.php`, `tests/Feature/CatalogoTest.php` | Carga compras de ejemplo, define una selección editorial de destacados y prueba el ranking (`f9b3182`). | El ranking muestra ventas simuladas y los destacados se eligen por separado. |

## Carrusel de destacados

Commit `9c932d7`.

| Archivo | Cambio | Por qué |
| --- | --- | --- |
| `resources/js/carrusel.js`, `resources/js/app.jsx`, `resources/views/inicio.blade.php`, `resources/css/app.css` | Agrega e importa el carrusel de banners del inicio. | Permite recorrer varios destacados en el mismo espacio. |
| `public/img/banners/hunter-x-hunter.jpg`, `public/img/banners/jojos.jpg`, `public/img/banners/shingeki-no-kyojin.jpg`, `public/img/banners/FUENTES.md` | Agrega imágenes y referencias para los banners. | El carrusel dispone de recursos documentados. |

Para consultar una fase, ejecutá `git show --stat` seguido del identificador del commit. Con `git show` y ese identificador podés leer el cambio completo.
