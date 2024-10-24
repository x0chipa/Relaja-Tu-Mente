<?php
require_once '../models/Pregunta.php';
require_once '../models/Sesion.php';

// Configurar la zona horaria
date_default_timezone_set('America/Mexico_City');

session_start();

// Verificar si se ha pasado el ID del instrumento como parámetro
if (isset($_GET['id'])) {
    $instrumento_id = intval($_GET['id']); // Convertir el ID a entero

    // Crear un objeto de Sesion con fecha y hora actuales
    $sesionModel = new Sesion();
    $sesion = $sesionModel->crear();

    // Guardar solo los datos relevantes en $_SESSION
    $_SESSION['sesion'] = [
        'fecha' => $sesion->fecha,
        'hora' => $sesion->hora
    ];

    // Limpiar las respuestas previas (importante para evitar duplicados)
    $_SESSION['respuestas'] = []; // Reinicia la lista de respuestas

    $preguntaModel = new Pregunta();
    $preguntas = $preguntaModel->filtrarPorInstrumentoId($instrumento_id);

    // Guardar las preguntas en la sesión
    $_SESSION['preguntas'] = $preguntas;
    $_SESSION['indicePregunta'] = 0;
    $_SESSION['instrumento_id'] = $instrumento_id;

    // Redirigir al archivo donde se contestan las preguntas
    header("Location: ../views/responderTest.php");
    exit();
} else {
    echo "ID de instrumento no proporcionado.";
}
