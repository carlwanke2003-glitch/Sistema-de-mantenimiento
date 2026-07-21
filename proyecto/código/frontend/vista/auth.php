<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAuth() {
    if (empty($_SESSION['id'])) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header('Location: dashboard.php');
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function getCurrentUserId() {
    return $_SESSION['id'] ?? null;
}

function getCurrentUserName() {
    return $_SESSION['nombre'] ?? 'Invitado';
}

function getCurrentUserRole() {
    return $_SESSION['rol'] ?? 'usuario';
}

function resolveBackendModel($fileName) {
    $candidates = [
        __DIR__ . '/../../backend/modelo/' . $fileName,
        __DIR__ . '/../backend/modelo/' . $fileName,
        __DIR__ . '/../../../backend/modelo/' . $fileName,
        __DIR__ . '/../../../../backend/modelo/' . $fileName,
    ];
    foreach ($candidates as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }
    die('No se encontró el archivo de modelo: ' . htmlspecialchars($fileName));
}

function requireModelo($fileName) {
    require_once resolveBackendModel($fileName);
}
?>