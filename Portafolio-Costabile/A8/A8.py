#!C:\Users\Luca super compu\AppData\Local\Programs\Python\Python313\python.exe
import os
import sys
import html
from urllib.parse import parse_qs


def _ensure_utf8_stdout():
    try:
        # Evita UnicodeEncodeError en Windows/Apache cuando hay caracteres no ASCII
        sys.stdout.reconfigure(encoding="utf-8", errors="replace")
    except Exception:
        pass

def parse_form():
    method = os.environ.get("REQUEST_METHOD", "GET").upper()
    if method == "POST":
        try:
            length = int(os.environ.get("CONTENT_LENGTH") or 0)
        except ValueError:
            length = 0
        raw = sys.stdin.buffer.read(length) if length > 0 else b""
        data = parse_qs(raw.decode("utf-8", errors="replace"), keep_blank_values=True)
    else:
        data = parse_qs(os.environ.get("QUERY_STRING", ""), keep_blank_values=True)

    first = lambda k: (data.get(k, [""])[0] if data.get(k) else "")
    pwd = first("password") or first("clave")
    pwd2 = first("password2") or first("clave2")
    return {
        "nombre": first("nombre"),
        "apellido": first("apellido"),
        "email": first("email"),
        "password": pwd,
        "password2": pwd2,
    }


def _env_block():
    out_lines = ["<hr><h2>Variables de entorno</h2><pre>"]
    for k in sorted(os.environ.keys()):
        v = os.environ.get(k, "")
        out_lines.append(f"{html.escape(k)}={html.escape(v)}")
    out_lines.append("</pre>")
    return "\n".join(out_lines)


def print_form(msg: str = "", d: dict | None = None):
        d = d or {}
        nombre = html.escape(d.get("nombre", ""))
        apellido = html.escape(d.get("apellido", ""))
        email = html.escape(d.get("email", ""))
        alert = f"<p><b>Resultado:</b> {html.escape(msg)}</p>" if msg else ""
        print(f"""
<!doctype html>
<html lang='es'>
<head><meta charset='utf-8'><title>Formulario</title></head>
<body>
  <h1>Formulario</h1>
    {alert}
  <form method='post'>
        <p>Nombre: <input name='nombre' value='{nombre}' required></p>
        <p>Apellido: <input name='apellido' value='{apellido}' required></p>
        <p>Email: <input type='email' name='email' value='{email}' required></p>
    <p>Password: <input type='password' name='password' required></p>
    <p>Repetir Password: <input type='password' name='password2' required></p>
    <button type='submit'>Enviar</button>
  </form>
""")
        print(_env_block())
        print("""
</body></html>
""")


def _msg_for_passwords(d: dict) -> str:
    if d.get("password", "") == "" and d.get("password2", "") == "":
        return "Complete ambos campos para comparar"
    return "Las claves coinciden" if d.get("password") == d.get("password2") else "Las claves NO coinciden"


def main():
    _ensure_utf8_stdout()
    # Enviar header ANTES de ejecutar lógica, así ante error podemos imprimir el mismo HTML
    sys.stdout.write("Content-Type: text/html; charset=utf-8\r\n\r\n")
    try:
        method = os.environ.get("REQUEST_METHOD", "GET").upper()
        if method == "POST":
            data = parse_form()
            print_form(_msg_for_passwords(data), data)
        else:
            print_form()
    except Exception as e:
        # Mostrar el mismo formulario con el mensaje de error (sin abrir otra página)
        print_form(f"Se produjo un error: {e.__class__.__name__}: {e}")


if __name__ == "__main__":
    main()