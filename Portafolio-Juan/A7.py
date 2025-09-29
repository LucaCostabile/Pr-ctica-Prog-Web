#!C:/Users/juann/AppData/Local/Programs/Python/Python312/python.exe
"""CGI que lista usuarios desde MySQL.

SQL de referencia para crear la base y la tabla esperadas:

    CREATE DATABASE IF NOT EXISTS test DEFAULT CHARACTER SET utf8mb4;
    USE test;

    CREATE TABLE IF NOT EXISTS usuarios (
        id      INT AUTO_INCREMENT PRIMARY KEY,
        nombre  VARCHAR(100) NOT NULL,
        email   VARCHAR(150) NOT NULL UNIQUE,
        creado  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    INSERT INTO usuarios (nombre, email) VALUES
        ('Ada Lovelace', 'ada@example.com'),
        ('Alan Turing',  'alan@example.com');

"""

import html
import os

import mysql.connector
from mysql.connector import Error


def obtener_configuracion():
    """Obtiene la configuración de la base de datos desde variables de entorno."""
    return {
        "host": os.environ.get("DB_HOST", "localhost"),
        "user": os.environ.get("DB_USER", "root"),
        "password": os.environ.get("DB_PASSWORD", ""),
        "database": os.environ.get("DB_NAME", "test"),
        "port": int(os.environ.get("DB_PORT", "3306")),
    }


def obtener_registros(db_config, tabla="usuarios"):
    try:
        conexion = mysql.connector.connect(**db_config)
        cursor = conexion.cursor(dictionary=True)
        cursor.execute(f"SELECT nombre, email FROM {tabla}")
        registros = cursor.fetchall()
        cursor.close()
        conexion.close()
        return registros, None
    except Error as exc:
        return [], str(exc)


def renderizar_html(registros, error=None):
    print("Content-Type: text/html; charset=utf-8")
    print()
    print("<html><head><title>Listado de Usuarios</title></head><body>")
    print("<h1>Listado de usuarios</h1>")

    if error:
        print(f"<p>Error al conectar con la base de datos: {html.escape(error)}</p>")
    elif not registros:
        print("<p>No se encontraron usuarios.</p>")
    else:
        print("<table border='1' cellpadding='6' cellspacing='0'>")
        print("<thead><tr><th>Nombre</th><th>Email</th></tr></thead>")
        print("<tbody>")
        for fila in registros:
            nombre = html.escape(fila.get("nombre", ""))
            email = html.escape(fila.get("email", ""))
            print(f"<tr><td>{nombre}</td><td>{email}</td></tr>")
        print("</tbody></table>")

    print("</body></html>")


if __name__ == "__main__":
    config = obtener_configuracion()
    tabla = os.environ.get("DB_USERS_TABLE", "usuarios")
    registros, error = obtener_registros(config, tabla)
    renderizar_html(registros, error)
