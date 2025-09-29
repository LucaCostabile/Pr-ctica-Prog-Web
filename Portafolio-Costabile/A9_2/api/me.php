<?php
/**
 * API para obtener información del usuario autenticado y su perfil
 * Método: GET
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

session_start();
require_once '../config/database.php';

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['authenticated' => false]);
        exit;
    }

    $pdo = getDB();

    // Datos básicos del usuario
    $stmt = $pdo->prepare('SELECT id, email, nombre FROM users WHERE id = :id');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Perfil opcional
    $pstmt = $pdo->prepare('SELECT telefono, direccion, notas, updated_at FROM user_profiles WHERE user_id = :id');
    $pstmt->execute([':id' => $_SESSION['user_id']]);
    $profile = $pstmt->fetch() ?: null;

    echo json_encode([
        'authenticated' => true,
        'user' => $user,
        'profile' => $profile
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    error_log('Error BD me: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
} catch (Throwable $e) {
    error_log('Error me: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}
?>
