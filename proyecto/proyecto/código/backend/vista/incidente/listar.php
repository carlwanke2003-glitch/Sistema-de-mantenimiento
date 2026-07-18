<?php
// backend/vista/incidente/listar.php
// Vista para renderizar la lista de incidentes en formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

echo json_encode($incidentes);
?>
