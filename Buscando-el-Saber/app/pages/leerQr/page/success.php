<?php
session_start();
include '../../../../connection.php';// Conexión a la base de datos

$orden = isset($_GET['orden']) ? intval($_GET['orden']) : 1;

// Aumentar el orden en la sesión
$_SESSION['orden'] = $orden + 1;

$_SESSION['qr_escaneado'] = true;

$response = ['success' => true];

// Aquí puedes añadir lógica para guardar las respuestas correctas en la base de datos, si es necesario

echo json_encode($response);

$conn->close();
?>