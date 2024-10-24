<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

$error = null;

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Instancia del modelo Usuario
    $usuarioModel = new Usuario();

    // Buscar usuario en la base de datos
    $usuario = $usuarioModel->encontrarPorUser($username);

    // Verifica si se encontró el usuario y si la contraseña es correcta
    if ($usuario && password_verify($password, $usuario->password)) {
        // Iniciar sesión y almacenar información del usuario en la sesión
        $_SESSION['usuario_id'] = $usuario->id_usuario;
        $_SESSION['username'] = $usuario->user;

        // Redirigir al dashboard
        header("Location: adminDashboard.php");
        exit();
    } else {
        // Error de credenciales
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styleLoginAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <title>Login Admin</title>
    <style>
        body {
            padding-top: 70px; /* Para evitar que el contenido se superponga con la barra de navegación */
        }

        .navbar-custom {
            background-color: white;
        }
        .box {
            width: 200px;
        }
        .minibox {
            width: 30px;
        }
        .width {
            width: 100%;
        }
        .max {
            max-width: 100%;
        }
        /* Estilo general para asegurar que el footer esté en la parte inferior */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Altura mínima para asegurar que ocupe toda la pantalla */
            margin: 0;
        }

        .container {
            flex: 1; /* Hace que el contenido principal crezca y ocupe el espacio restante */
        }

        .footer {
            text-align: center;
            padding: 10px 0;
            background-color: white;
            color: #333;
            font-size: 0.85rem;
            border-top: 1px solid #ddd;
            position: relative;
            bottom: 0;
            width: 100%; /* Asegura que el footer ocupe todo el ancho de la pantalla */
            margin-top: 20px; /* Añade la separación superior */
        }

        .footer a {
            color: #007b80;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

    </style>
</head>

<body>
    <!-- Menú de navegación en la parte superior -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="homePage.php"><img class="minibox" src="../assets/img/icon.png" alt="Logo de la aplicación">Relaja tu Mente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="form">
            <div class="logo">
                <img src="../assets/img/icon.png" alt="Logo" style="width: 100px; height: auto;">
            
            </div>
            <h1 class="form__title">Administradores</h1>
            <p class="form__description">¡Bienvenido de nuevo! Por favor, introduce tus datos</p>

            <!-- Mostrar un mensaje de error si las credenciales son incorrectas -->
            <?php if ($error): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <!-- Formulario de inicio de sesión -->
            <form method="POST" action="loginAdmin.php">
                <label class="form-control__label">Usuario</label>
                <input type="text" class="form-control" name="username" required>

                <label class="form-control__label">Contrasena</label>
                <div class="password-field">
                    <input type="password" class="form-control" name="password" minlength="4" id="password" required>
                    <!-- Icono SVG para mostrar/ocultar contraseña -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" id="togglePassword">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </div>

                <div class="password__settings">
                    <label class="password__settings__remember">
                        <input type="checkbox" name="remember">
                        <span class="custom__checkbox">
                            <!-- Icono SVG del checkbox -->
                        </span>
                        Recordar Contrasena
                    </label>
                </div>

                <button type="submit" class="form__submit" id="submit">Entrar</button>
            </form>
        </section>

        <section class="form__animation">
            <!-- Animación -->
            <div id="ball">
                <div class="ball">
                    <div id="face">
                        <div class="ball__eyes">
                            <div class="eye_wrap"><span class="eye"></span></div>
                            <div class="eye_wrap"><span class="eye"></span></div>
                        </div>
                        <div class="ball__mouth"></div>
                    </div>
                </div>
              </div>
              <div class="ball__shadow"></div>
        </section>
    </main>

    
    <footer class="footer">
        <p>© 2024 FCC BUAP y SOTEK SA de CV. Todos los derechos reservados.</p>
        <p>Contacto: <a href="mailto:info@fccbuap.mx">info@fccbuap.mx</a> | Tel: +52 123 456 7890</p>
        <p><a href="/terminos">Términos y Condiciones</a> | <a href="/privacidad">Política de Privacidad</a></p>
    </footer>
    <script src="../assets/js/loginAdmin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
