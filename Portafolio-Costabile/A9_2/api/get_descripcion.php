<?php
/**
 * API endpoint para obtener la descripción de una especialidad específica
 * Recibe el ID de la especialidad por GET y retorna todos sus datos
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir la conexión a la base de datos
require_once '../config/database.php';

try {
    // Verificar que se ha proporcionado el ID
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        http_response_code(400);
        $response = [
            'success' => false,
            'message' => 'ID de especialidad requerido',
            'error' => 'Missing required parameter: id'
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Validar que el ID sea numérico
    $especialidadId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($especialidadId === false || $especialidadId <= 0) {
        http_response_code(400);
        $response = [
            'success' => false,
            'message' => 'ID de especialidad inválido',
            'error' => 'Invalid ID format'
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Obtener conexión a la base de datos
    $pdo = getDB();
    
    // Consulta para obtener la especialidad específica
    $query = "SELECT id, nombre, descripcion, fecha_creacion, fecha_actualizacion 
              FROM especialidades 
              WHERE id = :id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $especialidadId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Obtener el resultado
    $especialidad = $stmt->fetch();
    
    if ($especialidad) {
        // Formatear las fechas para mejor presentación
        $especialidad['fecha_creacion_formateada'] = date('d/m/Y H:i', strtotime($especialidad['fecha_creacion']));
        $especialidad['fecha_actualizacion_formateada'] = date('d/m/Y H:i', strtotime($especialidad['fecha_actualizacion']));
        
        $response = [
            'success' => true,
            'message' => 'Especialidad obtenida correctamente',
            'especialidad' => $especialidad
        ];
    } else {
        http_response_code(404);
        $response = [
            'success' => false,
            'message' => 'Especialidad no encontrada',
            'error' => 'Specialty not found'
        ];
    }
    
} catch (PDOException $e) {
    // Error de base de datos
    error_log("Error de BD en get_descripcion.php: " . $e->getMessage());
    http_response_code(500);
    $response = [
        'success' => false,
        'message' => 'Error al consultar la base de datos',
        'error' => 'Database error'
    ];
    
} catch (Exception $e) {
    // Error general
    error_log("Error general en get_descripcion.php: " . $e->getMessage());
    http_response_code(500);
    $response = [
        'success' => false,
        'message' => 'Error interno del servidor',
        'error' => 'Internal server error'
    ];
}

// Enviar respuesta JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>