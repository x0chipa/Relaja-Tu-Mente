<?php
session_start();

// Obtener el ID del instrumento desde la URL
$instrumento_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$instrumento_id) {
    echo "ID de instrumento no proporcionado.";
    exit();
}

// Configurar los detalles específicos de cada instrumento
$instrumentos = [
    1 => [
        'titulo' => 'Instrumento de medición de Estrés, Ansiedad y Depresión basado en DASS-21',
        'descripcion' => 'La depresión, la ansiedad y el estrés son estados emocionales que pueden impactar significativamente la vida diaria y el bienestar general de una persona.',
        'boton_texto' => 'Iniciar Test DASS',
    ],
    2 => [
        'titulo' => 'Instrumento de medición de Estrés basado en SISCO',
        'descripcion' => 'El estrés es necesario y esencial para nuestra supervivencia; sin embargo, experimentarlo por varios días y en intensos niveles puede traer consecuencias para la salud física y mental que nadie quiere experimentar. Descubre en este test si sufres de estrés.',
        'boton_texto' => 'Iniciar Test SISCO',
    ],
];

// Verificar si el instrumento existe en la configuración
if (!array_key_exists($instrumento_id, $instrumentos)) {
    echo "Instrumento no válido.";
    exit();
}

// Obtener los detalles del instrumento
$instrumento = $instrumentos[$instrumento_id];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $instrumento['titulo'] ?> - Relaja tu Mente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <style>
       body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(120deg, #a0e3f0, #ccf2f4);
            color: #004d4d;
            margin: 0;
            padding: 0;
        }

        .navbar-custom {
            background: linear-gradient(120deg, #a0e3f0, #ccf2f4);
        }

        .wrapper {
            background: #ffffff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            max-width: 1000px;
            width: 90%;
            margin: 150px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .header-content {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-content h1 {
            font-size: 2.8rem;
            color: #007b80;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .header-content p {
            font-size: 1.2rem;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .header-content h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #006060;
        }

        .btn-1 {
            background: linear-gradient(120deg, #007b80, #00bfa5);
            color: #fff;
            padding: 15px 25px;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            transition: background 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            cursor: pointer;
        }

        .btn-1:hover {
            background: linear-gradient(120deg, #00bfa5, #007b80);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .header-content h1 {
                font-size: 2.2rem;
            }

            .header-content p {
                font-size: 1rem;
            }

            .btn-1 {
                font-size: 1rem;
                padding: 12px 20px;
            }
        }
        .navbar-brand, .nav-link{
            color: #007b80;
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
            background: linear-gradient(120deg, #a0e3f0, #ccf2f4);
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

    <div class="wrapper">
        <div class="header-content container">
            <h1><?= $instrumento['titulo'] ?></h1>
            <p><?= $instrumento['descripcion'] ?></p>
            <h3>No te tomará mucho tiempo</h3>
            <p>Tardarás menos de <b>3 minutos</b> en responder este cuestionario. Por favor, lee cada pregunta con atención y selecciona la respuesta que más se ajuste a tu caso. No hay respuestas incorrectas, lo importante es que respondas con honestidad. Recuerda que tu resultado es confidencial y no será publicado ni compartido.</p>
            <form action="../controllers/listarPreguntas.php" method="GET">
                <input type="hidden" name="id" value="<?= $instrumento_id ?>">
                <button type="submit" class="btn-1"><?= $instrumento['boton_texto'] ?> <i class="fa fa-arrow-right"></i></button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>© 2024 FCC BUAP y SOTEK SA de CV. Todos los derechos reservados.</p>
        <p>Contacto: <a href="mailto:info@fccbuap.mx">info@fccbuap.mx</a> | Tel: +52 123 456 7890</p>
        <p><a href="/terminos">Términos y Condiciones</a> | <a href="/privacidad">Política de Privacidad</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
