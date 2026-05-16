<?php
require_once 'auth.php';
requireModelo('PagoModelo.php');
requireAuth();
$userId = getCurrentUserId();
$isAdmin = isAdmin();
$success = '';
$pagoModel = new PagoModelo();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion'] ?? '');
    $monto = floatval($_POST['monto'] ?? 0);
    if ($descripcion === '' || $monto <= 0) {
        $success = 'Ingresa una descripción y un monto válido.';
    } else {
        if ($pagoModel->crear($userId, $descripcion, $monto)) {
            $success = 'Pago registrado correctamente.';
        } else {
            $success = 'No se pudo registrar el pago. Intenta de nuevo.';
        }
    }
}
$pagos = $pagoModel->listar($isAdmin ? null : $userId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar - PredictiveMaintain</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f7f9fc; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; background: #004a91; color: #fff; }
        .header a { color: #fff; text-decoration: none; }
        .nav { background: #fff; border-bottom: 1px solid #ddd; padding: 12px 24px; }
        .nav a { margin-right: 18px; color: #004a91; text-decoration: none; font-weight: 600; }
        .container { padding: 24px; }
        .card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); max-width: 700px; margin: 0 auto; }
        button { padding: 12px 18px; border: none; border-radius: 6px; background: #004a91; color: #fff; cursor: pointer; }
        button:hover { background: #00356a; }
        .success { color: #2e7d32; margin-top: 16px; }
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
            <h2>Pago de servicio</h2>
            <p>Hola <?php echo htmlspecialchars(getCurrentUserName()); ?>, aquí puedes registrar un pago para tu servicio de mantenimiento.</p>
            <?php if ($success !== ''): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if (!$isAdmin): ?>
                <form method="POST" action="pagar.php">
                    <label for="descripcion">Descripción del pago</label>
                    <input type="text" id="descripcion" name="descripcion" required>
                    <label for="monto">Monto</label>
                    <input type="number" step="0.01" id="monto" name="monto" required>
                    <button type="submit">Registrar pago</button>
                </form>
            <?php else: ?>
                <p>Como administrador puedes ver todos los pagos registrados abajo.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2><?php echo $isAdmin ? 'Pagos registrados' : 'Mis pagos'; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pagos) === 0): ?>
                        <tr><td colspan="5">No hay pagos registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pagos as $pago): ?>
                            <tr>
                                <td><?php echo intval($pago['id']); ?></td>
                                <td><?php echo htmlspecialchars($pago['descripcion']); ?></td>
                                <td><?php echo number_format($pago['monto'], 2); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($pago['fecha']))); ?></td>
                                <td><?php echo htmlspecialchars($pago['usuario_nombre']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>