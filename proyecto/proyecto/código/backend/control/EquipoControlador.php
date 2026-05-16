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
        header('Content-Type: application/json');
        echo json_encode($equipos);
    }

    private function requireAdmin() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
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
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
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
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
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
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
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
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }

    // Equipos que necesitan mantenimiento
    public function necesitanMantenimiento() {
        $equipos = $this->modelo->equiposNecesitanMantenimiento();
        header('Content-Type: application/json');
        echo json_encode($equipos);
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