<?php

require_once __DIR__ . '/../../config/database.php';

class Pregunta {
    private $conn;
    private $table_name = "preguntas";

    public $id_pregunta;
    public $Instrumento_id;
    public $pregunta;
    public $respuesta_1;
    public $respuesta_2;
    public $respuesta_3;
    public $respuesta_4;
    public $respuesta_5;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Listar todas las preguntas
    public function listar() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Filtrar preguntas por Instrumento ID
    public function filtrarPorInstrumentoId($Instrumento_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Instrumento_id = :Instrumento_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Instrumento_id', $Instrumento_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
