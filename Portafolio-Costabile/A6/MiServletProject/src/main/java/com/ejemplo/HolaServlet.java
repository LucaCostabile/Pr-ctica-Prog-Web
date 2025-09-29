package com.ejemplo;

import javax.servlet.*;
import javax.servlet.http.*;
import java.io.IOException;
import java.io.PrintWriter;

public class HolaServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
        throws ServletException, IOException {

        response.setContentType("text/html");
        response.setCharacterEncoding("UTF-8");
        PrintWriter out = response.getWriter();
        out.println("<html><body>");
        out.println("<h1>¡Hola desde el Servlet!</h1>");
        out.println("</body></html>");
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        request.setCharacterEncoding("UTF-8");
        response.setContentType("text/html");
        response.setCharacterEncoding("UTF-8");

        String nombre = request.getParameter("nombre");
        String edadParam = request.getParameter("edad");
        String mensaje;

        if (nombre == null) nombre = "";
        if (edadParam == null || edadParam.isEmpty()) {
            mensaje = "Parámetro 'edad' no proporcionado.";
        } else {
            try {
                int edad = Integer.parseInt(edadParam);
                boolean mayor = edad >= 18;
                mensaje = String.format("El usuario %s tiene %d años. Es %s de edad",
                        nombre, edad, (mayor ? "mayor" : "menor"));
            } catch (NumberFormatException e) {
                mensaje = "Parámetro 'edad' inválido. Debe ser un número.";
            }
        }

        PrintWriter out = response.getWriter();
        out.println("<html><body>");
        out.println("<p>" + mensaje + "</p>");
        out.println("</body></html>");
    }
}
