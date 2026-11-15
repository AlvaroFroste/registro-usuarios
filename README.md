# Proyecto Docker - Registro de usuarios

## 1. Descripción y objetivo

Este proyecto consiste en crear una página web de registro de usuarios utilizando PHP y MariaDB.

El objetivo es guardar los usuarios en una base de datos y comprobar que los datos se mantienen aunque reiniciemos los contenedores.

## 2. Tecnologías utilizadas

- Docker y Docker Compose.
- PHP, HTML y CSS.
- Apache.
- MariaDB.
- Git y GitHub.
- Ubuntu WSL 2 y Windows 11.

## 3. Requisitos previos

- Windows 11 con Ubuntu WSL 2.
- Docker Desktop instalado y funcionando.
- Git instalado.
- Conexión a internet.

## 4. Estructura del proyecto

- `Dockerfile`: crea la imagen de PHP con Apache.
- `docker-compose.yml`: configura los contenedores.
- `.env`: contiene las contraseñas y configuración.
- `.env.example`: ejemplo de configuración.
- `.gitignore`: indica qué archivos no se suben.
- `app/`: contiene el código de la página web.
- `database/init.sql`: crea la tabla de usuarios.
- `backups/`: guarda las copias de seguridad.

## 5. Configuración

Copiar el archivo de ejemplo:

```bash
cp .env.example .env
```

Después, editar `.env` y configurar las contraseñas.

## 6. Ejecutar el proyecto

Desde la carpeta del proyecto:

```bash
docker compose up -d --build
```

Este comando crea e inicia los contenedores.

## 7. Acceder a la web

Abrir en el navegador:

http://localhost:8080

Desde ahí podemos registrar usuarios y consultar los que ya están guardados.

## 8. Detener y reiniciar

Para detener los contenedores:

```bash
docker compose down
```

Para volver a iniciarlos:

```bash
docker compose up -d
```

Los datos se conservan gracias al volumen de MariaDB.

## 9. Consultar los logs

```bash
docker compose logs
```

Permite comprobar los mensajes de los contenedores y detectar errores.

## 10. Copias de seguridad

Para crear una copia:

```bash
docker compose exec -T db sh -c \
'mariadb-dump -u root -p"$MARIADB_ROOT_PASSWORD" registro_db' \
> backups/registro.sql
```

Para restaurarla en una base de datos de prueba, primero creamos `registro_restaurado` desde MariaDB y después ejecutamos:

```bash
docker compose exec -T db sh -c \
'mariadb -u root -p"$MARIADB_ROOT_PASSWORD" registro_restaurado' \
< backups/registro.sql
```

## 11. Seguridad

- Las contraseñas de los usuarios se guardan mediante `password_hash()`.
- Se utilizan consultas preparadas para evitar inyecciones SQL.
- Se comprueba que los correos electrónicos no estén duplicados.
- Las credenciales reales no se suben a GitHub.
- MariaDB no tiene su puerto expuesto al exterior.
