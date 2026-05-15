<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../modelo/EquipoModelo.php';
// ... el resto de tu clase ...

// IncidenteControlador.php - Controlador para incidentes
require_once '../modelo/IncidenteModelo.php';

class IncidenteControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new IncidenteModelo();
    }

    // Listar incidentes
    public function listar() {
        $incidentes = $this->modelo->listar();
        header('Content-Type: application/json');
        echo json_encode($incidentes);
    }

    // Crear incidente
    public function crear() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['descripcion']) && isset($data['estado']) && isset($data['equipo_id'])) {
            $success = $this->modelo->crear($data['descripcion'], $data['estado'], $data['equipo_id']);
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }

    // Actualizar incidente
    public function actualizar() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['descripcion']) && isset($data['estado']) && isset($data['equipo_id'])) {
            $success = $this->modelo->actualizar($data['id'], $data['descripcion'], $data['estado'], $data['equipo_id']);
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
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
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no encontrada']);
}
?>