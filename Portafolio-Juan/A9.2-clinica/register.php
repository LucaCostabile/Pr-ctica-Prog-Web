<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $email  = trim($_POST['email'] ?? '');
  $pass   = $_POST['password'] ?? '';
  $pass2  = $_POST['password2'] ?? '';

  if ($nombre !== '' && $email !== '' && $pass !== '') {
    if ($pass !== $pass2) {
      $error = 'Las contraseñas no coinciden';
    } else {
      $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
      $stmt->execute([$email]);
      if ($stmt->fetch()) {
        $error = 'El email ya está registrado';
      } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $email, $hash]);
        $msg = 'Usuario registrado. Ya puede iniciar sesión.';
      }
    }
  } else {
    $error = 'Complete todos los campos';
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro - Clínica 2</title>
</head>
<body>
  <h1>Registro</h1>
  <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
  <?php if ($msg): ?><p style="color:green;"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
  <form method="post">
    <p>Nombre: <input name="nombre" required></p>
    <p>Email: <input type="email" name="email" required></p>
    <p>Contraseña: <input type="password" name="password" required></p>
    <p>Repetir Contraseña: <input type="password" name="password2" required></p>
    <button type="submit">Crear cuenta</button>
  </form>
  <p><a href="/clinica2/login.php">Volver a Login</a></p>
</body>
</html>
