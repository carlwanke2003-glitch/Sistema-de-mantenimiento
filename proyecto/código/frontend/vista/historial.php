<?php
require_once 'auth.php';
requireModelo('TurnoModelo.php');
requireModelo('IncidenteModelo.php');

requireAuth();
$userId = getCurrentUserId();
$isAdmin = isAdmin();
$turnoModel = new TurnoModelo();
$incidenteModel = new IncidenteModelo();
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    if (isset($_POST['action']) && $_POST['action'] === 'eliminar_incidente' && isset($_POST['id'])) {
        $incidenteModel->eliminar(intval($_POST['id']));
        $mensaje = 'Incidente eliminado.';
    } elseif (isset($_POST['action']) && $_POST['action'] === 'toggle_incidente' && isset($_POST['id']) && isset($_POST['visible'])) {
        $incidenteModel->cambiarVisibilidad(intval($_POST['id']), intval($_POST['visible']));
        $mensaje = 'Visibilidad del incidente actualizada.';
    }
}
$turnos = $turnoModel->listar($isAdmin ? null : $userId);
$incidentes = $incidenteModel->listar($isAdmin ? false : true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Revisiones - PredictiveMaintain</title>
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
        <a href="historial.php" class="active"><i class="uil uil-history"></i> Historial de Revisiones</a>
        <a href="pagar.php"><i class="uil uil-credit-card"></i> Pagar</a>
        <a href="contacto.php"><i class="uil uil-envelope"></i> Contacto</a>
    </div>
    <div class="container">
        <?php if ($mensaje !== ''): ?>
            <div class="success">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <div class="card">
            <h2><?php echo $isAdmin ? 'Todos los turnos registrados' : 'Mis turnos registrados'; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($turnos) === 0): ?>
                        <tr><td colspan="5">No hay turnos registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($turnos as $turno): ?>
                            <tr>
                                <td><?php echo intval($turno['id']); ?></td>
                                <td><?php echo htmlspecialchars($turno['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($turno['fecha']))); ?></td>
                                <td><?php echo htmlspecialchars($turno['estado']); ?></td>
                                <td><?php echo htmlspecialchars($turno['usuario_nombre']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Historial de incidentes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Equipo ID</th>
                        <?php if ($isAdmin): ?>
                            <th>Visible</th>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($incidentes) === 0): ?>
                        <tr><td colspan="5">No hay incidentes registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($incidentes as $incidente): ?>
                            <tr>
                                <td><?php echo intval($incidente['id']); ?></td>
                                <td><?php echo htmlspecialchars($incidente['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($incidente['estado']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($incidente['fecha']))); ?></td>
                                <td><?php echo intval($incidente['equipo_id']); ?></td>
                                <?php if ($isAdmin): ?>
                                    <td><?php echo $incidente['visible'] === '1' || $incidente['visible'] === 1 ? 'Sí' : 'No'; ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="toggle_incidente">
                                            <input type="hidden" name="id" value="<?php echo intval($incidente['id']); ?>">
                                            <input type="hidden" name="visible" value="<?php echo $incidente['visible'] === '1' || $incidente['visible'] === 1 ? 0 : 1; ?>">
                                            <button type="submit"><?php echo $incidente['visible'] === '1' || $incidente['visible'] === 1 ? 'Ocultar' : 'Mostrar'; ?></button>
                                        </form>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este incidente?');">
                                            <input type="hidden" name="action" value="eliminar_incidente">
                                            <input type="hidden" name="id" value="<?php echo intval($incidente['id']); ?>">
                                            <button type="submit">Eliminar</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>