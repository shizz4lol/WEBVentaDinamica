# Sobre Venta Dinamica

Esta es una aplicación web de comercio virtual orientada a la venta de cómics y mangas. El proyecto integra tecnologías de desarrollo frontend y backend, junto con contenedores en Docker para facilitar la configuración y ejecución del entorno.
Contiene lógica básica de CRUD de productos, y lógica de logeo para administradores y usuario.
Se decidio este dominio por interés propio de la alumna Rizzuto Á. :3

## Tecnologías utilizadas

- **HTML5:** estructura de las páginas web.
- **CSS3:** estilos y diseño visual.
- **JavaScript:** interactividad del frontend.
- **PHP 8.5.6:** lenguaje utilizado en el backend. [Instalar](https://www.php.net/downloads.php)
- **Laravel 13:** framework PHP
- **React:** biblioteca JS para el uso de componentes en el frontend.
- **Node.js 22:** entorno de ejecución para las herramientas del frontend. [Instalar](https://nodejs.org/es/download)
- **Vite:** servidor de desarrollo y herramienta de compilación de recursos.
- **MariaDB 11.4:** sistema de gestión de bases de datos relacional.
- **Docker:** configuración y ejecución de los servicios mediante contenedores. [Instalar](https://www.docker.com/products/docker-sandboxes/)
- **WSL 2:** subsistema que permite la ejecución de Docker (el cual es nativo de Linux). 
- **Composer:** administrador de dependencias de PHP. [Instalar](https://getcomposer.org/download/)
- **npm:** administrador de dependencias de JavaScript.

## Requisitos previos

Para ejecutar el proyecto, se necesita tener instalado:

- WSL 2 (o Linux en su defecto)
- Docker Desktop.
- Docker Compose, incluido en las instalaciones actuales de Docker Desktop.
- NPM
- Git, si se desea clonar el repositorio.

No es realmente necesario instalar PHP, Composer, Node.js ni MariaDB directamente en Windows, ya que se ejecutan dentro de los contenedores correspondientes. Aun asi, para la configuración en entorno de producción, se configuro toda la BD en Laravel con Composer, y lo único que se instalo dentro de Docker fueron las dependencias correspondientes a React.
Este proyecto NO utiliza XAMPP.

## Estructura del entorno Docker

El entorno utiliza tres servicios principales definidos en `compose.yaml`:

| Servicio | Función |
|---|---|
| `app` | Ejecuta Laravel y PHP. |
| `vite` | Ejecuta Node.js, Vite y las herramientas del frontend React. |
| `db` | Ejecuta el servidor de base de datos MariaDB mediante la conexión con. |

Los contenedores se comunican a través de la red interna de Docker.

### Puertos utilizados

| Servicio | Puerto del host | Puerto del contenedor |
|---|---:|---:|
| Laravel | 8001 | 8000 |
| Vite | 5173 | 5173 |
| MariaDB | 3307 | 3306 |

La aplicación Laravel estará disponible en:

http://localhost:8001

El servidor de desarrollo de Vite estará disponible en:

http://localhost:5173

El puerto `3307` permite acceder a MariaDB desde el equipo anfitrión, por ejemplo, mediante un cliente de bases de datos. Dentro de Docker, Laravel debe conectarse al servicio `db` mediante el puerto `3306`. Esto fue particularmente configurado mediante los archivos `Dockerfile` y `.env` en la raíz de la carpeta de Laravel.

## Instalación y configuración

### 1. Obtener el proyecto

Clonar el repositorio o descargar sus archivos y abrir una terminal en el directorio raíz del proyecto, donde se encuentra el archivo `compose.yaml`.

### 2. Configurar las variables de entorno

Crear el archivo `.env` a partir de `.env.example` (se cambia el nombre, eliminándose '.example').

Configurar las variables de Laravel según el entorno Docker. Los valores de usuario y contraseña deben coincidir con los definidos para el servicio MariaDB en el archivo Compose.

Actualmente, los usuarios están asi:

```dotenv
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ventadinamica
DB_USERNAME=ventadinamica_admin
DB_PASSWORD=----
DB_ROOT_PASSWORD=----
```

Los valores de usuarios son correctos, pero por una cuestión de seguridad deberá poner otras contraseñas.
**Importante:** `DB_HOST=db` corresponde al nombre del servicio de MariaDB dentro de la red de Docker. No debe utilizarse `localhost` desde el contenedor de Laravel para conectarse a esa base de datos.

### 3. Construir e iniciar los contenedores

Desde el directorio raíz del proyecto, ejecutar:

```bash
docker compose up -d --build
```

Este comando construye las imágenes necesarias e inicia los servicios definidos en la configuración de Docker Compose.

Para comprobar el estado de los contenedores:

```bash
docker compose ps
```

### 4. Instalar las dependencias de Laravel

Si las dependencias de PHP todavía no están instaladas, ejecutar Composer dentro del contenedor de Laravel:

```bash
docker compose exec app composer install
```

Generar/actualizar la clave de la aplicación si aún no existe:

```bash
docker compose exec app php artisan key:generate
```

Si el proyecto utiliza migraciones y estas todavía no se han ejecutado, inicializar la base de datos:

```bash
docker compose exec app php artisan migrate
```

Este último comando crea las tablas definidas en las migraciones pendientes. Al ejecutar ese comando también cargara el seeder de Administrador.

### 5. Instalar las dependencias del frontend

Para las dependencias de Node.js ejecutar:

```bash
docker compose exec vite npm install
```

Este paso debe realizarse en el directorio del contenedor `vite` que contiene el archivo `package.json`. Si el servicio ya instala las dependencias automáticamente, no es necesario repetirlo.

### 6. Acceder a la aplicación

Una vez iniciados los servicios, abrir:

http://localhost:8001

Durante el desarrollo, Vite proporciona los recursos frontend y permite aplicar los cambios de JavaScript, React y CSS según la configuración de HMR.

Los archivos del proyecto se encuentran vinculados mediante los volúmenes definidos en Docker Compose. Por ello, los cambios realizados en los archivos locales se reflejan dentro de los contenedores sin tener que reconstruir las imágenes cada vez, salvo que se modifique la configuración de la imagen o alguna dependencia que requiera reconstrucción.

## Comandos útiles

### Administrar los contenedores

Iniciar los servicios:

```bash
docker compose up -d
```

Detener los servicios:

```bash
docker compose down
```

Reconstruir las imágenes e iniciar los servicios:

```bash
docker compose up -d --build
```

Consultar los registros:

```bash
docker compose logs -f
```

Consultar los registros de Laravel:

```bash
docker compose logs -f app
```

Consultar los registros de Vite:

```bash
docker compose logs -f vite
```

### Ejecutar comandos de Laravel

Ejecutar migraciones:

```bash
docker compose exec app php artisan migrate
```

Mostrar las rutas de Laravel:

```bash
docker compose exec app php artisan route:list
```

Limpiar las cachés de Laravel:

```bash
docker compose exec app php artisan optimize:clear
```

Abrir una terminal dentro del contenedor de Laravel:

```bash
docker compose exec app sh
```

### Acceder a MariaDB

Abrir una terminal en el contenedor de la base de datos:

```bash
docker compose exec db mariadb -u usuario -p
```

Reemplazar `usuario` por el usuario configurado en el servicio MariaDB. El comando solicitará la contraseña.

## Persistencia de datos

La base de datos utiliza un volumen persistente de Docker, denominado `mariadb_data`, asociado al directorio de datos de MariaDB.

Esto permite conservar los datos aunque se detengan o eliminen los contenedores, siempre que no se elimine el volumen.

El comando:

```bash
docker compose down
```

detiene y elimina los contenedores, pero conserva los volúmenes por defecto.

## Desarrollo

El código de Laravel se encuentra en el directorio del proyecto y se ejecuta dentro del contenedor `app`.

Los recursos frontend se gestionan mediante React, Node.js y Vite, utilizando el servicio `vite`.

MariaDB funciona de forma independiente en el servicio `db`, pero se comunica con Laravel mediante la red interna de Docker y la configuración previa en el `.env`.

Los cambios realizados en los archivos locales se reflejan en los contenedores mediante los volúmenes configurados, por lo que normalmente no es necesario reconstruirlos para cada modificación del código.

## Consideraciones

- No subir el archivo `.env` al repositorio si contiene credenciales o claves privadas.
- Mantener `.env.example` actualizado, sin incluir contraseñas reales.
- Verificar que los nombres de los servicios, las variables de entorno y los puertos coincidan con el archivo Compose del proyecto.
- No eliminar el volumen de MariaDB si se necesita conservar la información almacenada.
