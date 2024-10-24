<?php
session_start();
session_unset(); // Limpiar todas las variables de la sesión
session_destroy(); // Destruir la sesión actual
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relaja tu Mente - Home</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script defer src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/css/styleFooter.css">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <style>
        /* Estilos generales */
        body {
            background-color: #e6f7ff;
            margin: 0;
            font-family: 'Montserrat Alternates', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: #ccf2f4;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 0 20px;
        }

        .logo img {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 3rem;
            color: #004d4d;
            margin-bottom: 20px;
            animation: slideIn 1s ease-out;
        }

        p {
            font-size: 1.5rem;
            color: #007b80;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .buttons {
            margin-bottom: 50px;
        }

        .button-wrapper {
            position: relative;
            display: inline-block;
            margin: 10px;
        }

        .button {
            z-index: 1;
            position: relative;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            padding: 15px 50px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
            background-color: #007b80;
            border: none;
            border-radius: 50px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            white-space: nowrap;
        }

        .button::before,
        .button::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            border-radius: 999px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .button::before {
            box-shadow: 0px 0px 24px 0px rgba(0, 123, 128, 0.7);
            /* Sombra con un tono similar al botón */
            mix-blend-mode: screen;
        }

        .button::after {
            box-shadow: 0px 0px 23px 0px rgba(0, 123, 128, 0.5) inset, 0px 0px 8px 0px rgba(0, 255, 255, 0.4);
            /* Sombra interior */
        }

        .button-wrapper:hover .button::before,
        .button-wrapper:hover .button::after {
            opacity: 1;
        }

        .button-wrapper:hover .dot {
            transform: translate(0, 0) rotate(var(--rotatation));
        }

        .dot {
            display: block;
            position: absolute;
            transition: transform calc(var(--speed) / 12) ease;
            width: var(--size);
            height: var(--size);
            transform: translate(var(--starting-x), var(--starting-y)) rotate(var(--rotatation));
        }

        .dot::after {
            content: "";
            animation: hoverFirefly var(--speed) infinite, dimFirefly calc(var(--speed) / 2) infinite calc(var(--speed) / 3);
            animation-play-state: paused;
            display: block;
            border-radius: 100%;
            background: #007b80;
            /* Color del botón */
            width: 100%;
            height: 100%;
            box-shadow: 0px 0px 6px 0px rgba(0, 123, 128, 0.7), 0px 0px 4px 0px rgba(0, 123, 128, 0.5) inset, 0px 0px 2px 1px rgba(0, 255, 255, 0.4);
            /* Sombras */
        }

        /* Definición de los puntos animados */
        .dot-1 {
            --rotatation: 0deg;
            --speed: 14s;
            --size: 6px;
            --starting-x: 30px;
            --starting-y: 20px;
            top: 2px;
            left: -16px;
            opacity: 0.7;
        }

        .dot-2 {
            --rotatation: 122deg;
            --speed: 16s;
            --size: 3px;
            --starting-x: 40px;
            --starting-y: 10px;
            top: 1px;
            left: 0px;
            opacity: 0.7;
        }

        .dot-3 {
            --rotatation: 39deg;
            --speed: 20s;
            --size: 4px;
            --starting-x: -10px;
            --starting-y: 20px;
            top: -8px;
            right: 14px;
        }

        .dot-4 {
            --rotatation: 220deg;
            --speed: 18s;
            --size: 2px;
            --starting-x: -30px;
            --starting-y: -5px;
            bottom: 4px;
            right: -14px;
            opacity: 0.9;
        }

        .dot-5 {
            --rotatation: 190deg;
            --speed: 22s;
            --size: 5px;
            --starting-x: -40px;
            --starting-y: -20px;
            bottom: -6px;
            right: -3px;
        }

        .dot-6 {
            --rotatation: 20deg;
            --speed: 15s;
            --size: 4px;
            --starting-x: 12px;
            --starting-y: -18px;
            bottom: -12px;
            left: 30px;
            opacity: 0.7;
        }

        .dot-7 {
            --rotatation: 300deg;
            --speed: 19s;
            --size: 3px;
            --starting-x: 6px;
            --starting-y: -20px;
            bottom: -16px;
            left: 44px;
        }

        @keyframes dimFirefly {
            0% {
                opacity: 1;
            }

            25% {
                opacity: 0.4;
            }

            50% {
                opacity: 0.8;
            }

            75% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes hoverFirefly {
            0% {
                transform: translate(0, 0);
            }

            12% {
                transform: translate(3px, 1px);
            }

            24% {
                transform: translate(-2px, 3px);
            }

            37% {
                transform: translate(2px, -2px);
            }

            55% {
                transform: translate(-1px, 0);
            }

            74% {
                transform: translate(0, 2px);
            }

            88% {
                transform: translate(-3px, -1px);
            }

            100% {
                transform: translate(0, 0);
            }
        }

        footer {
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #ccf2f4;
            font-size: 0.8rem;
            color: #004d4d;
        }
        .admin-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007b80;
            color: #ffffff;
            text-decoration: none; /* Quitar el subrayado */
            font-size: 1rem;
            font-weight: bold;
            border-radius: 25px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra para darle profundidad */
            transition: background-color 0.3s ease, transform 0.3s ease; /* Transiciones suaves */
        }

        .admin-button:hover {
            background-color: #005f5f; /* Color de fondo al pasar el cursor */
            transform: translateY(-2px); /* Efecto de levantar al hacer hover */
            text-decoration: none; /* Asegurarse de que el subrayado no aparezca al hacer hover */
        }

        .admin-button:active {
            transform: translateY(1px); /* Efecto al hacer clic */
        }

        @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css");
        @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.2.1/font-awesome-animation.min.css");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        footer {
            text-align: center;
            padding: 40px 0;
        }

        .social-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            gap: 30px;
        }

        .social-bar a {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            background-color: #fff;
            font-size: 24px;
            box-shadow: 0 5px 15px rgba(93, 70, 232, 0.15);
            transition: transform 0.3s ease;
        }

        /* Colores para cada ícono */
        .social-bar a:nth-child(1) i {
            color: #3b5998; /* Facebook */
        }

        .social-bar a:nth-child(2) i {
            color: #db4437; /* Google Plus */
        }

        .social-bar a:nth-child(3) i {
            color: #1da1f2; /* Twitter */
        }

        .social-bar a:nth-child(4) i {
            color: #e4405f; /* Instagram */
        }

        .social-bar a:nth-child(5) i {
            color: #0077b5; /* LinkedIn */
        }

        .social-bar a:nth-child(6) i {
            color: #bd081c; /* Pinterest */
        }

        .social-bar a:nth-child(7) i {
            color: #00aff0; /* Skype */
        }

        .social-bar a:nth-child(8) i {
            color: #ff0000; /* YouTube */
        }

        .social-bar a:nth-child(9) i {
            color: #1769ff; /* Behance */
        }

        .social-bar a:nth-child(10) i {
            color: #ea4c89; /* Dribbble */
        }

        .social-bar a::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 100%;
            width: 80px;
            height: 2px;
            background-image: linear-gradient(to right, rgba(132, 132, 164, 0.35) 50%, transparent 50%);
            background-size: 10px 2px;
            animation: slide 1s linear infinite;
        }

        .social-bar a:last-child::before {
            display: none;
        }

        .social-bar a::after {
            content: "";
            position: absolute;
            top: -15px;
            left: -15px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid rgba(132, 132, 164, 0.35);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .social-bar a:hover::after {
            opacity: 1;
            animation: focuse 1.5s linear infinite;
        }

        .social-bar a:hover {
            transform: scale(1.2);
        }

        @keyframes focuse {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            75% {
                transform: scale(1.2);
                opacity: 0;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        @keyframes slide {
            from {
                background-position: 0 0;
            }
            to {
                background-position: 40px 0;
            }
        }

        .jello-horizontal {
            animation: jello-horizontal 0.9s both;
        }

        .social-bar a:hover i {
            animation: jello-horizontal 0.9s both;
        }

        @keyframes jello-horizontal {
            0% {
                transform: scale3d(1, 1, 1);
            }
            30% {
                transform: scale3d(1.25, 0.75, 1);
            }
            40% {
                transform: scale3d(0.75, 1.25, 1);
            }
            50% {
                transform: scale3d(1.15, 0.85, 1);
            }
            65% {
                transform: scale3d(0.95, 1.05, 1);
            }
            75% {
                transform: scale3d(1.05, 0.95, 1);
            }
            100% {
                transform: scale3d(1, 1, 1);
            }
        }

        @media (max-width: 768px) {
            .social-bar {
                flex-wrap: wrap;
            }

            .social-bar a {
                margin: 15px;
            }

            .social-bar a::before {
                display: none;
            }
        }

        .slider-nav-item {
            color: #007b80;
            font-size: 1.5rem;
            text-decoration: none; /* Quita la línea subrayada */
            transition: color 0.3s;
        }

        footer {
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #ccf2f4;
            font-size: 0.8rem;
            color: #004d4d;
        }

    </style>
</head>

<body>
    <div class="container-wrapper">
        <div class="container">
        <div class="swiper-slide">
          <div class="content" data-content="one">
            <!---->
            <div class="logo">
                <img src="../assets/img/icon.png" alt="Logo de la aplicación">
            </div>
            <h2>Relaja tu Mente</h2>
            <p>Mide y Entiende: Encuentra tu equilibrio con SISCO y DASS-21</p>
            <div class="buttons">
                <a class="button-wrapper" href="intro.php?id=1">
                    <span class="dot dot-1"></span>
                    <span class="dot dot-2"></span>
                    <span class="dot dot-3"></span>
                    <span class="dot dot-4"></span>
                    <span class="dot dot-5"></span>
                    <span class="dot dot-6"></span>
                    <span class="dot dot-7"></span>
                    <span class="button">Test DASS-21</span>
                </a>
                <a class="button-wrapper" href="intro.php?id=2">
                    <span class="dot dot-1"></span>
                    <span class="dot dot-2"></span>
                    <span class="dot dot-3"></span>
                    <span class="dot dot-4"></span>
                    <span class="dot dot-5"></span>
                    <span class="dot dot-6"></span>
                    <span class="dot dot-7"></span>
                    <span class="button">Test SISCO</span>
                </a>

                <!-- ejemplo de como añadir un boton para actualizaciones futuras -->
                <!-- <a class="button-wrapper" href="intro.php?id=ID_INSTRUMENTO">
                    <span class="dot dot-1"></span>
                    <span class="dot dot-2"></span>
                    <span class="dot dot-3"></span>
                    <span class="dot dot-4"></span>
                    <span class="dot dot-5"></span>
                    <span class="dot dot-6"></span>
                    <span class="dot dot-7"></span>
                    <span class="button">Nombre instrumento</span>
                </a> -->

            </div>
            <a href="../views/loginAdmin.php" class="admin-button">Administrador</a>
            <!---->
          </div>
          <div class="background" data-item="one"></div>
        </div>
        </div>
    </div>
    
     <!-- Incluir el footer desde footer.php -->
    <footer>
        <div class="social-bar">
            <a href="tel:+5212223083630" class="slider-nav-item" aria-label="Teléfono">
                <i class="fas fa-phone"></i>
            </a>
            <a href="https://outlook.live.com/mail/0/" class="slider-nav-item" aria-label="Correo">
                <i class="fas fa-envelope"></i>
            </a>
            <a href="https://www.linkedin.com/in/x0chipa" class="slider-nav-item" aria-label="LinkedIn" target="_blank">
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="https://github.com/tony89767698" class="slider-nav-item" aria-label="GitHub" target="_blank">
                <i class="fab fa-github"></i>
            </a>
        </div>
    </footer>


</body>

</html>