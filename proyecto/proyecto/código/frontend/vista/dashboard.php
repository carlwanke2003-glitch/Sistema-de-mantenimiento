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
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #fafafa; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; background: #004a91; color: #fff; }
        .header a { color: #fff; text-decoration: none; }
        .nav { background: #fff; border-bottom: 1px solid #ddd; padding: 12px 24px; }
        .nav a { margin-right: 18px; color: #004a91; text-decoration: none; font-weight: 600; }
        h1 { color: #222; margin-bottom: 10px; }
        h2 { margin: 0 0 12px; font-size: 20px; }
        .section { margin-bottom: 30px; padding: 0 24px; }
        .table-wrapper { overflow-x: auto; }
        .card { background: #fff; border-radius: 10px; padding: 18px 18px; margin-bottom: 18px; box-shadow: 0 5px 18px rgba(0,0,0,0.08); }
        .card label, .card input, .card select, .card button { display: block; width: 100%; margin-bottom: 12px; }
        .card input, .card select { padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .card button { width: auto; background: #004a91; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; }
        .card button:hover { background: #00356a; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: 600; }
        tr:nth-child(even) td { background-color: #fafafa; }
        tr:hover td { background-color: #f7f7f7; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <strong>PredictiveMaintain</strong>
            <span> · Bienvenido <?php echo htmlspecialchars(getCurrentUserName()); ?> (<?php echo htmlspecialchars(getCurrentUserRole()); ?>)</span>
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

    <?php if ($isAdmin): ?>
    <div class="section">
        <h2>Administración rápida</h2>
        <div class="card">
            <h3>Nuevo equipo</h3>
            <label for="equipoNombre">Nombre</label>
            <input id="equipoNombre" type="text">
            <label for="equipoHoras">Horas de uso</label>
            <input id="equipoHoras" type="number" min="0" value="0">
            <label for="equipoUmbral">Umbral</label>
            <input id="equipoUmbral" type="number" min="0" value="200">
            <button onclick="crearEquipo()">Agregar equipo</button>
        </div>
        <div class="card">
            <h3>Nuevo incidente</h3>
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
            <button onclick="crearIncidente()">Agregar incidente</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="section">
        <h2>Equipos que Necesitan Mantenimiento</h2>
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

    <div class="section">
        <h2>Todos los Equipos</h2>
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

    <div class="section">
        <h2>Lista de Incidentes</h2>
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