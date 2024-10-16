<?php
session_start();
include 'db_connection.php'; // Conexión a la base de datos

if (!$conn) {
    echo json_encode(['error' => 'Error al conectar con la base de datos']);
    exit;
}

$orden = isset($_GET['orden']) ? intval($_GET['orden']) : 1;

// Número total de pistas (en este caso, 5)
$total_pistas = 15;

// Si el orden es mayor que el total de pistas, reiniciar a 1
if ($orden > $total_pistas) {
    $orden = 1;
}

// Consultar la pista correspondiente al orden
$query = "SELECT pista FROM QRS WHERE orden = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    echo json_encode(['error' => 'Error al preparar la consulta']);
    exit;
}

$stmt->bind_param("i", $orden);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['pista' => $row['pista']]);
} else {
    echo json_encode(['pista' => 'No se encontró la pista.']);
}

$stmt->close();
$conn->close();

?>
