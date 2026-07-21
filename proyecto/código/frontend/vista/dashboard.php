<?php
require_once 'auth.php';
requireAuth();
$isAdmin = isAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PredictiveMaintain</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-stripe"></div>
    <div class="header">
        <div>
            <strong><img src="logo.png" alt="Logo" style="width: 32px; height: 32px; vertical-align: middle; border-radius: 50%; margin-right: 8px; border: 1px solid var(--accent-color); background: #111; padding: 2px;"> PredictiveMaintain</strong>
            <span> · <i class="uil uil-user"></i> Bienvenido <?php echo htmlspecialchars(getCurrentUserName()); ?> (<?php echo htmlspecialchars(getCurrentUserRole()); ?>)</span>
        </div>
        <div><a href="logout.php"><i class="uil uil-signout"></i> Cerrar sesión</a></div>
    </div>
    <div class="nav">
        <a href="dashboard.php" class="active"><i class="uil uil-dashboard"></i> Dashboard</a>
        <a href="turno.php"><i class="uil uil-calendar-alt"></i> Turno</a>
        <a href="historial.php"><i class="uil uil-history"></i> Historial de Revisiones</a>
        <a href="pagar.php"><i class="uil uil-credit-card"></i> Pagar</a>
        <a href="contacto.php"><i class="uil uil-envelope"></i> Contacto</a>
    </div>

    <?php if ($isAdmin): ?>
    <div class="section">
        <h2><i class="uil uil-setting"></i> Administración rápida</h2>
        <div class="card">
            <h3><i class="uil uil-plus-circle"></i> Nuevo equipo</h3>
            <label for="equipoNombre">Nombre</label>
            <input id="equipoNombre" type="text">
            <label for="equipoHoras">Horas de uso</label>
            <input id="equipoHoras" type="number" min="0" value="0">
            <label for="equipoUmbral">Umbral</label>
            <input id="equipoUmbral" type="number" min="0" value="200">
            <button onclick="crearEquipo()"><i class="uil uil-plus"></i> Agregar equipo</button>
        </div>
        <div class="card">
            <h3><i class="uil uil-exclamation-triangle"></i> Nuevo incidente</h3>
            <label for="incidenteDescripcion">Descripción</label>
            <input id="incidenteDescripcion" type="text">
            <label for="incidenteEstado">Estado</label>
            <select id="incidenteEstado">
                <option value="abierto">Abierto</option>
                <option value="en_progreso">En progreso</option>
                <option value="cerrado">Cerrado</option>
            </select>
            <label for="incidenteEquipoId">Equipo ID</label>
            <input id="incidenteEquipoId" type="number" min="1" value="1">
            <button onclick="crearIncidente()"><i class="uil uil-plus"></i> Agregar incidente</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="dashboard-grid">
        <div class="section">
            <h2><i class="uil uil-exclamation-octagon"></i> Equipos que Necesitan Mantenimiento</h2>
            <div class="table-wrapper">
                <table id="equipos-criticos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Horas de Uso</th>
                            <th>Umbral</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos cargados por JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section">
            <h2><i class="uil uil-list-ui-alt"></i> Todos los Equipos</h2>
            <div class="table-wrapper">
                <table id="equipos-todos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Horas de Uso</th>
                            <th>Umbral</th>
                            <?php if ($isAdmin): ?>
                                <th>Visible</th>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos cargados por JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="section">
        <h2><i class="uil uil-wrench"></i> Lista de Incidentes</h2>
        <div class="table-wrapper">
            <table id="incidentes">
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
                    <!-- Datos cargados por JS -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const isAdmin = <?php echo $isAdmin ? 'true' : 'false'; ?>;

        async function apiFetch(url, options = {}) {
            try {
                const response = await fetch(url, options);
                return await response.json();
            } catch (error) {
                console.error('Error en la API:', error);
                return null;
            }
        }

        async function cargarDatos(url, tablaId, campos, juntarAdmin = false) {
            const data = await apiFetch(url);
            if (!data) return;
            const tbody = document.querySelector(`#${tablaId} tbody`);
            tbody.innerHTML = '';
            data.forEach(item => {
                const row = document.createElement('tr');
                campos.forEach(campo => {
                    const cell = document.createElement('td');
                    cell.textContent = item[campo];
                    row.appendChild(cell);
                });
                if (juntarAdmin && isAdmin) {
                    const visibleCell = document.createElement('td');
                    visibleCell.textContent = item.visible === '1' || item.visible === 1 ? 'Sí' : 'No';
                    row.appendChild(visibleCell);
                    const actionsCell = document.createElement('td');
                    actionsCell.innerHTML = `<button onclick="toggleVisible('${tablaId}', ${item.id}, ${item.visible === '1' || item.visible === 1 ? 0 : 1})">${item.visible === '1' || item.visible === 1 ? 'Ocultar' : 'Mostrar'}</button>`;
                    if (tablaId === 'equipos-todos') {
                        actionsCell.innerHTML += ` <button onclick="editarEquipo(${item.id})">Editar</button> <button onclick="eliminarEquipo(${item.id})">Eliminar</button>`;
                    } else {
                        actionsCell.innerHTML += ` <button onclick="editarIncidente(${item.id})">Editar</button> <button onclick="eliminarIncidente(${item.id})">Eliminar</button>`;
                    }
                    row.appendChild(actionsCell);
                }
                tbody.appendChild(row);
            });
        }

        async function reloadTablas() {
            cargarDatos('../../backend/control/EquipoControlador.php?action=necesitan_mantenimiento', 'equipos-criticos', ['id', 'nombre', 'horas_uso', 'umbral']);
            cargarDatos('../../backend/control/EquipoControlador.php?action=listar', 'equipos-todos', ['id', 'nombre', 'horas_uso', 'umbral'], true);
            cargarDatos('../../backend/control/IncidenteControlador.php?action=listar', 'incidentes', ['id', 'descripcion', 'estado', 'fecha', 'equipo_id'], true);
        }

        async function crearEquipo() {
            const nombre = document.getElementById('equipoNombre').value.trim();
            const horas = parseInt(document.getElementById('equipoHoras').value, 10);
            const umbral = parseInt(document.getElementById('equipoUmbral').value, 10);
            if (!nombre) return alert('Ingrese el nombre del equipo.');
            const result = await apiFetch('../../backend/control/EquipoControlador.php?action=crear', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({nombre, horas_uso: horas, umbral})
            });
            if (result && result.success) {
                alert('Equipo creado.');
                reloadTablas();
            }
        }

        async function crearIncidente() {
            const descripcion = document.getElementById('incidenteDescripcion').value.trim();
            const estado = document.getElementById('incidenteEstado').value;
            const equipo_id = parseInt(document.getElementById('incidenteEquipoId').value, 10);
            if (!descripcion) return alert('Ingrese la descripción del incidente.');
            const result = await apiFetch('../../backend/control/IncidenteControlador.php?action=crear', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({descripcion, estado, equipo_id})
            });
            if (result && result.success) {
                alert('Incidente creado.');
                reloadTablas();
            }
        }

        async function editarEquipo(id) {
            const nombre = prompt('Nuevo nombre del equipo:');
            if (nombre === null) return;
            const horas = parseInt(prompt('Horas de uso:'), 10);
            const umbral = parseInt(prompt('Umbral:'), 10);
            if (!nombre || isNaN(horas) || isNaN(umbral)) return alert('Datos incorrectos.');
            const result = await apiFetch('../../backend/control/EquipoControlador.php?action=actualizar', {
                method: 'PUT',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id, nombre, horas_uso: horas, umbral})
            });
            if (result && result.success) {
                alert('Equipo actualizado.');
                reloadTablas();
            }
        }

        async function editarIncidente(id) {
            const descripcion = prompt('Nueva descripción del incidente:');
            if (descripcion === null) return;
            const estado = prompt('Estado (abierto, en_progreso, cerrado):');
            const equipo_id = parseInt(prompt('ID del equipo:'), 10);
            if (!descripcion || !estado || isNaN(equipo_id)) return alert('Datos incorrectos.');
            const result = await apiFetch('../../backend/control/IncidenteControlador.php?action=actualizar', {
                method: 'PUT',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id, descripcion, estado, equipo_id})
            });
            if (result && result.success) {
                alert('Incidente actualizado.');
                reloadTablas();
            }
        }

        async function eliminarEquipo(id) {
            if (!confirm('¿Eliminar este equipo?')) return;
            const result = await apiFetch('../../backend/control/EquipoControlador.php?action=eliminar', {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id})
            });
            if (result && result.success) {
                alert('Equipo eliminado.');
                reloadTablas();
            }
        }

        async function eliminarIncidente(id) {
            if (!confirm('¿Eliminar este incidente?')) return;
            const result = await apiFetch('../../backend/control/IncidenteControlador.php?action=eliminar', {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id})
            });
            if (result && result.success) {
                alert('Incidente eliminado.');
                reloadTablas();
            }
        }

        async function toggleVisible(tabla, id, visible) {
            const controller = tabla === 'equipos-todos' ? 'EquipoControlador' : 'IncidenteControlador';
            const result = await apiFetch(`../../backend/control/${controller}.php?action=visibilidad`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id, visible})
            });
            if (result && result.success) {
                reloadTablas();
            }
        }

        reloadTablas();
    </script>
</body>
</html>