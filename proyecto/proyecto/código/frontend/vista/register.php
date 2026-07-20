<?php
session_start();
require_once __DIR__ . '/auth.php';
requireModelo('UsuarioModelo.php');

if (!empty($_SESSION['id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($nombre === '' || $email === '' || $password === '' || $confirm === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email no válido.';
    } elseif ($password !== $confirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $modelo = new UsuarioModelo();
        if ($modelo->buscarPorEmail($email)) {
            $error = 'Ya existe un usuario con ese email.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $created = $modelo->guardar($nombre, $email, $passwordHash);
            if ($created) {
                $usuario = $modelo->buscarPorEmail($email);
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['email'] = $usuario['email'];
                header('Location: dashboard.php');
                exit;
            }
            $error = 'No se pudo crear la cuenta. Intenta de nuevo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - PredictiveMaintain</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-stripe"></div>
    <div class="container">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="logo.png" alt="Logo" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid var(--accent-color); padding: 4px; background: #111;">
        </div>
        <h1>Crear cuenta nueva</h1>
        <form method="POST" action="register.php">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm">Confirmar contraseña</label>
            <input type="password" id="confirm" name="confirm" required>
            <button type="submit">Crear cuenta</button>
        </form>
        <?php if ($error !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success !== ''): ?>
            <div class="message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <div class="footer">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>.
        </div>
    </div>
</body>
</html>