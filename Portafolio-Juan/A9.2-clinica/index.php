<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';

// Inserción de nuevas especialidades (si hay sesión)
if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  if ($nombre !== '' && $descripcion !== '') {
    $stmt = $pdo->prepare('INSERT INTO especialidades (nombre, descripcion) VALUES (?, ?)');
    $stmt->execute([$nombre, $descripcion]);
  }
}

// Listado de todas las especialidades
$stmt = $pdo->query('SELECT id, nombre, descripcion FROM especialidades ORDER BY nombre');
$especialidades = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Clínica 2 - Especialidades</title>
  <style>
    body{font-family: Arial, sans-serif; margin: 20px}
    textarea{width: 100%; height: 100px}
    table{border-collapse: collapse; width: 100%}
    th,td{border:1px solid #ccc; padding:6px}
  </style>
</head>
<body>
  <h1>Clínica 2</h1>

  <?php if (is_logged_in()): ?>
    <p>Sesión: <strong><?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'usuario', ENT_QUOTES, 'UTF-8') ?></strong> | <a href="/clinica2/logout.php">Cerrar sesión</a></p>
  <?php else: ?>
    <p><a href="/clinica2/login.php">Iniciar sesión</a> | <a href="/clinica2/register.php">Registrarse</a></p>
  <?php endif; ?>

  <?php if (is_logged_in()): ?>
  <h2>Registrar nueva especialidad</h2>
  <form method="post">
    <p>Nombre:<br><input name="nombre" required></p>
    <p>Descripción:<br><textarea name="descripcion" required></textarea></p>
    <button type="submit">Guardar</button>
  </form>
  <?php endif; ?>

  <h2>Especialidades</h2>
  <table>
    <tr><th>ID</th><th>Nombre</th><th>Descripción</th></tr>
    <?php foreach ($especialidades as $esp): ?>
      <tr>
        <td><?= (int)$esp['id'] ?></td>
        <td><?= htmlspecialchars($esp['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= nl2br(htmlspecialchars($esp['descripcion'], ENT_QUOTES, 'UTF-8')) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
