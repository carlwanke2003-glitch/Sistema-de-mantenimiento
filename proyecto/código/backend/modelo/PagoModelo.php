<?php
// PagoModelo.php - Modelo para la tabla pagos
class PagoModelo {
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
            $sql = 'SELECT p.id, p.descripcion, p.monto, p.fecha, p.usuario_id, u.nombre AS usuario_nombre FROM pagos p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.fecha DESC';
            $stmt = $this->conn->prepare($sql);
        } else {
            $sql = 'SELECT p.id, p.descripcion, p.monto, p.fecha, p.usuario_id, u.nombre AS usuario_nombre FROM pagos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.usuario_id = ? ORDER BY p.fecha DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $usuarioId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $pagos = [];
        while ($row = $result->fetch_assoc()) {
            $pagos[] = $row;
        }
        $stmt->close();
        return $pagos;
    }

    public function crear($usuarioId, $descripcion, $monto) {
        $sql = 'INSERT INTO pagos (usuario_id, descripcion, monto) VALUES (?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('isd', $usuarioId, $descripcion, $monto);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>