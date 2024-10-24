<?php

require_once __DIR__ . '/../../config/database.php';

class Sesion
{
    private $conn;
    private $table_name = "sesion";

    public $id;
    public $fecha;
    public $hora;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function obtenerUltimoId() {
        $query = "SELECT MAX(id) AS max_id FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_OBJ);
        return $resultado ? $resultado->max_id : 0;
    }

    // Crear una nueva sesión pero no insertandola en la base de datos
    public function crear()
    {
        $sesion = new Sesion();
        $sesion->fecha = date('Y-m-d'); // Capturar la fecha actual
        $sesion->hora = date('H:i:s'); // Capturar la hora actual

        return $sesion;
    }

    // Insertar una nueva sesión con un objeto sesión en la base de datos
    public function insertarConObjeto($sesion) {
    if ($this->insertar($sesion->fecha, $sesion->hora)) {
        // Retornar el ID generado
        return $this->conn->lastInsertId();
    }
    return false;
}


    // Insertar una nueva sesión
    public function insertar($fecha, $hora) {
        $query = "INSERT INTO " . $this->table_name . " (fecha, hora) VALUES (:fecha, :hora)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);

        if ($stmt->execute()) {
            // Obtener el ID generado automáticamente por AUTO_INCREMENT
            return $this->conn->lastInsertId();
        }
        return false;
    }



    // Listar todas las sesiones
    public function listar()
    {
        $query = "SELECT id, fecha, hora FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Encontrar sesión por ID
    public function encontrarPorId($id)
    {
        $query = "SELECT id, fecha, hora FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Actualizar una sesión
    public function actualizar($id, $fecha, $hora)
    {
        $query = "UPDATE " . $this->table_name . " SET fecha = :fecha, hora = :hora WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Eliminar una sesión
    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
