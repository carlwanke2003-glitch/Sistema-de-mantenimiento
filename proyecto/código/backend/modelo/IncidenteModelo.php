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

    // Listar incidentes; los usuarios solo ven visibles, admin puede ver todo
    public function listar($soloVisibles = true) {
        if ($soloVisibles) {
            $sql = "SELECT id, descripcion, estado, fecha, equipo_id, visible FROM incidentes WHERE visible = 1";
            $stmt = $this->conn->prepare($sql);
        } else {
            $sql = "SELECT id, descripcion, estado, fecha, equipo_id, visible FROM incidentes";
            $stmt = $this->conn->prepare($sql);
        }
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

    // Cambiar visibilidad del incidente
    public function cambiarVisibilidad($id, $visible) {
        $sql = "UPDATE incidentes SET visible = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $visible, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Eliminar un incidente
    public function eliminar($id) {
        $sql = "DELETE FROM incidentes WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>