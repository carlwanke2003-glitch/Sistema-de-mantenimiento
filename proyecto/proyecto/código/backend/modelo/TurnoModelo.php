<?php
// TurnoModelo.php - Modelo para la tabla turnos
class TurnoModelo {
    private $conn;

    public function __construct() {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $db = 'predictive_maintain';

        $this->conn = new mysqli($host, $user, $pass, $db);
        if ($this->conn->connect_error) {
            die('Error de conexión: ' . $this->conn->connect_error);
        }
    }

    public function listar($usuarioId = null) {
        if ($usuarioId === null) {
            $sql = 'SELECT t.id, t.descripcion, t.fecha, t.estado, t.usuario_id, u.nombre AS usuario_nombre FROM turnos t JOIN usuarios u ON t.usuario_id = u.id ORDER BY t.fecha ASC';
            $stmt = $this->conn->prepare($sql);
        } else {
            $sql = 'SELECT t.id, t.descripcion, t.fecha, t.estado, t.usuario_id, u.nombre AS usuario_nombre FROM turnos t JOIN usuarios u ON t.usuario_id = u.id WHERE t.usuario_id = ? ORDER BY t.fecha ASC';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $usuarioId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $turnos = [];
        while ($row = $result->fetch_assoc()) {
            $turnos[] = $row;
        }
        $stmt->close();
        return $turnos;
    }

    public function buscarPorId($id) {
        $sql = 'SELECT t.id, t.descripcion, t.fecha, t.estado, t.usuario_id, u.nombre AS usuario_nombre FROM turnos t JOIN usuarios u ON t.usuario_id = u.id WHERE t.id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $turno = $result->fetch_assoc();
        $stmt->close();
        return $turno ?: null;
    }

    public function crear($usuarioId, $descripcion, $fecha) {
        $sql = 'INSERT INTO turnos (usuario_id, descripcion, fecha) VALUES (?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iss', $usuarioId, $descripcion, $fecha);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function actualizar($id, $descripcion, $fecha, $estado) {
        $sql = 'UPDATE turnos SET descripcion = ?, fecha = ?, estado = ? WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssi', $descripcion, $fecha, $estado, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function eliminar($id) {
        $sql = 'DELETE FROM turnos WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>