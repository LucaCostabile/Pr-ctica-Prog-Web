<?php
/**
 * API endpoint para registrar una nueva especialidad médica
 * Método: POST
 * Body JSON o application/x-www-form-urlencoded: { nombre, descripcion }
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once '../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    // Obtener payload (soporta JSON y form-urlencoded)
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?: [];
    } else {
        $data = $_POST;
    }

    $nombre = trim($data['nombre'] ?? '');
    $descripcion = trim($data['descripcion'] ?? '');

    if ($nombre === '' || $descripcion === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre y descripción son requeridos']);
        exit;
    }

    if (mb_strlen($nombre) > 100) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El nombre no puede exceder 100 caracteres']);
        exit;
    }

    $pdo = getDB();

    // Verificar duplicado por nombre (único)
    $checkStmt = $pdo->prepare('SELECT id FROM especialidades WHERE nombre = :n');
    $checkStmt->execute([':n' => $nombre]);
    if ($checkStmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'La especialidad ya existe']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO especialidades (nombre, descripcion) VALUES (:n, :d)');
    $stmt->execute([':n' => $nombre, ':d' => $descripcion]);

    $id = (int)$pdo->lastInsertId();
    echo json_encode([
        'success' => true,
        'message' => 'Especialidad creada correctamente',
        'id' => $id
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    error_log('Error BD add_especialidad: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
} catch (Throwable $e) {
    error_log('Error add_especialidad: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}
?>
