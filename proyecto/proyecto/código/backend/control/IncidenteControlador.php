<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();

require_once '../modelo/IncidenteModelo.php';
// IncidenteControlador.php - Controlador para incidentes

class IncidenteControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new IncidenteModelo();
    }

    // Listar incidentes
    public function listar() {
        $soloVisibles = !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin';
        $incidentes = $this->modelo->listar($soloVisibles);
        header('Content-Type: application/json');
        echo json_encode($incidentes);
    }

    private function requireAdmin() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return false;
        }
        return true;
    }

    // Crear incidente
    public function crear() {
        if (!$this->requireAdmin()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['descripcion']) && isset($data['estado']) && isset($data['equipo_id'])) {
            $success = $this->modelo->crear($data['descripcion'], $data['estado'], $data['equipo_id']);
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }

    // Actualizar incidente
    public function actualizar() {
        if (!$this->requireAdmin()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['descripcion']) && isset($data['estado']) && isset($data['equipo_id'])) {
            $success = $this->modelo->actualizar($data['id'], $data['descripcion'], $data['estado'], $data['equipo_id']);
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }

    // Cambiar visibilidad de un incidente
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

    // Eliminar incidente
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
}

// Routing básico
$controlador = new IncidenteControlador();
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
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no encontrada']);
}
?>