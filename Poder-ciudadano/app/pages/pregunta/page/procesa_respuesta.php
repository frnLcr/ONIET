<?php
session_start();

// Datos de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resolution_qrproyecto";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$userId = $_SESSION['id'];
$preguntaActual = $_SESSION['contador_preguntas'];

// Verificar si es la última pregunta
if ($preguntaActual == 5) {
    // Calcular el tiempo total
    $horaInicio = $_SESSION['hora_inicio'];
    $tiempoTotal = time() - $horaInicio;

    // Guardar el tiempo total en la base de datos
    $sql = "UPDATE USUARIOS SET tiempo_total = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tiempoTotal, $userId);
    $stmt->execute();
    $stmt->close();

    // Redirigir al ranking
    header("Location: ranking.php");
    exit;
}

// Incrementar el contador de preguntas
$_SESSION['contador_preguntas']++;
$conn->close();

// Redirigir a la siguiente pregunta
header("Location: pregunta.php");
?>
