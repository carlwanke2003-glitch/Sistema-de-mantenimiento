<?php
// IncidenteModelo.php - Modelo para la tabla incidentes
class IncidenteModelo {
    private $conn;

    public function __construct() {
        // Configuración de la base de datos
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $db = 'predictive_maintain';

        $this->conn = new mysqli($host, $user, $pass, $db);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    // Listar todos los incidentes
    public function listar() {
        $sql = "SELECT id, descripcion, estado, fecha, equipo_id FROM incidentes";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $incidentes = [];
        while ($row = $result->fetch_assoc()) {
            $incidentes[] = $row;
        }
        $stmt->close();
        return $incidentes;
    }

    // Crear un nuevo incidente
    public function crear($descripcion, $estado, $equipo_id) {
        $sql = "INSERT INTO incidentes (descripcion, estado, equipo_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $descripcion, $estado, $equipo_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Actualizar un incidente
    public function actualizar($id, $descripcion, $estado, $equipo_id) {
        $sql = "UPDATE incidentes SET descripcion = ?, estado = ?, equipo_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $descripcion, $estado, $equipo_id, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>