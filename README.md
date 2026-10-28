# Venta Dinámica

Tienda online de mangas y cómics. El original está en el commit `a11fc78`. Consultá también [MEJORAS.md](MEJORAS.md) y [GUIA-AMBAR.md](GUIA-AMBAR.md).

Usa Laravel 13, Blade, React 19, Vite, Tailwind 4 y MariaDB 11.4. Docker ejecuta `app` (Laravel), `vite` (recursos del navegador) y `db` (base de datos).

## Requisitos

- Windows con PowerShell.
- Docker Desktop instalado, abierto y configurado para contenedores Linux.
- Git instalado para guardar cambios y consultar el historial.
- Internet para descargar las imágenes de Docker y las dependencias la primera vez.

No necesitás instalar PHP, Composer ni Node en Windows: están dentro de Docker.

## Instalación desde cero

Abrí PowerShell en la carpeta del proyecto, donde está `compose.yaml`. Todos los comandos de esta guía se ejecutan desde ahí.

1. Abrí Docker Desktop, esperá a que termine de iniciar y comprobá las herramientas:

   ```powershell
   docker version
   docker compose version
   git --version
   ```

2. Creá tu configuración local una sola vez. Si ya tenés `.env`, conservá ese archivo:

   ```powershell
   copy .env.example .env
   ```

   Las contraseñas de `.env.example` son de prueba. Si querés cambiar `DB_PASSWORD` o `DB_ROOT_PASSWORD`, hacelo antes del primer arranque: después la base ya quedó creada con las anteriores. Conservá `DB_HOST=db` y `DB_PORT=3306`: Laravel usa la red interna de Docker. No subas `.env` a Git.

3. Construí y levantá los servicios:

   ```powershell
   docker compose up -d --build
   docker compose ps
   docker compose logs -f app vite db
   ```

   La primera vez tarda: `app` ejecuta `composer install` y `vite` ejecuta `npm install` automáticamente. Puede tardar varios minutos. Está listo cuando en los logs aparecen estas dos líneas:

   - de `ventadinamica_app`: `Server running on [http://0.0.0.0:8000]`
   - de `ventadinamica_vite`: `VITE v... ready in ...`

   Si ves líneas que dicen `error-handler`, no es un error: es el nombre de un paquete que se está instalando. Con `Ctrl+C` salís de los logs; los servicios siguen funcionando.

4. Generá la clave de Laravel:

   ```powershell
   docker compose exec app php artisan key:generate
   ```

5. Creá las tablas y cargá los datos de prueba:

   ```powershell
   docker compose exec app php artisan migrate --seed
   ```

6. Abrí http://localhost:8001. Vite funciona en http://localhost:5173 para servir CSS y JavaScript; la tienda se abre en el 8001. Dejá ambos servicios funcionando mientras trabajás.

## Usuarios de prueba

Después de cargar los seeders, entrá desde http://localhost:8001/login:

| Rol | Usuario | Contraseña |
| --- | --- | --- |
| Cliente | `cliente` | `cliente1234` |
| Administrador | `admin` | `admin1234` |

Están definidos en `database/seeders/ClienteSeeder.php` y `database/seeders/AdministradorSeeder.php`. El panel está en http://localhost:8001/admin y por ahora solo lista productos. Todavía no hay pago online. El contacto valida datos, pero no envía correos.

## Si tenés la base original o querés reiniciar los datos

**El siguiente comando borra TODAS las tablas y sus datos de la base configurada y vuelve a cargar los datos de prueba. Perdés usuarios, carritos y cualquier dato agregado. Guardá lo que necesites antes de ejecutarlo.**

Quien tenga la base vieja del original tiene que reconstruirla así: se modificaron migraciones existentes y ejecutar solamente `migrate` no actualiza esas tablas.

```powershell
docker compose exec app php artisan migrate:fresh --seed
```

## Tests

Con `app` funcionando:

```powershell
docker compose exec app php artisan test
```

`phpunit.xml` configura SQLite en memoria: los tests no usan la base MariaDB de la tienda. `tests/TestCase.php` desactiva Vite durante los tests.

## Apagar y volver a trabajar

Para apagar los servicios y conservar la base:

```powershell
docker compose down
```

Para volver a levantarlos:

```powershell
docker compose up -d
```

**La siguiente variante elimina también el volumen `mariadb_data`: borra la base guardada.** Después tendrás que crear las tablas y cargar los seeders otra vez.

```powershell
docker compose down -v
```

## Problemas comunes

### Docker Desktop apagado

Si Docker no puede conectarse a su motor, abrí Docker Desktop, esperá a que esté listo y repetí `docker version` y el arranque. Para ver qué falló:

```powershell
docker compose ps
docker compose logs app vite db
```

### Antivirus que intercepta HTTPS

Algunos antivirus revisan las conexiones HTTPS y eso rompe las descargas dentro de Docker. En esta computadora pasó con el escudo web de Avast durante el build: aparecen errores de certificado o descargas que fallan en `composer install` o `npm install`. Pausá ese escudo solo mientras se construye y volvé a activarlo al terminar. Para reintentar:

```powershell
docker compose up -d --build
```

### "Conflict. The container name ... is already in use"

Los contenedores de este proyecto se llaman `ventadinamica_app`, `ventadinamica_vite` y `ventadinamica_db`. Si tenés otra copia del proyecto (por ejemplo, tu versión anterior) levantada o creada, Docker no deja crear otra con los mismos nombres. Andá a la carpeta de la otra copia, apagala y volvé:

```powershell
docker compose down
```

### Puerto ocupado

Estos son los mapeos reales de `compose.yaml`:

| Servicio | Puerto en Windows | Puerto dentro del contenedor |
| --- | --- | --- |
| `app` | 8001 | 8000 |
| `vite` | 5173 | 5173 |
| `db` | 3307, solo en 127.0.0.1 | 3306 |

Buscá qué proceso usa el puerto. La última columna de la salida es su PID; podés buscarlo en el Administrador de tareas:

```powershell
netstat -ano | findstr :8001
netstat -ano | findstr :5173
netstat -ano | findstr :3307
```

Si sospechás de una instalación local de MariaDB/MySQL:

```powershell
netstat -ano | findstr :3306
```

Que Windows tenga ocupado el 3306 no entra en conflicto con este proyecto: la base se publica en el 3307.

Para cambiar un puerto, editá `ports` del servicio en `compose.yaml`. En `8001:8000` cambiás solo el número de la izquierda por uno libre; en `127.0.0.1:3307:3306`, cambiás el del medio. Conservá el puerto interno. Aplicá el cambio con:

```powershell
docker compose up -d
```

Si cambiás el de `app`, actualizá también `APP_URL` en tu `.env` y la dirección del navegador. Si cambiás el publicado de `db`, mantené `DB_PORT=3306` en `.env`.

Para Vite, lo más sencillo es liberar el 5173 cerrando la otra aplicación. Cambiar su puerto requiere coordinar también `server.port` y HMR en `vite.config.js` con el mapeo de `compose.yaml`: cambiar solamente el puerto publicado puede cortar la carga de recursos o la actualización automática.

## El día a día

Una vez instalado, no hace falta repetir todo. Cada vez que te sentás a programar:

1. Abrí Docker Desktop y esperá a que diga que está andando.
2. Abrí PowerShell en la carpeta del proyecto y levantá todo:

   ```powershell
   docker compose up -d
   ```

3. Esperá unos segundos y entrá a http://localhost:8001.
4. Cuando termines, apagalo:

   ```powershell
   docker compose down
   ```

Los datos quedan guardados para la próxima. Si algo no carga, mirá qué pasa con `docker compose logs app vite`.
