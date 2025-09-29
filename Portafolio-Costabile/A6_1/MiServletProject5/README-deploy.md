Instrucciones rápidas para crear la base de datos y desplegar la aplicación

1) Requisitos
- Java 8 (o compatible con el pom.xml)
- Maven
- Tomcat (u otro contenedor Servlet 3.0+)
- MySQL en la máquina local

2) Crear la base de datos y la tabla
- Abrir una terminal y conectarse a MySQL:
  mysql -u root -p
  (ingresar contraseña: root)
- Ejecutar el script SQL que está en `sql/create_progweb_db.txt`:
  SOURCE /ruta/absoluta/al/proyecto/sql/create_progweb_db.txt;

3) Compilar el proyecto
- Desde la raíz del proyecto ejecutar:
  mvn clean package
- El WAR generado estará en `target/MiServletProject5.war` (según finalName en pom)

4) Desplegar
- Copiar el WAR a la carpeta `webapps` de Tomcat y arrancar Tomcat (o usar Tomcat Manager)
- Abrir el navegador en:
  http://localhost:8080/MiServletProject5/

5) Uso
- Completar el formulario (nombre y edad) y enviar.
- El flujo hará: ServletA -> ServletB (inserta en BD) -> ServletC (muestra resultado)

6) Notas
- Credenciales en `DatabaseConnection.java`:
  URL: jdbc:mysql://localhost:3306/progweb
  USER: root
  PASSWORD: root
- Si usas MySQL 8+ y conector 8.x, considera actualizar el driver en `DatabaseConnection` a `com.mysql.cj.jdbc.Driver` y el dependency en pom a 8.x.

Si quieres, puedo:
- Crear un archivo SQL alternativo con instrucciones de Windows PowerShell (con mysql.exe)
- Cambiar el driver si vas a usar conector 8.x
- Ejecutar mvn package aquí y reportar errores (si me indicas que puedo usar la terminal)
