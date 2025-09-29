<?php
/**
 * Configuración y conexión a la base de datos MySQL
 * Especialidades de Clínica
 */

class DatabaseConnection {
    private $host = 'localhost';
    private $dbname = 'clinica_especialidades';
    private $username = 'root';
    private $password = 'root';
    private $charset = 'utf8mb4';
    private $pdo = null;

    /**
     * Obtener la conexión PDO a la base de datos
     */
    public function getConnection() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                
            } catch (PDOException $e) {
                error_log("Error de conexión a la base de datos: " . $e->getMessage());
                throw new Exception("Error al conectar con la base de datos");
            }
        }
        
        return $this->pdo;
    }

    /**
     * Cerrar la conexión
     */
    public function closeConnection() {
        $this->pdo = null;
    }

    /**
     * Verificar si la conexión está activa
     */
    public function isConnected() {
        try {
            if ($this->pdo === null) {
                return false;
            }
            $this->pdo->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

/**
 * Función helper para obtener una instancia de conexión
 */
function getDB() {
    static $db = null;
    if ($db === null) {
        $db = new DatabaseConnection();
    }
    return $db->getConnection();
}
?>