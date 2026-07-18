<?php
// backend/vista/equipo/resultado.php
// Vista para renderizar el resultado de las acciones sobre equipos (creación, edición, eliminación) en formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

echo json_encode($resultado);
?>
