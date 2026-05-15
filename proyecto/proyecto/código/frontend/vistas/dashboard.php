<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// dashboard.php - Dashboard simple para PredictiveMaintain
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PredictiveMaintain</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .section { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Dashboard PredictiveMaintain</h1>

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
        <h2>Lista de Incidentes</h2>
        <table id="incidentes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Equipo ID</th>
                </tr>
            </thead>
            <tbody>
                <!-- Datos cargados por JS -->
            </tbody>
        </table>
    </div>

    <script>
        // Función para cargar datos de la API
        async function cargarDatos(url, tablaId, campos) {
            try {
                const response = await fetch(url);
                const data = await response.json();
                const tbody = document.querySelector(`#${tablaId} tbody`);
                tbody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    campos.forEach(campo => {
                        const cell = document.createElement('td');
                        cell.textContent = item[campo];
                        row.appendChild(cell);
                    });
                    tbody.appendChild(row);
                });
            } catch (error) {
                console.error('Error cargando datos:', error);
            }
        }

        // Cargar equipos críticos
        cargarDatos('../../backend/control/EquipoControlador.php?action=necesitan_mantenimiento', 'equipos-criticos', ['id', 'nombre', 'horas_uso', 'umbral']);

        // Cargar incidentes
        cargarDatos('../../backend/control/IncidenteControlador.php?action=listar', 'incidentes', ['id', 'descripcion', 'estado', 'fecha', 'equipo_id']);
    </script>
</body>
</html>