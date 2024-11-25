<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Validar acceso del administrador
if (!isset($_SESSION['dni']) || $_SESSION['dni'] !== '2025') {
    echo json_encode(["success" => false, "message" => "Acceso denegado."]);
    exit;
}

// Conectar a la base de datos
include '../../../../connection.php';

// Actualizar el estado del juego para detenerlo
$sql_stop = "UPDATE JUEGO SET fin = NOW() WHERE fin > NOW()";
if ($conn->query($sql_stop) === TRUE) {
    echo json_encode(["success" => true, "message" => "El juego ha sido detenido."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al detener el juego."]);
}

// Cerrar la conexión
$conn->close();
?>
