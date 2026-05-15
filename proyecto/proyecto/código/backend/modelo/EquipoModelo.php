<?php
// EquipoModelo.php - Modelo para la tabla equipos
class EquipoModelo {
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

    // Listar todos los equipos
    public function listar() {
        $sql = "SELECT id, nombre, horas_uso, umbral FROM equipos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $equipos = [];
        while ($row = $result->fetch_assoc()) {
            $equipos[] = $row;
        }
        $stmt->close();
        return $equipos;
    }

    // Crear un nuevo equipo
    public function crear($nombre, $horas_uso, $umbral) {
        $sql = "INSERT INTO equipos (nombre, horas_uso, umbral) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $nombre, $horas_uso, $umbral);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Actualizar un equipo
    public function actualizar($id, $nombre, $horas_uso, $umbral) {
        $sql = "UPDATE equipos SET nombre = ?, horas_uso = ?, umbral = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $nombre, $horas_uso, $umbral, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Obtener equipos que necesitan mantenimiento
    public function equiposNecesitanMantenimiento() {
        $sql = "CALL sp_equipos_necesitan_mantenimiento()";
        $result = $this->conn->query($sql);
        $equipos = [];
        while ($row = $result->fetch_assoc()) {
            $equipos[] = $row;
        }
        return $equipos;
    }
}
?>