<?php

require_once __DIR__ . '/../../config/database.php';

class Usuario
{
    private $conn;
    private $table_name = "usuario";

    public $id_usuario;
    public $user;
    public $password;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Getter para acceder a la conexión desde fuera
    public function getConnection()
    {
        return $this->conn;
    }

    // Listar todos los usuarios
    public function listar()
    {
        $query = "SELECT id_usuario, user, password FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Encontrar usuario por ID
    public function encontrarPorId($id_usuario)
    {
        $query = "SELECT id_usuario, user, password FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Encontrar usuario por nombre de usuario
    public function encontrarPorUser($username)
    {
        $query = "SELECT id_usuario, user, password FROM " . $this->table_name . " WHERE user = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Insertar un nuevo usuario
    public function insertar($user, $password)
    {
        $query = "INSERT INTO " . $this->table_name . " (user, password) VALUES (:user, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT)); // Almacenar la contraseña de forma segura

        return $stmt->execute();
    }

    // Actualizar un usuario
    public function actualizar($id_usuario, $user, $password)
    {
        if (!empty($password)) {
            // Actualizar con nueva contraseña
            $query = "UPDATE " . $this->table_name . " SET user = :user, password = :password WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT)); // Aquí se encripta la nueva contraseña
        } else {
            // Actualizar sin cambiar la contraseña
            $query = "UPDATE " . $this->table_name . " SET user = :user WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Eliminar un usuario
    public function eliminar($id_usuario)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
