<?php
// MensajeModelo.php - Modelo para la tabla mensajes
class MensajeModelo {
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

    public function listar($soloNoLeidos = false) {
        if ($soloNoLeidos) {
            $sql = 'SELECT m.id, m.usuario_id, m.nombre, m.email, m.mensaje, m.creado_en, m.leido, u.nombre AS usuario_nombre FROM mensajes m LEFT JOIN usuarios u ON m.usuario_id = u.id WHERE m.leido = 0 ORDER BY m.creado_en DESC';
        } else {
            $sql = 'SELECT m.id, m.usuario_id, m.nombre, m.email, m.mensaje, m.creado_en, m.leido, u.nombre AS usuario_nombre FROM mensajes m LEFT JOIN usuarios u ON m.usuario_id = u.id ORDER BY m.creado_en DESC';
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $mensajes = [];
        while ($row = $result->fetch_assoc()) {
            $mensajes[] = $row;
        }
        $stmt->close();
        return $mensajes;
    }

    public function guardar($usuarioId, $nombre, $email, $mensaje) {
        $sql = 'INSERT INTO mensajes (usuario_id, nombre, email, mensaje) VALUES (?, ?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('isss', $usuarioId, $nombre, $email, $mensaje);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function marcarLeido($id) {
        $sql = 'UPDATE mensajes SET leido = 1 WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>