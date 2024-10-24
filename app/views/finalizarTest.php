<?php
session_start();
require_once '../models/Sesion.php';
require_once '../models/Respuesta.php';
require_once '../models/Resultado.php';

// Verificar si ya se completaron todas las preguntas
if ($_SESSION['indicePregunta'] < count($_SESSION['preguntas'])) {
    header("Location: responderTest.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear la sesión y guardarla en la base de datos
    $sesionModel = new Sesion();
    $sesion = $_SESSION['sesion']; // Sesión temporal que habíamos creado antes

    // Asegurarse de que la sesión sea un objeto y no un array
    if (is_array($sesion)) {
        $sesionObj = new Sesion();
        $sesionObj->fecha = $sesion['fecha'];
        $sesionObj->hora = $sesion['hora'];
        $sesion = $sesionObj;
    }

    // Insertar la sesión en la base de datos
    $sesion_id = $sesionModel->insertarConObjeto($sesion);

    if ($sesion_id) {
        // Guardar las respuestas asociadas a esta sesión
        $respuestaModel = new Respuesta();
        foreach ($_SESSION['respuestas'] as $respuesta) {
            $respuestaModel->insertar($sesion_id, $respuesta['pregunta_id'], $respuesta['respuesta']);
        }

        // Calcular y guardar los resultados basados en el instrumento
        $resultadoModel = new Resultado();
        $instrumento_id = $_SESSION['instrumento_id']; // 1 para DASS, 2 para SISCO

        if ($instrumento_id == 2) { // SISCO
            $puntuacionTotal = 0;
            foreach ($_SESSION['respuestas'] as $respuesta) {
                $puntuacionTotal += $respuesta['respuesta'];
            }
            $nivelDeEstres = ($puntuacionTotal <= 70) ? "Bajo" : (($puntuacionTotal <= 110) ? "Medio" : "Alto");

            // Guardar el resultado en la base de datos
            $resultadoModel->insertar($sesion_id, $nivelDeEstres, $puntuacionTotal, null, null, null, null);

        } elseif ($instrumento_id == 1) { // DASS
            $estresPuntuacion = 0;
            $ansiedadPuntuacion = 0;
            $depresionPuntuacion = 0;

            for ($i = 0; $i < 7; $i++) {
                $estresPuntuacion += ($_SESSION['respuestas'][$i]['respuesta'] - 1);
            }

            for ($i = 7; $i < 14; $i++) {
                $ansiedadPuntuacion += ($_SESSION['respuestas'][$i]['respuesta'] - 1);
            }

            for ($i = 14; $i < 21; $i++) {
                $depresionPuntuacion += ($_SESSION['respuestas'][$i]['respuesta'] - 1);
            }

            // Calcular niveles
            $nivelDeEstres = calcularNivelDeEstres($estresPuntuacion);
            $nivelDeAnsiedad = calcularNivelDeAnsiedad($ansiedadPuntuacion);
            $nivelDeDepresion = calcularNivelDeDepresion($depresionPuntuacion);

            // Guardar el resultado en la base de datos
            $resultadoModel->insertar($sesion_id, $nivelDeEstres, $estresPuntuacion, $nivelDeAnsiedad, $ansiedadPuntuacion, $nivelDeDepresion, $depresionPuntuacion);
        }

        // Redirigir a la página de resultados
        header("Location: results.php");
        exit();
    } else {
        echo "Error al guardar la sesión en la base de datos.";
    }
}

// Funciones para calcular los niveles en DASS
function calcularNivelDeEstres($puntuacion) {
    if ($puntuacion <= 7) return "Normal";
    if ($puntuacion <= 9) return "Leve";
    if ($puntuacion <= 12) return "Moderado";
    if ($puntuacion <= 16) return "Severo";
    return "Extremadamente Severo";
}

function calcularNivelDeAnsiedad($puntuacion) {
    if ($puntuacion <= 3) return "Normal";
    if ($puntuacion <= 5) return "Leve";
    if ($puntuacion <= 7) return "Moderado";
    if ($puntuacion <= 9) return "Severo";
    return "Extremadamente Severo";
}

function calcularNivelDeDepresion($puntuacion) {
    if ($puntuacion <= 4) return "Normal";
    if ($puntuacion <= 6) return "Leve";
    if ($puntuacion <= 10) return "Moderado";
    if ($puntuacion <= 13) return "Severo";
    return "Extremadamente Severo";
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Test</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
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
            max-width: 500px;
            width: 100%;
        }
        h1 {
            font-size: 2rem;
            color: #004d4d;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2rem;
            color: #007b80;
            margin-bottom: 30px;
        }
        button {
            background-color: #00bfa5;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        button:hover {
            background-color: #009688;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Has completado todas las preguntas del test.</h1>
        <p>¿Estás listo para ver tus resultados?</p>
        <form method="POST">
            <button type="submit">Finalizar Test y Ver Resultados</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
