<?php
require_once __DIR__ . '/../models/Resultado.php';

// Obtener los filtros de la solicitud POST
$instrumentoTabla = $_POST['instrumento'] ?? 'todos';
$fechaInicioTabla = $_POST['fecha_inicio'] ?? date('Y-m-d');
$fechaFinTabla = $_POST['fecha_fin'] ?? date('Y-m-d');
$horaInicioTabla = $_POST['hora_inicio'] ?? '00:00';
$horaFinTabla = $_POST['hora_fin'] ?? '23:59';
$limiteTabla = $_POST['limite'] ?? 5;
$formato = $_POST['formato'] ?? 'csv';

// Crear instancia del modelo Resultado
$resultadoModel = new Resultado();

// Filtrar los resultados según los filtros seleccionados
$resultadosTabla = $resultadoModel->filtrarResultadosPorRangoFechaHora(
    $instrumentoTabla,
    $fechaInicioTabla,
    $fechaFinTabla,
    $horaInicioTabla,
    $horaFinTabla,
    $limiteTabla
);

if ($formato === 'csv') {
    // Generar CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="resultados.csv"');

    $output = fopen('php://output', 'w');
    // Cabeceras del archivo
    fputcsv($output, ['ID', 'Instrumento', 'Fecha', 'Hora', 'Nivel de Estrés', 'Nivel de Ansiedad', 'Nivel de Depresión']);

    // Rellenar datos
    foreach ($resultadosTabla as $resultado) {
        fputcsv($output, [
            $resultado->id,
            $resultado->nivel_de_ansiedad === null ? 'SISCO' : 'DASS',
            $resultado->fecha,
            $resultado->hora,
            $resultado->nivel_de_estres,
            $resultado->nivel_de_ansiedad ?? '-',
            $resultado->nivel_de_depresion ?? '-'
        ]);
    }

    fclose($output);
    exit();
}
?>
