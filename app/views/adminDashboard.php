<?php
require_once __DIR__ . '/../models/Resultado.php';

// Definir la fecha actual por defecto, HORARIO CIUDAD DE MEXICO
date_default_timezone_set('America/Mexico_City');
$fechaHoy = date('Y-m-d');

// Obtener los filtros enviados por GET para la tabla
$instrumentoTabla = $_GET['instrumento'] ?? 'todos';
$fechaInicioTabla = $_GET['fecha_inicio'] ?? $fechaHoy;
$fechaFinTabla = $_GET['fecha_fin'] ?? $fechaHoy;
$horaInicioTabla = $_GET['hora_inicio'] ?? '00:00';
$horaFinTabla = $_GET['hora_fin'] ?? '23:59';
$limiteTabla = $_GET['limite'] ?? 5;

// Obtener los filtros enviados por GET para las gráficas
$instrumentoGrafica = $_GET['chart-instrumento'] ?? 'todos';
$fechaInicioGrafica = $_GET['chart-fecha_inicio'] ?? $fechaHoy;
$fechaFinGrafica = $_GET['chart-fecha_fin'] ?? $fechaHoy;
$horaInicioGrafica = $_GET['chart-hora_inicio'] ?? '00:00';
$horaFinGrafica = $_GET['chart-hora_fin'] ?? '23:59';

// Crear instancia del modelo Resultado
$resultadoModel = new Resultado();

// Filtrar los resultados para la tabla
$resultadosTabla = $resultadoModel->filtrarResultadosPorRangoFechaHora($instrumentoTabla, $fechaInicioTabla, $fechaFinTabla, $horaInicioTabla, $horaFinTabla, $limiteTabla);

// Filtrar los resultados para las gráficas
$resultadosGrafica = $resultadoModel->filtrarResultadosPorRangoFechaHora($instrumentoGrafica, $fechaInicioGrafica, $fechaFinGrafica, $horaInicioGrafica, $horaFinGrafica, null);

// Inicializar contadores para los niveles de cada instrumento
$siscoEstres = ['Bajo' => 0, 'Medio' => 0, 'Alto' => 0];
$dassEstres = ['Normal' => 0, 'Leve' => 0, 'Moderado' => 0, 'Severo' => 0, 'Extremadamente Severo' => 0];
$dassAnsiedad = ['Normal' => 0, 'Leve' => 0, 'Moderado' => 0, 'Severo' => 0, 'Extremadamente Severo' => 0];
$dassDepresion = ['Normal' => 0, 'Leve' => 0, 'Moderado' => 0, 'Severo' => 0, 'Extremadamente Severo' => 0];

