<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if ($email !== '' && $pass !== '') {
    $stmt = $pdo->prepare('SELECT id, nombre, email, password_hash FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($pass, $user['password_hash'])) {
      $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'nombre' => $user['nombre'],
        'email' => $user['email'],
      ];
      header('Location: /clinica2/index.php');
      exit;
    }
    $error = 'Credenciales inválidas';
  } else {
    $error = 'Complete email y contraseña';
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - Clínica 2</title>
</head>
<body>
  <h1>Login</h1>
  <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
  <form method="post">
    <p>Email: <input type="email" name="email" required></p>
    <p>Contraseña: <input type="password" name="password" required></p>
    <button type="submit">Entrar</button>
  </form>
  <p><a href="/clinica2/register.php">Registrarse</a></p>
</body>
</html>
