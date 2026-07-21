<?php
require_once 'auth.php';
requireModelo('TurnoModelo.php');

requireAuth();
$turnoModel = new TurnoModelo();
$userId = getCurrentUserId();
$isAdmin = isAdmin();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'crear') {
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        if ($descripcion === '' || $fecha === '') {
            $error = 'Descripción y fecha son obligatorias.';
        } else {
            $fechaSql = date('Y-m-d H:i:s', strtotime($fecha));
            if ($turnoModel->crear($userId, $descripcion, $fechaSql)) {
                $success = 'Turno creado correctamente.';
            } else {
                $error = 'No se pudo registrar el turno. Intenta de nuevo.';
            }
        }
    } elseif ($action === 'actualizar') {
        $id = intval($_POST['id'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $estado = $_POST['estado'] ?? 'pendiente';
        $turno = $turnoModel->buscarPorId($id);
        if (!$turno || (!$isAdmin && $turno['usuario_id'] !== $userId)) {
            $error = 'No tienes permiso para editar este turno.';
        } elseif ($descripcion === '' || $fecha === '') {
            $error = 'Descripción y fecha son obligatorias.';
        } else {
            $fechaSql = date('Y-m-d H:i:s', strtotime($fecha));
            if ($turnoModel->actualizar($id, $descripcion, $fechaSql, $estado)) {
                $success = 'Turno actualizado correctamente.';
            } else {
                $error = 'No se pudo actualizar el turno.';
            }
        }
    } elseif ($action === 'eliminar') {
        $id = intval($_POST['id'] ?? 0);
        $turno = $turnoModel->buscarPorId($id);
        if (!$turno || (!$isAdmin && $turno['usuario_id'] !== $userId)) {
            $error = 'No tienes permiso para eliminar este turno.';
        } else {
            if ($turnoModel->eliminar($id)) {
                $success = 'Turno eliminado correctamente.';
            } else {
                $error = 'No se pudo eliminar el turno.';
            }
        }
    }
}

$turnos = $turnoModel->listar($isAdmin ? null : $userId);
$editTurno = null;
if (isset($_GET['editar'])) {
    $editId = intval($_GET['editar']);
    $candidate = $turnoModel->buscarPorId($editId);
    if ($candidate && ($isAdmin || $candidate['usuario_id'] === $userId)) {
        $editTurno = $candidate;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turnos - PredictiveMaintain</title>
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
        <a href="turno.php" class="active"><i class="uil uil-calendar-alt"></i> Turno</a>
        <a href="historial.php"><i class="uil uil-history"></i> Historial de Revisiones</a>
        <a href="pagar.php"><i class="uil uil-credit-card"></i> Pagar</a>
        <a href="contacto.php"><i class="uil uil-envelope"></i> Contacto</a>
    </div>
    <div class="container">
        <div class="card">
            <h2><?php echo $editTurno ? 'Editar turno' : 'Solicitar nuevo turno'; ?></h2>
            <?php if ($error !== ''): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <?php if ($success !== ''): ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
            <form method="POST" action="turno.php<?php echo $editTurno ? '?editar=' . intval($editTurno['id']) : ''; ?>">
                <input type="hidden" name="action" value="<?php echo $editTurno ? 'actualizar' : 'crear'; ?>">
                <?php if ($editTurno): ?>
                    <input type="hidden" name="id" value="<?php echo intval($editTurno['id']); ?>">
                <?php endif; ?>
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($editTurno['descripcion'] ?? ''); ?>" required>
                <label for="fecha">Fecha y hora</label>
                <input type="datetime-local" id="fecha" name="fecha" value="<?php echo htmlspecialchars($editTurno ? date('Y-m-d\TH:i', strtotime($editTurno['fecha'])) : ''); ?>" required>
                <?php if ($editTurno): ?>
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="pendiente"<?php echo $editTurno['estado'] === 'pendiente' ? ' selected' : ''; ?>>Pendiente</option>
                        <option value="confirmado"<?php echo $editTurno['estado'] === 'confirmado' ? ' selected' : ''; ?>>Confirmado</option>
                        <option value="cancelado"<?php echo $editTurno['estado'] === 'cancelado' ? ' selected' : ''; ?>>Cancelado</option>
                    </select>
                <?php endif; ?>
                <button type="submit"><?php echo $editTurno ? 'Guardar cambios' : 'Solicitar turno'; ?></button>
                <?php if ($editTurno): ?>
                    <a href="turno.php" style="margin-left: 12px; color: #004a91;">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h2><?php echo $isAdmin ? 'Todos los turnos' : 'Mis turnos'; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($turnos) === 0): ?>
                        <tr><td colspan="6">No hay turnos disponibles.</td></tr>
                    <?php else: ?>
                        <?php foreach ($turnos as $turno): ?>
                            <tr>
                                <td><?php echo intval($turno['id']); ?></td>
                                <td><?php echo htmlspecialchars($turno['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($turno['fecha']))); ?></td>
                                <td><?php echo htmlspecialchars($turno['estado']); ?></td>
                                <td><?php echo htmlspecialchars($turno['usuario_nombre']); ?></td>
                                <td class="actions">
                                    <?php if ($isAdmin || $turno['usuario_id'] === $userId): ?>
                                        <form method="GET" action="turno.php" style="display:inline;">
                                            <input type="hidden" name="editar" value="<?php echo intval($turno['id']); ?>">
                                            <button type="submit" style="background:#2563eb; padding:6px 10px;">Editar</button>
                                        </form>
                                        <form method="POST" action="turno.php" style="display:inline;" onsubmit="return confirm('¿Eliminar este turno?');">
                                            <input type="hidden" name="action" value="eliminar">
                                            <input type="hidden" name="id" value="<?php echo intval($turno['id']); ?>">
                                            <button type="submit" style="background:#dc2626; padding:6px 10px;">Eliminar</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color:#6b7280;">Sin permisos</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>