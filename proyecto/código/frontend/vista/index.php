<?php
session_start();
if (!empty($_SESSION['id'])) {
    header('Location: dashboard.php');
    exit;
}
header('Location: login.php');
exit;
?>