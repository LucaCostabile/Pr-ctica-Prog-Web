#!C:/Users/juann/AppData/Local/Programs/Python/Python312/python.exe
import cgi
import html
import os

# Cabecera obligatoria
print("Content-Type: text/html; charset=utf-8")
print()

form = cgi.FieldStorage()

print("<html><body>")
print("<h1>Procesar Formulario (CGI)</h1>")

# Si no hay datos, muestra un formulario simple de prueba
if not form or len(form.keys()) == 0:
    print("<form method='post' action='/cgi-bin/A8.py'>")
    print("  <p>Nombre: <input name='nombre' required></p>")
    print("  <p>Email: <input type='email' name='email'></p>")
    print("  <p>Clave: <input type='password' name='clave'></p>")
    print("  <p>Repetir Clave: <input type='password' name='clave2'></p>")
    print("  <button type='submit'>Enviar</button>")
    print("</form>")
else:
    # 1) Mostrar datos del formulario
    print("<h2>Datos del formulario</h2>")
    print("<ul>")
    for k in sorted(form.keys()):
        v = form.getlist(k)
        texto = ", ".join(html.escape(str(x)) for x in v)
        print(f"<li>{html.escape(k)}: {texto}</li>")
    print("</ul>")

    # 2) Comparar las claves (clave y clave2)
    c1 = form.getfirst("clave", "")
    c2 = form.getfirst("clave2", "")
    print("<h2>Comparación de claves</h2>")
    if c1 == "" and c2 == "":
        print("<p>Envía los campos 'clave' y 'clave2' para comparar.</p>")
    elif c1 == c2:
        print("<p>Las claves coinciden.</p>")
    else:
        print("<p>Las claves NO coinciden.</p>")

# 3) Variables de ambiente del servidor
print("<h2>Variables de entorno</h2>")
print("<pre>")
for k in sorted(os.environ.keys()):
    print(f"{k}={os.environ[k]}")
print("</pre>")

print("</body></html>")
