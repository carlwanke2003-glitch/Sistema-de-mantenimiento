<?php
// UsuarioModelo.php - Modelo para la tabla usuarios
class UsuarioModelo {
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

    public function buscarPorEmail($email) {
        $sql = 'SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        return $usuario ?: null;
    }

    public function guardar($nombre, $email, $passwordHash, $rol = 'usuario') {
        $sql = 'INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssss', $nombre, $email, $passwordHash, $rol);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>