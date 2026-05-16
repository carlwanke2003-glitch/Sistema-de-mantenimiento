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
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; color: #333; margin: 0; }
        .container { max-width: 500px; margin: 60px auto; background: #fff; padding: 32px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        h1 { margin-bottom: 18px; color: #004a91; }
        label { display: block; margin-top: 14px; font-weight: 600; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px 14px; margin-top: 6px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { width: 100%; padding: 12px; margin-top: 22px; border: none; border-radius: 6px; background: #004a91; color: #fff; font-size: 16px; cursor: pointer; }
        button:hover { background: #00356a; }
        .message { margin-top: 16px; color: #2e7d32; }
        .error { margin-top: 16px; color: #d32f2f; }
        .footer { margin-top: 24px; font-size: 14px; }
        .footer a { color: #004a91; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
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