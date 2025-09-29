package com.ejemplo.servlets;

import javax.servlet.*;
import javax.servlet.http.*;
import java.io.IOException;
import java.io.PrintWriter;

public class ServletC extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        procesarSolicitud(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        procesarSolicitud(req, resp);
    }

    private void procesarSolicitud(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {

        Usuario user = (Usuario) req.getAttribute("usuario");

        resp.setContentType("text/html;charset=UTF-8");
        PrintWriter out = resp.getWriter();

        out.println("<html><body>");
        out.println("<h1>Resultado Final</h1>");
        if (user != null) {
            out.println("<p>Nombre: " + user.getNombre() + "</p>");
            out.println("<p>Edad: " + user.getEdad() + "</p>");
        } else {
            out.println("<p>No se recibió usuario.</p>");
        }
        out.println("</body></html>");
    }
}
