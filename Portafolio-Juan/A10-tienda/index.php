<?php
session_start();
$productos = require __DIR__ . '/productos.php';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Tienda</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Tienda</h1>
  <div class="carrito-mini">
    <button id="btnVaciar">Vaciar carrito</button>
    <span id="badge">Carrito (0 items)</span>
  </div>

  <div class="grid">
    <?php foreach ($productos as $p): ?>
      <div class="card">
        <h3><?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p>$<?= number_format($p['precio'], 0) ?></p>
        <button class="btn-add" data-id="<?= (int)$p['id'] ?>">Agregar al carrito</button>
      </div>
    <?php endforeach; ?>
  </div>

  <h2>Contenido del carrito</h2>
  <ul id="lista"></ul>
  <p id="total"></p>

  <script src="main.js"></script>
</body>
</html>
