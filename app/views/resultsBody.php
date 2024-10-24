<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <title>Resultados del Test</title>
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
        <h1>Resultados de la encuesta <?= $instrumento_id == 2 ? 'SISCO' : 'DASS' ?></h1>
        <div class="date-box">
            <span><strong>Fecha y hora:</strong> <?= htmlspecialchars($fechaFormateada) ?></span>
        </div>

        <h2>Resultados Calculados</h2>
        <?php if ($instrumento_id == 2): ?>
            <div class="result-box" style="background-color: <?= $colorEstres ?>;">
                <p><strong>Nivel de Estrés:</strong> <?= htmlspecialchars($nivelDeEstres) ?> (Puntuación: <?= htmlspecialchars($estresPuntuacion) ?>)</p>
            </div>
        <?php elseif ($instrumento_id == 1): ?>
            <div class="result-box" style="background-color: <?= $colorEstres ?>;">
                <p><strong>Nivel de Estrés:</strong> <?= htmlspecialchars($nivelDeEstres) ?> (Puntuación: <?= htmlspecialchars($estresPuntuacion) ?>)</p>
            </div>
            <div class="result-box" style="background-color: <?= $colorAnsiedad ?>;">
                <p><strong>Nivel de Ansiedad:</strong> <?= htmlspecialchars($nivelDeAnsiedad) ?> (Puntuación: <?= htmlspecialchars($ansiedadPuntuacion) ?>)</p>
            </div>
            <div class="result-box" style="background-color: <?= $colorDepresion ?>;">
                <p><strong>Nivel de Depresión:</strong> <?= htmlspecialchars($nivelDeDepresion) ?> (Puntuación: <?= htmlspecialchars($depresionPuntuacion) ?>)</p>
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
                        <td><?= htmlspecialchars(obtenerTextoRespuesta($pregunta, $_SESSION['respuestas'][$index]['respuesta'] ?? null)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="button-container">
            <a href="homePage.php">Volver al inicio</a>
            <a href="?descargar=1" class="download-button">Descargar PDF</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
