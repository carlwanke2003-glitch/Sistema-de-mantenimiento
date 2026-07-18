<?php
// backend/vista/equipo/necesitan_mantenimiento.php
// Vista para renderizar la lista de equipos que requieren mantenimiento en formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

echo json_encode($equipos);
?>
