<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();

require_once '../modelo/EquipoModelo.php';
// EquipoControlador.php - Controlador para equipos

class EquipoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new EquipoModelo();
    }

    // Listar equipos
    public function listar() {
        $soloVisibles = !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin';
        $equipos = $this->modelo->listar($soloVisibles);
        require_once '../vista/equipo/listar.php';
    }

    private function requireAdmin() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            http_response_code(403);
            $error = 'Acceso denegado';
            require_once '../vista/error.php';
            return false;
        }
        return true;
    }

    // Crear equipo
    public function crear() {
        if (!$this->requireAdmin()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['nombre']) && isset($data['horas_uso']) && isset($data['umbral'])) {
            $success = $this->modelo->crear($data['nombre'], $data['horas_uso'], $data['umbral']);
            $resultado = ['success' => $success];
            require_once '../vista/equipo/resultado.php';
        } else {
            http_response_code(400);
            $error = 'Datos incompletos';
            require_once '../vista/error.php';
        }
    }

    // Actualizar equipo
    public function actualizar() {
        if (!$this->requireAdmin()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['nombre']) && isset($data['horas_uso']) && isset($data['umbral'])) {
            $success = $this->modelo->actualizar($data['id'], $data['nombre'], $data['horas_uso'], $data['umbral']);
            $resultado = ['success' => $success];
            require_once '../vista/equipo/resultado.php';
        } else {
            http_response_code(400);
            $error = 'Datos incompletos';
            require_once '../vista/error.php';
        }
    }

    // Eliminar equipo
    public function eliminar() {
        if (!$this->requireAdmin()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $success = $this->modelo->eliminar($data['id']);
            $resultado = ['success' => $success];
            require_once '../vista/equipo/resultado.php';
        } else {
            http_response_code(400);
            $error = 'ID requerido';
            require_once '../vista/error.php';
        }
    }

    // Cambiar visibilidad de un equipo
    public function cambiarVisibilidad() {
        if (!$this->requireAdmin()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['visible'])) {
            $success = $this->modelo->cambiarVisibilidad($data['id'], $data['visible']);
            $resultado = ['success' => $success];
            require_once '../vista/equipo/resultado.php';
        } else {
            http_response_code(400);
            $error = 'Datos incompletos';
            require_once '../vista/error.php';
        }
    }

    // Equipos que necesitan mantenimiento
    public function necesitanMantenimiento() {
        $equipos = $this->modelo->equiposNecesitanMantenimiento();
        require_once '../vista/equipo/necesitan_mantenimiento.php';
    }
}

// Routing básico
$controlador = new EquipoControlador();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'listar';

switch ($action) {
    case 'listar':
        if ($method == 'GET') $controlador->listar();
        break;
    case 'crear':
        if ($method == 'POST') $controlador->crear();
        break;
    case 'actualizar':
        if ($method == 'PUT') $controlador->actualizar();
        break;
    case 'eliminar':
        if ($method == 'DELETE') $controlador->eliminar();
        break;
    case 'visibilidad':
        if ($method == 'POST') $controlador->cambiarVisibilidad();
        break;
    case 'necesitan_mantenimiento':
        if ($method == 'GET') $controlador->necesitanMantenimiento();
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no encontrada']);
}
?>