package com.ejemplo;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;

/**
 * Servlet que atiende peticiones GET y POST.
 * - GET: simple verificación de vida.
 * - POST: recibe parámetros "nombre" y "edad" y responde con un mensaje.
 */
public class UsuarioServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        resp.setContentType("text/plain; charset=UTF-8");
        try (PrintWriter out = resp.getWriter()) {
            out.println("UsuarioServlet OK");
        }
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        req.setCharacterEncoding("UTF-8");
        resp.setContentType("text/plain; charset=UTF-8");

    String nombre = req.getParameter("nombre");
    String edadStr = req.getParameter("edad");

        String mensaje;
        boolean nombreVacio = (nombre == null || nombre.trim().isEmpty());
        boolean edadVacia = (edadStr == null || edadStr.trim().isEmpty());
        if (nombreVacio || edadVacia) {
            resp.setStatus(HttpServletResponse.SC_BAD_REQUEST);
            mensaje = "Faltan parámetros: nombre y edad son requeridos";
        } else {
            int edad;
            try {
                edad = Integer.parseInt(edadStr);
                String mayoria = edad >= 18 ? "mayor" : "menor";
                mensaje = String.format("El usuario %s tiene %d años. Es %s de edad", nombre, edad, mayoria);
            } catch (NumberFormatException e) {
                resp.setStatus(HttpServletResponse.SC_BAD_REQUEST);
                mensaje = "El parámetro 'edad' debe ser un número entero";
            }
        }

        try (PrintWriter out = resp.getWriter()) {
            out.print(mensaje);
        }
    }
}
