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
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; color: #333; margin: 0; }
        .container { max-width: 420px; margin: 80px auto; background: #fff; padding: 32px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        h1 { margin-bottom: 18px; color: #004a91; }
        label { display: block; margin-top: 14px; font-weight: 600; }
        input[type="email"], input[type="password"] { width: 100%; padding: 12px 14px; margin-top: 6px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { width: 100%; padding: 12px; margin-top: 22px; border: none; border-radius: 6px; background: #004a91; color: #fff; font-size: 16px; cursor: pointer; }
        button:hover { background: #00356a; }
        .error { margin-top: 16px; color: #d32f2f; }
        .footer { margin-top: 24px; font-size: 14px; }
        .footer a { color: #004a91; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
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