<?php
// backend/vista/error.php
// Vista genérica para renderizar errores en formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

echo json_encode(['error' => $error]);
?>
