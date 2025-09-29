package com.ejemplo.servlets;


import javax.servlet.*;
import javax.servlet.http.*;
import java.io.IOException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class ServletA extends HttpServlet {

    private static final Logger logger = LoggerFactory.getLogger(ServletA.class);
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        // Delegar GET a POST para simplificar
        doPost(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {

    logger.info("ServletA: método recibido = {} , requestURI = {}", req.getMethod(), req.getRequestURI());


        String nombre = req.getParameter("nombre");
        if (nombre == null || nombre.trim().isEmpty()) {
            nombre = "Invitado";
        }

        int edad = 0;
        String edadParam = req.getParameter("edad");
        try {
            if (edadParam != null && !edadParam.trim().isEmpty()) {
                edad = Integer.parseInt(edadParam);
            }
        } catch (NumberFormatException e) {
            edad = 0;
        }

        Usuario user = new Usuario(nombre, edad);
        req.setAttribute("usuario", user);

        // Pasar a ServletB
        RequestDispatcher rd = req.getRequestDispatcher("/servletB");
        rd.forward(req, resp);
    }
}