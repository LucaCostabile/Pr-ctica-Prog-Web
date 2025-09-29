#!C:\Users\Luca super compu\AppData\Local\Programs\Python\Python313\python.exe
import html
import os


def obtener_configuracion():
    """Obtiene la configuración de la base de datos desde variables de entorno."""
    return {
        "host": os.environ.get("DB_HOST", "localhost"),
        "user": os.environ.get("DB_USER", "root"),
        "password": os.environ.get("DB_PASSWORD", "root"),
        "database": os.environ.get("DB_NAME", "test"),
        "port": int(os.environ.get("DB_PORT", "3306")),
    }


def obtener_registros(db_config, tabla="usuarios"):
    conexion = None
    cursor = None
    try:
        # Importar aquí para poder capturar errores de dependencia faltante
        try:
            import mysql.connector as mysql_connector
        except Exception as e:
            return [], (
                "No se pudo importar mysql-connector-python. "
                "Instálalo en el mismo Python que usa Apache: "
                "python -m pip install mysql-connector-python. "
                f"Detalle: {e}"
            )

        conexion = mysql_connector.connect(**db_config)
        cursor = conexion.cursor(dictionary=True)
        cursor.execute(f"SELECT nombre, email FROM {tabla}")
        registros = cursor.fetchall()
        return registros, None
    except Exception as exc:
        return [], str(exc)
    finally:
        try:
            if cursor:
                cursor.close()
        except Exception:
            pass
        try:
            if conexion:
                conexion.close()
        except Exception:
            pass


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