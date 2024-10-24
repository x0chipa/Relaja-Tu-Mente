<?php
require_once __DIR__ . '/../models/Usuario.php';

// Instancia del modelo Usuario
$usuarioModel = new Usuario();
$usuarios = $usuarioModel->listar();

// Si se va a eliminar un usuario
if (isset($_GET['delete_id'])) {
    $usuarioModel->eliminar($_GET['delete_id']);
    header("Location: crudUsuarios.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuarios</title>
    <link rel="stylesheet" href="../assets/css/styleCrudUsuarios.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Usuarios</h1>

        <!-- Botón para agregar un nuevo usuario -->
        <a href="formUsuario.php" class="btn btn-primary">Agregar Usuario</a>

        <!-- Tabla de usuarios -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario->id_usuario) ?></td>
                        <td><?= htmlspecialchars($usuario->user) ?></td>
                        <td>
                            <a href="formUsuario.php?id=<?= $usuario->id_usuario ?>">Editar</a>
                            <a href="crudUsuarios.php?delete_id=<?= $usuario->id_usuario ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
