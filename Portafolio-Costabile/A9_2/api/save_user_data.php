<?php
/**
 * API para guardar datos de usuario con sesión activa
 * Método: POST
 * Body: { telefono, direccion, notas } (campos de ejemplo)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

session_start();
require_once '../config/database.php';

try {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No autenticado']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
    } else {
        $data = $_POST;
    }

    $telefono = trim($data['telefono'] ?? '');
    $direccion = trim($data['direccion'] ?? '');
    $notas = trim($data['notas'] ?? '');

    $pdo = getDB();

    // upsert en tabla user_profiles
    $stmt = $pdo->prepare('INSERT INTO user_profiles (user_id, telefono, direccion, notas) 
                           VALUES (:uid, :t, :d, :n)
                           ON DUPLICATE KEY UPDATE telefono = VALUES(telefono), direccion = VALUES(direccion), notas = VALUES(notas), updated_at = CURRENT_TIMESTAMP');
    $stmt->execute([
        ':uid' => $_SESSION['user_id'],
        ':t' => $telefono,
        ':d' => $direccion,
        ':n' => $notas,
    ]);

    echo json_encode(['success' => true, 'message' => 'Datos guardados']);
} catch (PDOException $e) {
    error_log('Error BD save_user_data: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
} catch (Throwable $e) {
    error_log('Error save_user_data: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}
?>
