<?php
// Iniciar la sesión
session_start();

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "resolution_qrproyecto");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener el usuario actual desde la sesión
$usuario_actual = $_SESSION['usuario']; // El usuario actual (usuario de la sesión)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/game_over.css">
    <title>Ranking Final</title>
</head>
<body class="bodyrankin">
    <div>
    <h1>FINALIZO EL JUEGO</h1>
    </div>
    <div>
    <?php include 'ranking_final.php';?>
    </div>
    <div>
        <p class="msj-final">Gracias por jugar!</p>
    </div>
</body>
</html>