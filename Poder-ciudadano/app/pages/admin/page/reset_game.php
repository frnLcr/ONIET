<?php
session_start();
if (!isset($_SESSION['dni']) || $_SESSION['dni'] !== '2025') {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
    exit;
}

// Conectar a la base de datos
include '../../../../connection.php';

// Reiniciar la tabla "juego"
$sql = "TRUNCATE TABLE JUEGO"; // Elimina todos los datos de la tabla "juego"

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Datos eliminados correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al reiniciar el juego: ' . $conn->error]);
}

$conn->close();
?>
