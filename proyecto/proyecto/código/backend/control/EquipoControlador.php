<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../modelo/EquipoModelo.php';
// ... el resto de tu clase ...
// EquipoControlador.php - Controlador para equipos
require_once '../modelo/EquipoModelo.php';

class EquipoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new EquipoModelo();
    }

    // Listar equipos
    public function listar() {
        $equipos = $this->modelo->listar();
        header('Content-Type: application/json');
        echo json_encode($equipos);
    }

    // Crear equipo
    public function crear() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['nombre']) && isset($data['horas_uso']) && isset($data['umbral'])) {
            $success = $this->modelo->crear($data['nombre'], $data['horas_uso'], $data['umbral']);
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }

    // Actualizar equipo
    public function actualizar() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['nombre']) && isset($data['horas_uso']) && isset($data['umbral'])) {
            $success = $this->modelo->actualizar($data['id'], $data['nombre'], $data['horas_uso'], $data['umbral']);
            header('Content-Type: application/json');
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
    case 'necesitan_mantenimiento':
        if ($method == 'GET') $controlador->necesitanMantenimiento();
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no encontrada']);
}
?>