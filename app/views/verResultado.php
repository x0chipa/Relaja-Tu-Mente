<?php
require_once __DIR__ . '/../models/Resultado.php';
require_once __DIR__ . '/../models/Sesion.php';
require_once __DIR__ . '/../models/Pregunta.php';
require_once __DIR__ . '/../models/Respuesta.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Importar Dompdf

use Dompdf\Dompdf;

// Verificar si se ha pasado el ID del resultado
if (!isset($_GET['id'])) {
    echo "No se ha proporcionado ningún ID de resultado.";
    exit();
}

// Obtener el ID del resultado
$resultadoId = $_GET['id'];

// Crear instancias de los modelos necesarios
$resultadoModel = new Resultado();
$sesionModel = new Sesion();
$preguntaModel = new Pregunta();
$respuestaModel = new Respuesta();

// Buscar el resultado por sesión ID
$resultado = $resultadoModel->filtrarPorSesionId($resultadoId);

// Verificar si se encontró el resultado
if (!$resultado) {
    echo "No se encontró ningún resultado con el ID proporcionado.";
    exit();
}

// Obtener la sesión correspondiente al resultado
$sesion = $sesionModel->encontrarPorId($resultado[0]->sesion_id);

// Obtener las preguntas y respuestas correspondientes al instrumento
$instrumento_id = $resultado[0]->nivel_de_ansiedad === null ? 2 : 1; // 2: SISCO, 1: DASS
$preguntas = $preguntaModel->filtrarPorInstrumentoId($instrumento_id);
$respuestas = $respuestaModel->filtrarPorSesionId($resultado[0]->sesion_id);

// Formatear la fecha y hora
$fechaFormateada = date('d/m/Y', strtotime($sesion->fecha));
$horaFormateada = date('H:i:s', strtotime($sesion->hora));

// Manejar la descarga del PDF si se solicita
if (isset($_GET['descargar'])) {
    ob_start(); // Iniciar el buffer de salida
    include __DIR__ . '/verResultadoBody.php'; // Incluir el archivo HTML
    $html = ob_get_clean(); // Obtener el contenido del buffer y limpiarlo

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); // Configurar tamaño de página y orientación
    $dompdf->render();

    $dompdf->stream("detalles_test.pdf", ["Attachment" => true]);
    exit();
} else {
    include __DIR__ . '/verResultadoBody.php'; // Incluir la vista HTML en la pantalla
}
