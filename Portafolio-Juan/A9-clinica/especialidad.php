<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  http_response_code(400);
  echo json_encode(['error' => 'Parámetro id inválido']);
  exit;
}

$stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM especialidades WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row) {
  http_response_code(404);
  echo json_encode(['error' => 'No encontrada']);
  exit;
}

echo json_encode([
  'id' => (int)$row['id'],
  'nombre' => $row['nombre'],
  'descripcion' => $row['descripcion'],
], JSON_UNESCAPED_UNICODE);
