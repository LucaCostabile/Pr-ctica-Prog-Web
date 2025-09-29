package com.ejemplo;

import jakarta.servlet.RequestDispatcher;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.IOException;

public class Servlet1 extends HttpServlet {
    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        req.setCharacterEncoding("UTF-8");
        String nombre = req.getParameter("nombre");
        String email = req.getParameter("email");
        String edadStr = req.getParameter("edad");

        int edad = 0;
        try { edad = Integer.parseInt(edadStr); } catch (Exception ignored) {}

        Usuario u = new Usuario(nombre, email, edad);
        req.setAttribute("usuario", u);

        RequestDispatcher rd = req.getRequestDispatcher("paso2");
        rd.forward(req, resp);
    }
}
