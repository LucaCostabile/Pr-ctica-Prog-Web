package com.ejemplo;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;

public class UsuarioDAO {
    private final String url;
    private final String user;
    private final String pass;

    public UsuarioDAO(String url, String user, String pass) {
        this.url = url;
        this.user = user;
        this.pass = pass;
    }

    public void insert(Usuario u) throws SQLException {
        String sql = "INSERT INTO usuarios(nombre, email, edad) VALUES (?, ?, ?)";
        try (Connection conn = DBUtil.getConnection(url, user, pass);
             PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setString(1, u.getNombre());
            ps.setString(2, u.getEmail());
            ps.setInt(3, u.getEdad());
            ps.executeUpdate();
        }
    }
}
