<?php
session_start(); // Asegúrate de iniciar la sesión

// Destruir todas las sesiones
session_unset();
session_destroy();

// Redirigir al usuario a la página de inicio (index.php en la raíz)
header("Location: ../../public/index.php");
exit();
