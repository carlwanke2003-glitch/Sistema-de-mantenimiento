<?php
session_start();
require_once __DIR__ . '/auth.php';
requireModelo('UsuarioModelo.php');

if (!empty($_SESSION['id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Por favor ingrese email y contraseña.';
    } else {
        $modelo = new UsuarioModelo();
        $usuario = $modelo->buscarPorEmail($email);
        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['email'] = $usuario['email'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email o contraseña inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - PredictiveMaintain</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-stripe"></div>
    <div class="container">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="logo.png" alt="Logo" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid var(--accent-color); padding: 4px; background: #111;">
        </div>
        <h1>Iniciar sesión</h1>
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if ($error !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <div class="footer">
            ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.
        </div>
    </div>
</body>
</html>