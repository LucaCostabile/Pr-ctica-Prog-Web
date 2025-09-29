<?php
session_start();
if (!isset($_SESSION['carrito'])) { $_SESSION['carrito'] = []; }

$productos = require __DIR__ . '/productos.php';
$indexProductos = [];
foreach ($productos as $p) { $indexProductos[$p['id']] = $p; }

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

switch ($action) {
  case 'add':
    if ($id && isset($indexProductos[$id])) {
      $_SESSION['carrito'][$id] = ($_SESSION['carrito'][$id] ?? 0) + 1;
    }
    break;
  case 'remove':
    if ($id && isset($_SESSION['carrito'][$id])) {
      $_SESSION['carrito'][$id] -= 1;
      if ($_SESSION['carrito'][$id] <= 0) unset($_SESSION['carrito'][$id]);
    }
    break;
  case 'set':
    $qty = max(0, (int)($_GET['qty'] ?? $_POST['qty'] ?? 0));
    if ($id && isset($indexProductos[$id])) {
      if ($qty > 0) { $_SESSION['carrito'][$id] = $qty; } else { unset($_SESSION['carrito'][$id]); }
    }
    break;
  case 'clear':
    $_SESSION['carrito'] = [];
    break;
}

// Respuesta JSON con resumen del carrito
header('Content-Type: application/json; charset=utf-8');
$totalItems = array_sum($_SESSION['carrito']);
$detalle = [];
$total = 0;
foreach ($_SESSION['carrito'] as $pid => $qty) {
  $p = $indexProductos[$pid];
  $subtotal = $p['precio'] * $qty;
  $total += $subtotal;
  $detalle[] = [
    'id' => $pid,
    'nombre' => $p['nombre'],
    'precio' => $p['precio'],
    'cantidad' => $qty,
    'subtotal' => $subtotal,
  ];
}

echo json_encode([
  'items' => $totalItems,
  'total' => $total,
  'detalle' => $detalle,
], JSON_UNESCAPED_UNICODE);
