<?php

require_once __DIR__ . '/../../config/database.php';

class Instrumento {
    private $conn;
    private $table_name = "instrumento";

    public $id_instrumento;
    public $nombre;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Listar todos los instrumentos
    public function listar() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Encontrar instrumento por ID
    public function encontrarPorId($id_instrumento) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_instrumento = :id_instrumento";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_instrumento', $id_instrumento, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Insertar un nuevo instrumento
    public function insertar($nombre) {
        $query = "INSERT INTO " . $this->table_name . " (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);

        return $stmt->execute();
    }

    // Actualizar un instrumento
    public function actualizar($id_instrumento, $nombre) {
        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre WHERE id_instrumento = :id_instrumento";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id_instrumento', $id_instrumento, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Eliminar un instrumento
    public function eliminar($id_instrumento) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_instrumento = :id_instrumento";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_instrumento', $id_instrumento, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Filtrar instrumentos por nombre
    public function filtrarPorNombre($nombre) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nombre LIKE :nombre";
        $stmt = $this->conn->prepare($query);
        $nombre = "%{$nombre}%";
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
