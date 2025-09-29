<?php
/**
 * API endpoint para obtener todas las especialidades
 * Retorna las especialidades en formato JSON para poblar el combobox
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir la conexión a la base de datos
require_once '../config/database.php';

try {
    // Obtener conexión a la base de datos
    $pdo = getDB();
    
    // Consulta para obtener todas las especialidades (solo id y nombre para el combobox)
    $query = "SELECT id, nombre FROM especialidades ORDER BY nombre ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Obtener todos los resultados
    $especialidades = $stmt->fetchAll();
    
    // Verificar si se encontraron especialidades
    if (count($especialidades) > 0) {
        $response = [
            'success' => true,
            'message' => 'Especialidades obtenidas correctamente',
            'count' => count($especialidades),
            'especialidades' => $especialidades
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No se encontraron especialidades en la base de datos',
            'count' => 0,
            'especialidades' => []
        ];
    }
    
} catch (PDOException $e) {
    // Error de base de datos
    error_log("Error en get_especialidades.php: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Error al consultar la base de datos',
        'error' => 'Database error'
    ];
    http_response_code(500);
    
} catch (Exception $e) {
    // Error general
    error_log("Error general en get_especialidades.php: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Error interno del servidor',
        'error' => 'Internal server error'
    ];
    http_response_code(500);
}

// Enviar respuesta JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>