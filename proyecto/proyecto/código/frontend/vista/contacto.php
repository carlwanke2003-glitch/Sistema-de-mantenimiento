<?php
require_once 'auth.php';
requireModelo('MensajeModelo.php');
requireAuth();
$userId = getCurrentUserId();
$isAdmin = isAdmin();
$message = '';
$mensajeModel = new MensajeModelo();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($isAdmin && isset($_POST['action']) && $_POST['action'] === 'marcar_leido' && !empty($_POST['id'])) {
        $mensajeModel->marcarLeido(intval($_POST['id']));
        $message = 'Mensaje marcado como leído.';
    } else {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');
        if ($nombre && $email && $mensaje) {
            if ($mensajeModel->guardar($userId, $nombre, $email, $mensaje)) {
                $message = 'Gracias por contactarnos. Hemos recibido tu mensaje.';
            } else {
                $message = 'Ocurrió un error al enviar el mensaje.';
            }
        } else {
            $message = 'Por favor completa todos los campos.';
        }
    }
}
$mensajes = $isAdmin ? $mensajeModel->listar() : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - PredictiveMaintain</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-stripe"></div>
    <div class="header">
        <div>
            <strong><img src="logo.png" alt="Logo" style="width: 32px; height: 32px; vertical-align: middle; border-radius: 50%; margin-right: 8px; border: 1px solid var(--accent-color); background: #111; padding: 2px;"> PredictiveMaintain</strong>
            <span> · <i class="uil uil-user"></i> <?php echo htmlspecialchars(getCurrentUserName()); ?> (<?php echo htmlspecialchars(getCurrentUserRole()); ?>)</span>
        </div>
        <div><a href="logout.php"><i class="uil uil-signout"></i> Cerrar sesión</a></div>
    </div>
    <div class="nav">
        <a href="dashboard.php"><i class="uil uil-dashboard"></i> Dashboard</a>
        <a href="turno.php"><i class="uil uil-calendar-alt"></i> Turno</a>
        <a href="historial.php"><i class="uil uil-history"></i> Historial de Revisiones</a>
        <a href="pagar.php"><i class="uil uil-credit-card"></i> Pagar</a>
        <a href="contacto.php" class="active"><i class="uil uil-envelope"></i> Contacto</a>
    </div>
    <div class="container">
        <div class="card">
            <h2>Contacto</h2>
            <p>Escribe tu duda o comentario y el equipo de PredictiveMaintain te responderá pronto.</p>
            <?php if ($message !== ''): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if (!$isAdmin): ?>
                <form method="POST" action="contacto.php">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" required></textarea>
                    <button type="submit">Enviar mensaje</button>
                </form>
            <?php else: ?>
                <p>Mensajes recibidos:</p>
                <?php if (count($mensajes) === 0): ?>
                    <p>No hay mensajes nuevos.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                                <th>Leído</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mensajes as $item): ?>
                                <tr>
                                    <td><?php echo intval($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['usuario_nombre'] ?? 'Invitado'); ?></td>
                                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($item['email']); ?></td>
                                    <td><?php echo htmlspecialchars($item['mensaje']); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($item['creado_en']))); ?></td>
                                    <td><?php echo $item['leido'] === '1' || $item['leido'] === 1 ? 'Sí' : 'No'; ?></td>
                                    <td>
                                        <?php if ($item['leido'] === '0' || $item['leido'] === 0): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="marcar_leido">
                                                <input type="hidden" name="id" value="<?php echo intval($item['id']); ?>">
                                                <button type="submit">Marcar leído</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>