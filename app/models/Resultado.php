<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/Sesion.php'; // Incluir el modelo Sesion para obtener los datos de la sesión

class Resultado
{
    private $conn;
    private $table_name = "resultados";

    public $id;
    public $sesion_id;
    public $nivel_de_estres;
    public $estres_puntuacion;
    public $nivel_de_ansiedad;
    public $ansiedad_puntuacion;
    public $nivel_de_depresion;
    public $depresion_puntuacion;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Insertar un nuevo resultado
    public function insertar($sesion_id, $nivel_de_estres, $estres_puntuacion, $nivel_de_ansiedad = null, $ansiedad_puntuacion = null, $nivel_de_depresion = null, $depresion_puntuacion = null)
    {
        $query = "INSERT INTO " . $this->table_name . " (sesion_id, nivel_de_estres, estres_puntuacion, nivel_de_ansiedad, ansiedad_puntuacion, nivel_de_depresion, depresion_puntuacion) VALUES (:sesion_id, :nivel_de_estres, :estres_puntuacion, :nivel_de_ansiedad, :ansiedad_puntuacion, :nivel_de_depresion, :depresion_puntuacion)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sesion_id', $sesion_id);
        $stmt->bindParam(':nivel_de_estres', $nivel_de_estres);
        $stmt->bindParam(':estres_puntuacion', $estres_puntuacion);
        $stmt->bindParam(':nivel_de_ansiedad', $nivel_de_ansiedad);
        $stmt->bindParam(':ansiedad_puntuacion', $ansiedad_puntuacion);
        $stmt->bindParam(':nivel_de_depresion', $nivel_de_depresion);
        $stmt->bindParam(':depresion_puntuacion', $depresion_puntuacion);

        return $stmt->execute();
    }

    // Filtrar resultados por sesión ID
    public function filtrarPorSesionId($sesion_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE sesion_id = :sesion_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sesion_id', $sesion_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Obtener los detalles de una sesión incluyendo fecha y hora
    public function obtenerDetallesConFecha($resultado)
    {
        $sesionModel = new Sesion();
        $sesion = $sesionModel->encontrarPorId($resultado->sesion_id);

        if ($sesion) {
            $resultado->fecha = $sesion->fecha;
            $resultado->hora = $sesion->hora;
        }

        return $resultado;
    }

    public function filtrarResultadosPorRangoFechaHora($instrumento, $fechaInicio = null, $fechaFin = null, $horaInicio = null, $horaFin = null, $limite = null)
    {
        $query = "
        SELECT resultados.*, sesion.fecha, sesion.hora 
        FROM resultados 
        JOIN sesion ON resultados.sesion_id = sesion.id 
        WHERE 1=1";

        // Aplicar filtros de fecha
        if ($fechaInicio && $fechaFin) {
            $query .= " AND sesion.fecha BETWEEN :fechaInicio AND :fechaFin";
        } elseif ($fechaInicio) { // Si solo se proporciona una fecha de inicio
            $query .= " AND sesion.fecha >= :fechaInicio";
        } elseif ($fechaFin) { // Si solo se proporciona una fecha de fin
            $query .= " AND sesion.fecha <= :fechaFin";
        }

        // Aplicar filtros de hora
        if ($horaInicio && $horaFin) {
            $query .= " AND sesion.hora BETWEEN :horaInicio AND :horaFin";
        } elseif ($horaInicio) { // Si solo se proporciona una hora de inicio
            $query .= " AND sesion.hora >= :horaInicio";
        } elseif ($horaFin) { // Si solo se proporciona una hora de fin
            $query .= " AND sesion.hora <= :horaFin";
        }

        // Aplicar límite si se proporciona
        if ($limite) {
            $query .= " LIMIT " . intval($limite); // Aquí concatenamos el valor de límite directamente
        }

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros de fecha
        if ($fechaInicio) {
            $stmt->bindParam(':fechaInicio', $fechaInicio);
        }
        if ($fechaFin) {
            $stmt->bindParam(':fechaFin', $fechaFin);
        }

        // Vincular parámetros de hora
        if ($horaInicio) {
            $stmt->bindParam(':horaInicio', $horaInicio);
        }
        if ($horaFin) {
            $stmt->bindParam(':horaFin', $horaFin);
        }

        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Aplicar filtro de instrumento manualmente
        if ($instrumento !== 'todos') {
            $resultados = array_filter($resultados, function ($resultado) use ($instrumento) {
                if ($instrumento === 'sisco' && $resultado->nivel_de_ansiedad === null) {
                    return true;
                }
                if ($instrumento === 'dass' && $resultado->nivel_de_ansiedad !== null) {
                    return true;
                }
                return false;
            });
        }

        return $resultados;
    }
}
