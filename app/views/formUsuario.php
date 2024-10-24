<?php
require_once __DIR__ . '/../models/Usuario.php';

// Instancia del modelo Usuario
$usuarioModel = new Usuario();
$usuario = null;
$error = null;

// Si se va a editar un usuario
if (isset($_GET['id'])) {
    $usuario = $usuarioModel->encontrarPorId($_GET['id']);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (isset($_POST['id'])) {
        // Actualizar usuario existente
        $usuarioModel->actualizar($_POST['id'], $username, $password);
    } else {
        // Insertar nuevo usuario
        $usuarioModel->insertar($username, $password);
    }

    // Redirigir a la lista de usuarios
    header("Location: crudUsuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($usuario) ? 'Editar Usuario' : 'Agregar Usuario' ?></title>
    <link rel="stylesheet" href="../assets/css/styleFormUsuario.css">
</head>
<body>
    <div class="container">
        <h1><?= isset($usuario) ? 'Editar Usuario' : 'Agregar Usuario' ?></h1>

        <!-- Formulario para agregar o editar usuario -->
        <form method="POST" action="formUsuario.php">
            <?php if (isset($usuario)): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->id_usuario) ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($usuario->user ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" <?= isset($usuario) ? '' : 'required' ?>>
                <?php if (isset($usuario)): ?>
                    <small>Deja el campo en blanco si no deseas cambiar la contraseña.</small>
                <?php endif; ?>
            </div>

            <button type="submit"><?= isset($usuario) ? 'Actualizar' : 'Agregar' ?></button>
        </form>

        <a href="crudUsuarios.php">Volver a la lista de usuarios</a>
    </div>
</body>
</html>
