package com.ejemplo;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.IOException;
import java.io.PrintWriter;

public class Servlet3 extends HttpServlet {
    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        Usuario u = (Usuario) req.getAttribute("usuario");
        resp.setContentType("text/html; charset=UTF-8");
        try (PrintWriter out = resp.getWriter()) {
            out.println("<html><body>");
            out.println("<h2>Usuario guardado con éxito</h2>");
            if (u != null) {
                out.printf("<p>Nombre: %s</p>", u.getNombre());
                out.printf("<p>Email: %s</p>", u.getEmail());
                out.printf("<p>Edad: %d</p>", u.getEdad());
            } else {
                out.println("<p>No se recibió el objeto Usuario.</p>");
            }
            out.println("<p><a href='index.html'>Volver</a></p>");
            out.println("</body></html>");
        }
    }
}
