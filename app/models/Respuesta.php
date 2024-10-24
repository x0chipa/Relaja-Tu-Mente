<?php

require_once __DIR__ . '/../../config/database.php';

class Respuesta
{
    private $conn;
    private $table_name = "respuestas";

    public $id_respuesta;
    public $sesion_id;
    public $pregunta_id;
    public $respuesta;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Insertar una respuesta
    public function insertar($sesion_id, $pregunta_id, $respuesta)
    {
        $query = "INSERT INTO " . $this->table_name . " (sesion_id, pregunta_id, respuesta) VALUES (:sesion_id, :pregunta_id, :respuesta)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sesion_id', $sesion_id);
        $stmt->bindParam(':pregunta_id', $pregunta_id);
        $stmt->bindParam(':respuesta', $respuesta);

        return $stmt->execute();
    }

    // Filtrar respuestas por sesión ID
    public function filtrarPorSesionId($sesion_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE sesion_id = :sesion_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sesion_id', $sesion_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}
