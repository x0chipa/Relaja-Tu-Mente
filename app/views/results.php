<?php
require_once __DIR__ . '/../controllers/resultadosLogic.php'; // Incluir la lógica de resultados
use Dompdf\Dompdf; // Import the Dompdf class

// Generar PDF si se envía la solicitud de descarga
if (isset($_GET['descargar'])) {
    ob_start(); // Inicia el buffer de salida
    include __DIR__ . '/resultsBody.php'; // Incluye el archivo HTML
    $html = ob_get_clean(); // Obtiene el contenido del buffer y limpia el buffer

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); // Configurar tamaño de página y orientación
    $dompdf->render();

    $dompdf->stream("resultados_encuesta.pdf", ["Attachment" => true]);
    exit();
} else {
    include __DIR__ . '/resultsBody.php'; // Incluye la vista HTML en la pantalla
}
