<?php
// resultadosLogic.php

session_start();

require_once __DIR__ . '/../models/Pregunta.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

use Dompdf\Dompdf;

// Verificación de sesión
if (!isset($_SESSION['sesion'])) {
    echo "No se ha iniciado ninguna sesión de prueba.";
    exit();
}

// Instancia del modelo de Pregunta
$preguntaModel = new Pregunta();

$sesion = $_SESSION['sesion'];
$instrumento_id = $_SESSION['instrumento_id'];

$nivelDeEstres = '';
$nivelDeAnsiedad = '';
$nivelDeDepresion = '';
$estresPuntuacion = 0;
$ansiedadPuntuacion = 0;
$depresionPuntuacion = 0;

// Formatear fecha y hora en un formato amigable en español
setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain.1252');
$fechaSesion = DateTime::createFromFormat('Y-m-d H:i:s', $sesion['fecha'] . ' ' . $sesion['hora']);
$fechaFormateada = strftime('%e de %B de %Y, %I:%M %p', $fechaSesion->getTimestamp());

// Calcular resultados según el test
if ($instrumento_id == 2) { // SISCO
    $puntuacionTotal = array_sum(array_column($_SESSION['respuestas'] ?? [], 'respuesta'));

    if ($puntuacionTotal <= 70) {
        $nivelDeEstres = "Bajo";
        $colorEstres = "#A8D5BA"; // Verde pastel
    } elseif ($puntuacionTotal <= 110) {
        $nivelDeEstres = "Medio";
        $colorEstres = "#FFE5B4"; // Amarillo pastel
    } else {
        $nivelDeEstres = "Alto";
        $colorEstres = "#F8B9B2"; // Rojo pastel
    }

    $estresPuntuacion = $puntuacionTotal;

} elseif ($instrumento_id == 1) { // DASS
    for ($i = 0; $i < 7; $i++) {
        if (isset($_SESSION['respuestas'][$i])) {
            $estresPuntuacion += max(0, $_SESSION['respuestas'][$i]['respuesta'] - 1);
        }
    }
    for ($i = 7; $i < 14; $i++) {
        if (isset($_SESSION['respuestas'][$i])) {
            $ansiedadPuntuacion += max(0, $_SESSION['respuestas'][$i]['respuesta'] - 1);
        }
    }
    for ($i = 14; $i < 21; $i++) {
        if (isset($_SESSION['respuestas'][$i])) {
            $depresionPuntuacion += max(0, $_SESSION['respuestas'][$i]['respuesta'] - 1);
        }
    }

    $nivelDeEstres = calcularNivelDeEstres($estresPuntuacion);
    $nivelDeAnsiedad = calcularNivelDeAnsiedad($ansiedadPuntuacion);
    $nivelDeDepresion = calcularNivelDeDepresion($depresionPuntuacion);

    // Definir colores según niveles
    $colorEstres = getColorByLevel($nivelDeEstres);
    $colorAnsiedad = getColorByLevel($nivelDeAnsiedad);
    $colorDepresion = getColorByLevel($nivelDeDepresion);
}

function calcularNivelDeEstres($puntuacion) {
    if ($puntuacion <= 7) {
        return "Normal";
    } elseif ($puntuacion <= 9) {
        return "Leve";
    } elseif ($puntuacion <= 12) {
        return "Moderado";
    } elseif ($puntuacion <= 16) {
        return "Severo";
    } else {
        return "Extremadamente Severo";
    }
}

function calcularNivelDeAnsiedad($puntuacion) {
    if ($puntuacion <= 3) {
        return "Normal";
    } elseif ($puntuacion <= 5) {
        return "Leve";
    } elseif ($puntuacion <= 7) {
        return "Moderado";
    } elseif ($puntuacion <= 9) {
        return "Severo";
    } else {
        return "Extremadamente Severo";
    }
}

function calcularNivelDeDepresion($puntuacion) {
    if ($puntuacion <= 4) {
        return "Normal";
    } elseif ($puntuacion <= 6) {
        return "Leve";
    } elseif ($puntuacion <= 10) {
        return "Moderado";
    } elseif ($puntuacion <= 13) {
        return "Severo";
    } else {
        return "Extremadamente Severo";
    }
}

// Función para obtener color según el nivel
function getColorByLevel($nivel) {
    switch ($nivel) {
        case "Normal":
            return "#A8D5BA"; // Verde pastel
        case "Leve":
            return "#FFE5B4"; // Amarillo pastel
        case "Moderado":
            return "#F8D9B2"; // Naranja pastel
        case "Severo":
        case "Alto": // Aplica también para SISCO
            return "#F8B9B2"; // Rojo pastel
        case "Extremadamente Severo":
            return "#F5A8A8"; // Rojo más intenso pastel
        default:
            return "#D3D3D3"; // Gris claro como fallback
    }
}

// Obtener las preguntas desde la base de datos según el instrumento ID
$preguntas = $preguntaModel->filtrarPorInstrumentoId($instrumento_id);

// Función para obtener el texto del inciso correspondiente
function obtenerTextoRespuesta($pregunta, $respuestaSeleccionada) {
    switch ($respuestaSeleccionada) {
        case 1:
            return $pregunta->respuesta_1;
        case 2:
            return $pregunta->respuesta_2;
        case 3:
            return $pregunta->respuesta_3;
        case 4:
            return $pregunta->respuesta_4;
        case 5:
            return $pregunta->respuesta_5;
        default:
            return "Sin respuesta";
    }
}
