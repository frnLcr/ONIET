<?php
// Iniciar la sesión
session_start();

// Conectar a la base de datos
include '../../../connection.php';

//$usuario_actual = $_SESSION['usuario']; // El usuario actual (usuario de la sesión)

if (!isset($_SESSION['usuario'])) {
    echo "Error: Usuario no autenticado.";
    exit;
}


$_SESSION['contador_preguntas'] = 0;

// Verificar si el tiempo de inicio ya está en la sesión
if (isset($_SESSION['tiempo_inicio'])) {
    // Calcular el tiempo total en segundos
    $tiempoFin = time();
    $tiempoTotal = $tiempoFin - $_SESSION['tiempo_inicio'];

    // Formatear el tiempo total en horas, minutos y segundos
    $horas = str_pad(floor($tiempoTotal / 3600), 2, "0", STR_PAD_LEFT);
    $minutos = str_pad(floor(($tiempoTotal % 3600) / 60), 2, "0", STR_PAD_LEFT);
    $segundos = str_pad($tiempoTotal % 60, 2, "0", STR_PAD_LEFT);
    
    // Actualizar el tiempo en la base de datos solo si es menor que el tiempo guardado
    $conexion = new mysqli("localhost", "root", "", "resolution_qrproyecto");
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    $usuario_actual = $_SESSION['usuario'];
    $sql = "UPDATE USUARIOS SET tiempo_total = ? WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $tiempoTotal, $usuario_actual);
    $stmt->execute();
    $stmt->close();
}
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
        <h1>FINALIZÓ EL JUEGO</h1>
    </div>  
    <div>
        <p class="msj-final">Tu tiempo total fue: <?php echo $horas . ":" . $minutos . ":" . $segundos; ?></p>
        <?php include 'ranking_final.php'; // Ranking que incluye la columna de tiempo total ?>
    </div>
    <div>
        <button class="msj-final" onclick="resetGame()">
            Gracias por jugar!
        </button>
    </div>

    <script>
        function resetGame() {
            fetch('reset.php') // Llama a reset.php para reiniciar la sesión
                .then(response => window.location.href = '../../../index.php') // Redirige al index
                .catch(error => console.error('Error al resetear el juego:', error));
        }
    </script>
</body>
</html>
