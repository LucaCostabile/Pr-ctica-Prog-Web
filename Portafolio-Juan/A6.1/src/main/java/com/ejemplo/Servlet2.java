package com.ejemplo;

import jakarta.servlet.RequestDispatcher;
import jakarta.servlet.ServletConfig;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.IOException;

public class Servlet2 extends HttpServlet {
    private String dbUrl;
    private String dbUser;
    private String dbPass;

    @Override
    public void init(ServletConfig config) throws ServletException {
        super.init(config);
        dbUrl = getServletContext().getInitParameter("db.url");
        dbUser = getServletContext().getInitParameter("db.user");
        dbPass = getServletContext().getInitParameter("db.pass");
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        Usuario u = (Usuario) req.getAttribute("usuario");
        if (u != null) {
            try {
                new UsuarioDAO(dbUrl, dbUser, dbPass).insert(u);
            } catch (Exception e) {
                throw new ServletException("Error al guardar usuario", e);
            }
        }
        RequestDispatcher rd = req.getRequestDispatcher("paso3");
        rd.forward(req, resp);
    }
}
