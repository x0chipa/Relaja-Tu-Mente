<?php
session_start();

// Verificar si hay preguntas almacenadas en la sesión
if (!isset($_SESSION['preguntas']) || $_SESSION['indicePregunta'] >= count($_SESSION['preguntas'])) {
    echo "No hay preguntas disponibles o el test ha finalizado.";
    exit();
}

// Obtener la pregunta actual
$preguntaActual = $_SESSION['preguntas'][$_SESSION['indicePregunta']];

// Procesar la respuesta si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuestaSeleccionada = intval($_POST['respuesta']);
    
    // Guardar la respuesta en la sesión
    $_SESSION['respuestas'][] = [
        'pregunta_id' => $preguntaActual->id_pregunta,
        'respuesta' => $respuestaSeleccionada
    ];

    // Avanzar a la siguiente pregunta
    $_SESSION['indicePregunta']++;

    // Si se han contestado todas las preguntas, redirigir a la página de finalización
    if ($_SESSION['indicePregunta'] >= count($_SESSION['preguntas'])) {
        header("Location: finalizarTest.php");
        exit();
    }

    // Recargar la página para mostrar la siguiente pregunta
    header("Location: responderTest.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <style>
        body {
            background-color: #e6f7ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0 15px;
        }
        .container {
            background-color: #ccf2f4;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            opacity: 1;
            transition: opacity 0.5s ease;
        }
        h1 {
            font-size: 2rem;
            color: #004d4d;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 2.8rem;
            color: #004d4d;
            margin-bottom: 30px;
        }
        p {
            font-size: 2rem;
            color: #007b80;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .option-button {
            display: block;
            width: 100%;
            padding: 15px;
            margin-bottom: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
            background-color: #007b80;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .option-button:hover {
            background-color: #009688;
            transform: scale(1.03);
        }
        .option-button.selected {
            background-color: #006060;
        }
        .progress {
            height: 30px;
            margin-top: 30px;
            position: relative;
        }
        .progress-bar {
            background-color: #007b80;
            text-align: center;
            font-weight: bold;
            color: #fff;
        }
        .progress-bar span {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <?php if ($_SESSION['instrumento_id'] == 1) { ?>
                Instrumento de medición de Estrés, Ansiedad y Depresión DASS-21
            <?php } else { ?>
                Instrumento de medición de Estrés SISCO
            <?php } ?>
        </h1>
        <p><?= $_SESSION['indicePregunta'] + 1 ?>. <?=$preguntaActual->pregunta?></p>
        <form id="test-form" method="POST">
            <button type="button" class="option-button" data-value="1"><?= $preguntaActual->respuesta_1 ?></button>
            <button type="button" class="option-button" data-value="2"><?= $preguntaActual->respuesta_2 ?></button>
            <button type="button" class="option-button" data-value="3"><?= $preguntaActual->respuesta_3 ?></button>
            <button type="button" class="option-button" data-value="4"><?= $preguntaActual->respuesta_4 ?></button>
            <?php if (!empty($preguntaActual->respuesta_5)) { ?>
            <button type="button" class="option-button" data-value="5"><?= $preguntaActual->respuesta_5 ?></button>
            <?php } ?>
            <input type="hidden" name="respuesta" id="respuesta">
        </form>

        <!-- Barra de progreso -->
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: <?= (($_SESSION['indicePregunta'] + 1) / count($_SESSION['preguntas'])) * 100 ?>%;" aria-valuenow="<?= $_SESSION['indicePregunta'] + 1 ?>" aria-valuemin="0" aria-valuemax="<?= count($_SESSION['preguntas']) ?>">
                <span><?= round((($_SESSION['indicePregunta'] + 1) / count($_SESSION['preguntas'])) * 100) ?>%</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.option-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.option-button').forEach(btn => btn.disabled = true);
                button.classList.add('selected');
                document.getElementById('respuesta').value = button.getAttribute('data-value');
                document.querySelector('.container').style.opacity = 0.5;
                setTimeout(() => {
                    document.getElementById('test-form').submit();
                }, 1000);
            });
        });
    </script>
</body>
</html>
