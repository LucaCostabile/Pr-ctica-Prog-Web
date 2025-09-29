# Clínica - Especialidades y Sesiones

Este proyecto PHP permite:

- Registrar nuevas especialidades médicas.
- Listar las especialidades existentes.
- Mostrar la descripción de cada especialidad.
- Autenticarse (login/logout) y guardar datos del usuario con sesión activa.

## Requisitos

- PHP 8.x (o 7.4+ con PDO MySQL)
- MySQL/MariaDB
- Servidor web local (XAMPP/WAMP/IIS con PHP)

## Instalación rápida (Windows con XAMPP)

1. Copia esta carpeta dentro de `htdocs` (por ejemplo: `C:\xampp\htdocs\A9_2`).
2. Crea la base de datos ejecutando el SQL:
   - Abre phpMyAdmin y ejecuta el archivo `database/create_database.sql`.
   - O usa la consola de MySQL y pega el contenido del archivo.
3. Configura la conexión en `config/database.php` según tus credenciales MySQL (en XAMPP por defecto la contraseña suele estar vacía):
   - `$username = 'root';`
   - `$password = ''` (vacío en muchos XAMPP). Ajusta según tu entorno.
4. Abre en el navegador: `http://localhost/A9_2/index.php`.

## Endpoints principales

- GET `api/get_especialidades.php`: lista de especialidades (id, nombre)
- GET `api/get_descripcion.php?id={id}`: datos completos de una especialidad
- POST `api/add_especialidad.php`: agrega especialidad `{ nombre, descripcion }`
- POST `api/login.php`: login `{ email, password }`
- POST `api/logout.php`: logout
- GET `api/me.php`: estado de sesión y perfil del usuario
- POST `api/save_user_data.php`: guarda perfil `{ telefono, direccion, notas }` (requiere sesión)

## Usuario demo

Se intenta crear un usuario de ejemplo en el SQL (`database/create_database.sql`):

- Email: `demo@clinic.com`
- Password: `Demo1234!`

Si el login no funciona, crea un usuario manualmente con una contraseña generada por PHP:

```php
<?php echo password_hash('Demo1234!', PASSWORD_BCRYPT); // genera el hash ?>
```

Copia el hash resultante y ejecuta en MySQL:

```sql
INSERT INTO users (nombre, email, password_hash) VALUES ('Usuario Demo', 'demo@clinic.com', 'AQUI_TU_HASH');
```

## Uso desde la UI

- La página `index.php` permite iniciar sesión, agregar especialidades, ver su descripción, y guardar datos del usuario logueado.
- Los formularios usan fetch hacia la API. La sesión se gestiona con cookies de PHP (mismo origen).

## Notas de seguridad

- Este ejemplo es educativo. Para producción agrega validaciones adicionales, saneo de entradas, HTTPS y gestión de CORS/credenciales según dominio.
- No expongas `Access-Control-Allow-Origin: *` si vas a compartir cookies entre dominios; usa un origen permitido y `Access-Control-Allow-Credentials: true`.

## Solución de problemas

- Error de conexión a BD: revisa `config/database.php` (host, usuario, contraseña) y que MySQL esté encendido.
- Tablas inexistentes: ejecuta `database/create_database.sql`.
- Login falla: verifica que el correo exista en `users` y que `password_hash` coincida con la contraseña usando `password_verify`.