// Procesar los resultados para preparar los datos de las gráficas
foreach ($resultadosGrafica as $resultado) {
    if ($resultado->nivel_de_ansiedad === null) {
        $siscoEstres[$resultado->nivel_de_estres]++;
    } else {
        $dassEstres[$resultado->nivel_de_estres]++;
        $dassAnsiedad[$resultado->nivel_de_ansiedad]++;
        $dassDepresion[$resultado->nivel_de_depresion]++;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styleAdminDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e6f7ff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h1 {
            color: #004d4d;
        }

        .nav ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav ul li {
            margin-left: 20px;
        }

        .nav ul li a {
            text-decoration: none;
            color: #007b80;
            font-weight: bold;
            padding: 8px 15px;
            background-color: #e6f7ff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav ul li a:hover {
            background-color: #b3e0e6;
        }

        /* Tabs Styles */
        .tabs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background-color: #e6f7ff;
            border-radius: 8px;
            overflow: hidden;
        }

        .tabs button {
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            background-color: #007b80;
            color: #fff;
            border: none;
            border-radius: 0;
            flex: 1;
            text-align: center;
            transition: background-color 0.3s;
        }

        .tabs button.active {
            background-color: #004d4d;
        }

        /* Filters Section */
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .filters-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            justify-content: flex-start;
        }

        .apply-filters-button,
        .reset-button {
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .apply-filters-button {
            background: linear-gradient(135deg, #007b80, #00b3a6);
            color: #fff;
            border: none;
        }

        .apply-filters-button:hover {
            background: linear-gradient(135deg, #005f5f, #007b80);
            transform: translateY(-2px);
        }

        .reset-button {
            background-color: #ff6b6b;
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            display: inline-block;
        }

        .reset-button:hover {
            background-color: #e63946;
            transform: translateY(-2px);
        }

        .chart-type-selector {
            margin-top: 15px;
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .filters-row {
                flex-direction: column;
            }

            .filter-group {
                min-width: 100%;
            }
        }

        /* Table Styles */
        .results table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .results th,
        .results td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .results th {
            background-color: #e6f7ff;
            color: #004d4d;
        }

        .results td {
            background-color: #ffffff;
        }

        /* Charts Section */
        .charts {
            margin-top: 20px;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .chart-container {
            max-width: 450px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .chart-type-selector {
            margin-top: 15px;
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }

        .chart-type-selector button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007b80;
            color: #fff;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .chart-type-selector button:hover {
            background-color: #005f5f;
        }

        .chart-info {
            margin-top: 10px;
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .box {
            width: 200px;
        }
        .minibox {
            width: 40px;
        }
        .width {
            width: 100%;
        }
        .max {
            max-width: 100%;
        }

        /* Ajustes generales para los botones */
        .apply-filters-button,
        .reset-button,
        .chart-type-selector button {
            padding: 8px 20px; /* Reducción en el padding para hacerlos más compactos */
            font-size: 0.9rem; /* Tamaño de fuente ajustado */
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            text-align: center;
        }

        /* Estilo para el botón de "Aplicar Filtros" */
        .apply-filters-button {
            background: linear-gradient(135deg, #007b80, #00b3a6);
            color: #fff;
            border: none;
        }

        .apply-filters-button:hover {
            background: linear-gradient(135deg, #005f5f, #007b80);
            transform: translateY(-2px);
        }

        /* Estilo para el botón de "Restablecer Filtros" */
        .reset-button {
            background-color: #ff6b6b;
            color: #fff;
            text-decoration: none;
            padding: 8px 20px;
            display: inline-block;
        }

        .reset-button:hover {
            background-color: #e63946;
            transform: translateY(-2px);
        }

        /* Estilo para los botones de selección de tipo de gráfico */
        .chart-type-selector button {
            padding: 8px 20px;
            font-size: 0.9rem;
            background-color: #007b80;
            color: #fff;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .chart-type-selector button:hover {
            background-color: #005f5f;
            transform: translateY(-2px);
        }

        /* Asegurarse de que los botones tengan la misma altura */
        .filter-buttons,
        .chart-type-selector {
            display: flex;
            gap: 10px;
            align-items: center; /* Alinear los botones de manera uniforme */
        }

        .filter-buttons button,
        .chart-type-selector button {
            min-width: 150px; /* Establecer un ancho mínimo para mayor consistencia */
        }

    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1><img class="minibox" src="../assets/img/icon.png" alt="Logo de la aplicación">Admin Dashboard</h1>
            <nav class="nav">
                <ul>
                    <li><a href="loginAdmin.php">Cerrar Sesión</a></li>
                    <li><a href="logout.php">Salir</a></li>
                </ul>
            </nav>
        </header>

        <!-- Pestañas para cambiar entre la tabla y las gráficas -->
        <div class="tabs">
            <button id="tab-table" class="active">Tabla de Resultados</button>
            <button id="tab-charts">Gráficas</button>
        </div>

        <!-- Contenido para la tabla -->
        <section id="table-section" class="content-section active">
            <!-- Filtros para la tabla -->
            <section class="filters">
                <form method="GET" action="adminDashboard.php">
                    <input type="hidden" name="tab" value="table"> <!-- Campo oculto para mantener la pestaña activa -->
                    <div class="filters-row">
                        <div class="filter-group">
                            <label for="instrumento">Instrumento:</label>
                            <select name="instrumento" id="instrumento">
                                <option value="todos" <?= $instrumentoTabla === 'todos' ? 'selected' : '' ?>>Todos</option>
                                <option value="sisco" <?= $instrumentoTabla === 'sisco' ? 'selected' : '' ?>>SISCO</option>
                                <option value="dass" <?= $instrumentoTabla === 'dass' ? 'selected' : '' ?>>DASS</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="fecha_inicio">Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= $fechaInicioTabla ?>">
                        </div>

                        <div class="filter-group">
                            <label for="fecha_fin">Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="<?= $fechaFinTabla ?>">
                        </div>

                        <div class="filter-group">
                            <label for="hora_inicio">Hora Inicio:</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" value="<?= $horaInicioTabla ?>">
                        </div>

                        <div class="filter-group">
                            <label for="hora_fin">Hora Fin:</label>
                            <input type="time" name="hora_fin" id="hora_fin" value="<?= $horaFinTabla ?>">
                        </div>

                        <div class="filter-group">
                            <label for="limite">Cantidad:</label>
                            <select name="limite" id="limite">
                                <option value="5" <?= $limiteTabla == 5 ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= $limiteTabla == 10 ? 'selected' : '' ?>>10</option>
                                <option value="50" <?= $limiteTabla == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $limiteTabla == 100 ? 'selected' : '' ?>>100</option>
                                <option value="250" <?= $limiteTabla == 250 ? 'selected' : '' ?>>250</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones de aplicar y restablecer filtros -->
                    <div class="filter-buttons">
                        <button type="submit" class="apply-filters-button">Aplicar Filtros</button>
                        <a href="adminDashboard.php" class="reset-button">Restablecer Filtros</a>
                    </div>
                </form>

            </section>

            <!-- Tabla de Resultados -->
            <section class="results">
                <h2>Resultados Generales</h2>
                <?php if (empty($resultadosTabla)) : ?>
                    <p>No se encontraron resultados. Por favor, ajuste los filtros para encontrar resultados.</p>
                <?php else : ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Instrumento</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Nivel de Estrés</th>
                                <th>Nivel de Ansiedad</th>
                                <th>Nivel de Depresión</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultadosTabla as $index => $resultado) : ?>
                                <tr>
                                    <td><?= $resultado->id ?></td>
                                    <td><?= $resultado->nivel_de_ansiedad === null ? 'SISCO' : 'DASS' ?></td>
                                    <td><?= $resultado->fecha ?></td>
                                    <td><?= $resultado->hora ?></td>
                                    <td><?= $resultado->nivel_de_estres ?></td>
                                    <td><?= $resultado->nivel_de_ansiedad ?? '-' ?></td>
                                    <td><?= $resultado->nivel_de_depresion ?? '-' ?></td>
                                    <td><a href="verResultado.php?id=<?= $resultado->id ?>">Ver detalles</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Botón para descargar la tabla en CSV o Excel -->
                     <!-- Dale una pequeña separacion con la tabla -->
                    <br>
                   
                    <form method="POST" action="descargarTabla.php">
                        <!-- Enviar los filtros actuales como campos ocultos -->
                        <input type="hidden" name="instrumento" value="<?= $instrumentoTabla ?>">
                        <input type="hidden" name="fecha_inicio" value="<?= $fechaInicioTabla ?>">
                        <input type="hidden" name="fecha_fin" value="<?= $fechaFinTabla ?>">
                        <input type="hidden" name="hora_inicio" value="<?= $horaInicioTabla ?>">
                        <input type="hidden" name="hora_fin" value="<?= $horaFinTabla ?>">
                        <input type="hidden" name="limite" value="<?= $limiteTabla ?>">

                        
                        <button type="submit" name="formato" value="csv" class="apply-filters-button">Descargar CSV-Excel</button>

                    </form>

                <?php endif; ?>

            </section>
            <!-- Botón para descargar la tabla en CSV o Excel -->
            <form method="POST" action="descargarTabla.php">
                <!-- Enviar los filtros actuales como campos ocultos -->
                <input type="hidden" name="instrumento" value="<?= $instrumentoTabla ?>">
                <input type="hidden" name="fecha_inicio" value="<?= $fechaInicioTabla ?>">
                <input type="hidden" name="fecha_fin" value="<?= $fechaFinTabla ?>">
                <input type="hidden" name="hora_inicio" value="<?= $horaInicioTabla ?>">
                <input type="hidden" name="hora_fin" value="<?= $horaFinTabla ?>">
                <input type="hidden" name="limite" value="<?= $limiteTabla ?>">

            </form>

        </section>

        <!-- Contenido para las gráficas -->
        <section id="charts-section" class="content-section">
            <!-- Filtros específicos para las gráficas -->
            <section class="filters">
                <form method="GET" action="adminDashboard.php">
                    <input type="hidden" name="tab" value="charts"> <!-- Campo oculto para mantener la pestaña activa -->
                    <div class="filters-row">
                        <div class="filter-group">
                            <label for="chart-instrumento">Instrumento:</label>
                            <select name="chart-instrumento" id="chart-instrumento">
                                <option value="todos" <?= $instrumentoGrafica === 'todos' ? 'selected' : '' ?>>Todos</option>
                                <option value="sisco" <?= $instrumentoGrafica === 'sisco' ? 'selected' : '' ?>>SISCO</option>
                                <option value="dass" <?= $instrumentoGrafica === 'dass' ? 'selected' : '' ?>>DASS</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="chart-fecha_inicio">Fecha Inicio:</label>
                            <input type="date" name="chart-fecha_inicio" id="chart-fecha_inicio" value="<?= $fechaInicioGrafica ?>">
                        </div>

                        <div class="filter-group">
                            <label for="chart-fecha_fin">Fecha Fin:</label>
                            <input type="date" name="chart-fecha_fin" id="chart-fecha_fin" value="<?= $fechaFinGrafica ?>">
                        </div>

                        <div class="filter-group">
                            <label for="chart-hora_inicio">Hora Inicio:</label>
                            <input type="time" name="chart-hora_inicio" id="chart-hora_inicio" value="<?= $horaInicioGrafica ?>">
                        </div>

                        <div class="filter-group">
                            <label for="chart-hora_fin">Hora Fin:</label>
                            <input type="time" name="chart-hora_fin" id="chart-hora_fin" value="<?= $horaFinGrafica ?>">
                        </div>
                    </div>

                    <!-- Botones de aplicar y restablecer filtros -->
                    <div class="filter-buttons">
                        <button type="submit" class="apply-filters-button">Aplicar Filtros</button>
                        <a href="adminDashboard.php?tab=charts" class="reset-button">Restablecer Filtros</a>
                    </div>

                    <!-- Botones para seleccionar el tipo de gráfico (solo se muestran en la pestaña de gráficos) -->
                    <div class="chart-type-selector">
                        <button type="button" id="chart-bar">Gráfico de Barras</button>
                        <button type="button" id="chart-pie">Gráfico de Pastel</button>
                    </div>
                </form>
            </section>

            <!-- Gráficos -->
            <section class="charts">
                <?php if (empty($resultadosGrafica)) : ?>
                    <p>No se encontraron resultados. Por favor, ajuste los filtros para encontrar resultados.</p>
                <?php else : ?>
                    <!-- Gráfico de barras o pastel para SISCO -->
                    <div class="chart-container sisco-chart">
                        <h3>Niveles de Estrés (SISCO)</h3>
                        <canvas id="siscoChart"></canvas>
                        <div class="chart-info">Este gráfico muestra los niveles de estrés medidos según el test SISCO.</div>
                    </div>

                    <!-- Gráficos separados para DASS: Estrés, Ansiedad, Depresión -->
                    <div class="chart-container dass-chart">
                        <h3>Niveles de Estrés (DASS)</h3>
                        <canvas id="dassEstresChart"></canvas>
                        <div class="chart-info">Este gráfico muestra los niveles de estrés medidos según el test DASS.</div>
                    </div>

                    <div class="chart-container dass-chart">
                        <h3>Niveles de Ansiedad (DASS)</h3>
                        <canvas id="dassAnsiedadChart"></canvas>
                        <div class="chart-info">Este gráfico muestra los niveles de ansiedad medidos según el test DASS.</div>
                    </div>

                    <div class="chart-container dass-chart">
                        <h3>Niveles de Depresión (DASS)</h3>
                        <canvas id="dassDepresionChart"></canvas>
                        <div class="chart-info">Este gráfico muestra los niveles de depresión medidos según el test DASS.</div>
                    </div>
                <?php endif; ?>
            </section>
        </section>

    </div>

    <script>
        // JavaScript para cambiar entre las pestañas
        const tabTable = document.getElementById('tab-table');
        const tabCharts = document.getElementById('tab-charts');
        const tableSection = document.getElementById('table-section');
        const chartsSection = document.getElementById('charts-section');
        const chartInstrumentoSelect = document.getElementById('chart-instrumento');

        // Mantener la pestaña activa si se seleccionó alguna
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'table';
        if (activeTab === 'charts') {
            tabCharts.classList.add('active');
            tabTable.classList.remove('active');
            chartsSection.classList.add('active');
            tableSection.classList.remove('active');
        }

        tabTable.addEventListener('click', function() {
            tabTable.classList.add('active');
            tabCharts.classList.remove('active');
            tableSection.classList.add('active');
            chartsSection.classList.remove('active');
        });

        tabCharts.addEventListener('click', function() {
            tabCharts.classList.add('active');
            tabTable.classList.remove('active');
            chartsSection.classList.add('active');
            tableSection.classList.remove('active');
        });

        // Mostrar u ocultar las gráficas según el instrumento seleccionado
        chartInstrumentoSelect.addEventListener('change', function() {
            const selectedInstrumento = chartInstrumentoSelect.value;
            document.querySelectorAll('.chart-container').forEach(chart => {
                chart.style.display = 'none'; // Ocultar todas las gráficas
            });

            if (selectedInstrumento === 'sisco') {
                document.querySelectorAll('.sisco-chart').forEach(chart => {
                    chart.style.display = 'block'; // Mostrar solo las gráficas de SISCO
                });
            } else if (selectedInstrumento === 'dass') {
                document.querySelectorAll('.dass-chart').forEach(chart => {
                    chart.style.display = 'block'; // Mostrar solo las gráficas de DASS
                });
            } else {
                document.querySelectorAll('.chart-container').forEach(chart => {
                    chart.style.display = 'block'; // Mostrar todas las gráficas
                });
            }
        });

        // Lógica para cambiar entre gráficos de barras y de pastel
        let currentChartType = 'bar';
        document.getElementById('chart-bar').addEventListener('click', function() {
            currentChartType = 'bar';
            renderCharts(); // Re-renderizar los gráficos con el nuevo tipo
        });

        document.getElementById('chart-pie').addEventListener('click', function() {
            currentChartType = 'pie';
            renderCharts(); // Re-renderizar los gráficos con el nuevo tipo
        });

        // Función para renderizar los gráficos
        function renderCharts() {
            // Destruir gráficos anteriores si ya existen
            if (window.siscoChartInstance) window.siscoChartInstance.destroy();
            if (window.dassEstresChartInstance) window.dassEstresChartInstance.destroy();
            if (window.dassAnsiedadChartInstance) window.dassAnsiedadChartInstance.destroy();
            if (window.dassDepresionChartInstance) window.dassDepresionChartInstance.destroy();

            // Crear los gráficos nuevamente
            const ctxSisco = document.getElementById('siscoChart').getContext('2d');
            window.siscoChartInstance = new Chart(ctxSisco, {
                type: currentChartType,
                data: {
                    labels: ['Bajo', 'Medio', 'Alto'],
                    datasets: [{
                        label: 'Niveles de Estrés (SISCO)',
                        data: [<?= $siscoEstres['Bajo'] ?>, <?= $siscoEstres['Medio'] ?>, <?= $siscoEstres['Alto'] ?>],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(255, 99, 132, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: currentChartType === 'bar' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            });

            const ctxDassEstres = document.getElementById('dassEstresChart').getContext('2d');
            window.dassEstresChartInstance = new Chart(ctxDassEstres, {
                type: currentChartType,
                data: {
                    labels: ['Normal', 'Leve', 'Moderado', 'Severo', 'Extremadamente Severo'],
                    datasets: [{
                        label: 'Niveles de Estrés (DASS)',
                        data: [<?= $dassEstres['Normal'] ?>, <?= $dassEstres['Leve'] ?>, <?= $dassEstres['Moderado'] ?>, <?= $dassEstres['Severo'] ?>, <?= $dassEstres['Extremadamente Severo'] ?>],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)', // Normal
                            'rgba(153, 102, 255, 0.6)', // Leve
                            'rgba(255, 159, 64, 0.6)', // Moderado
                            'rgba(255, 205, 86, 0.6)', // Severo
                            'rgba(255, 99, 132, 0.6)' // Extremadamente Severo
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 205, 86, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: currentChartType === 'bar' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            });

            const ctxDassAnsiedad = document.getElementById('dassAnsiedadChart').getContext('2d');
            window.dassAnsiedadChartInstance = new Chart(ctxDassAnsiedad, {
                type: currentChartType,
                data: {
                    labels: ['Normal', 'Leve', 'Moderado', 'Severo', 'Extremadamente Severo'],
                    datasets: [{
                        label: 'Niveles de Ansiedad (DASS)',
                        data: [<?= $dassAnsiedad['Normal'] ?>, <?= $dassAnsiedad['Leve'] ?>, <?= $dassAnsiedad['Moderado'] ?>, <?= $dassAnsiedad['Severo'] ?>, <?= $dassAnsiedad['Extremadamente Severo'] ?>],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)', // Normal
                            'rgba(255, 206, 86, 0.6)', // Leve
                            'rgba(255, 159, 64, 0.6)', // Moderado
                            'rgba(153, 102, 255, 0.6)', // Severo
                            'rgba(255, 99, 132, 0.6)' // Extremadamente Severo
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: currentChartType === 'bar' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            });

            const ctxDassDepresion = document.getElementById('dassDepresionChart').getContext('2d');
            window.dassDepresionChartInstance = new Chart(ctxDassDepresion, {
                type: currentChartType,
                data: {
                    labels: ['Normal', 'Leve', 'Moderado', 'Severo', 'Extremadamente Severo'],
                    datasets: [{
                        label: 'Niveles de Depresión (DASS)',
                        data: [<?= $dassDepresion['Normal'] ?>, <?= $dassDepresion['Leve'] ?>, <?= $dassDepresion['Moderado'] ?>, <?= $dassDepresion['Severo'] ?>, <?= $dassDepresion['Extremadamente Severo'] ?>],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)', // Normal
                            'rgba(153, 102, 255, 0.6)', // Leve
                            'rgba(255, 159, 64, 0.6)', // Moderado
                            'rgba(54, 162, 235, 0.6)', // Severo
                            'rgba(255, 205, 86, 0.6)' // Extremadamente Severo
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 205, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: currentChartType === 'bar' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            });
        }

        // Renderizar las gráficas al cargar la página
        renderCharts();

        // Mostrar las gráficas correctas al cargar la página
        chartInstrumentoSelect.dispatchEvent(new Event('change'));
    </script>
    
</body>

</html>