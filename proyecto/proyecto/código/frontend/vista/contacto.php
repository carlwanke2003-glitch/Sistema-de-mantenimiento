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
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f7f9fc; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; background: #004a91; color: #fff; }
        .header a { color: #fff; text-decoration: none; }
        .nav { background: #fff; border-bottom: 1px solid #ddd; padding: 12px 24px; }
        .nav a { margin-right: 18px; color: #004a91; text-decoration: none; font-weight: 600; }
        .container { padding: 24px; }
        .card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); max-width: 700px; margin: 0 auto; }
        label { display: block; margin: 14px 0 6px; font-weight: 600; }
        input, textarea { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        textarea { min-height: 140px; resize: vertical; }
        button { padding: 12px 18px; border: none; border-radius: 6px; background: #004a91; color: #fff; cursor: pointer; margin-top: 16px; }
        button:hover { background: #00356a; }
        .message { margin-top: 16px; color: #2e7d32; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <strong>PredictiveMaintain</strong>
            <span> · <?php echo htmlspecialchars(getCurrentUserName()); ?> (<?php echo htmlspecialchars(getCurrentUserRole()); ?>)</span>
        </div>
        <div><a href="logout.php">Cerrar sesión</a></div>
    </div>
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="turno.php">Turno</a>
        <a href="historial.php">Historial de Revisiones</a>
        <a href="pagar.php">Pagar</a>
        <a href="contacto.php">Contacto</a>
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