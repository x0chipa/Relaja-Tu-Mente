<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Test</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        h2 {
            color: #444;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        .result-box {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            background-color: #e6f7ff;
        }
        .date-box {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
        }
        .date-box span {
            display: block;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #f0f8ff;
            color: #333;
        }
        td {
            background-color: #ffffff;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007b80;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #005f5f;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .download-button {
            background-color: #28a745;
        }
        .download-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles del Test <?= $instrumento_id == 2 ? 'SISCO' : 'DASS' ?></h1>
        <div class="date-box">
            <span><strong>Fecha:</strong> <?= htmlspecialchars($fechaFormateada) ?></span>
            <span><strong>Hora:</strong> <?= htmlspecialchars($horaFormateada) ?></span>
        </div>

        <h2>Resultados Calculados</h2>
        <?php if ($instrumento_id == 2): ?>
            <div class="result-box" style="background-color: <?= $resultado[0]->nivel_de_estres == 'Bajo' ? '#b3e5fc' : ($resultado[0]->nivel_de_estres == 'Medio' ? '#ffcc80' : '#ef9a9a') ?>;">
                <p><strong>Nivel de Estrés:</strong> <?= htmlspecialchars($resultado[0]->nivel_de_estres) ?> (Puntuación: <?= htmlspecialchars($resultado[0]->estres_puntuacion) ?>)</p>
            </div>
        <?php elseif ($instrumento_id == 1): ?>
            <div class="result-box" style="background-color: <?= $resultado[0]->nivel_de_estres == 'Normal' ? '#b3e5fc' : ($resultado[0]->nivel_de_estres == 'Leve' ? '#ffcc80' : ($resultado[0]->nivel_de_estres == 'Moderado' ? '#ffab91' : ($resultado[0]->nivel_de_estres == 'Severo' ? '#ef9a9a' : '#d32f2f'))) ?>;">
                <p><strong>Nivel de Estrés:</strong> <?= htmlspecialchars($resultado[0]->nivel_de_estres) ?> (Puntuación: <?= htmlspecialchars($resultado[0]->estres_puntuacion) ?>)</p>
            </div>
            <div class="result-box" style="background-color: <?= $resultado[0]->nivel_de_ansiedad == 'Normal' ? '#b3e5fc' : ($resultado[0]->nivel_de_ansiedad == 'Leve' ? '#ffcc80' : ($resultado[0]->nivel_de_ansiedad == 'Moderado' ? '#ffab91' : ($resultado[0]->nivel_de_ansiedad == 'Severo' ? '#ef9a9a' : '#d32f2f'))) ?>;">
                <p><strong>Nivel de Ansiedad:</strong> <?= htmlspecialchars($resultado[0]->nivel_de_ansiedad) ?> (Puntuación: <?= htmlspecialchars($resultado[0]->ansiedad_puntuacion) ?>)</p>
            </div>
            <div class="result-box" style="background-color: <?= $resultado[0]->nivel_de_depresion == 'Normal' ? '#b3e5fc' : ($resultado[0]->nivel_de_depresion == 'Leve' ? '#ffcc80' : ($resultado[0]->nivel_de_depresion == 'Moderado' ? '#ffab91' : ($resultado[0]->nivel_de_depresion == 'Severo' ? '#ef9a9a' : '#d32f2f'))) ?>;">
                <p><strong>Nivel de Depresión:</strong> <?= htmlspecialchars($resultado[0]->nivel_de_depresion) ?> (Puntuación: <?= htmlspecialchars($resultado[0]->depresion_puntuacion) ?>)</p>
            </div>
        <?php endif; ?>

        <h2>Preguntas y Respuestas</h2>
        <table>
            <thead>
                <tr>
                    <th>Pregunta</th>
                    <th>Respuesta Seleccionada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($preguntas as $index => $pregunta): ?>
                    <tr>
                        <td><?= htmlspecialchars($pregunta->pregunta) ?></td>
                        <?php
                            $respuestaSeleccionada = $respuestas[$index]->respuesta ?? null;
                            $opcionSeleccionada = "respuesta_" . $respuestaSeleccionada;
                        ?>
                        <td><?= htmlspecialchars($pregunta->$opcionSeleccionada) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="button-container">
            <a href="adminDashboard.php">Volver al Dashboard</a>
            <a href="?id=<?= $resultadoId ?>&descargar=1" class="download-button">Descargar PDF</a>
        </div>
    </div>
</body>
</html>
