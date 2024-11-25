<?php
// Establecer la zona horaria de Buenos Aires, Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Datos de conexión a la base de datos
include '../../../../connection.php';


$conn->set_charset("utf8mb4");

// Consulta para obtener inicio, fin y el ID más grande
$sql = "SELECT MIN(inicio) AS inicio, MAX(fin) AS fin, MAX(id) AS ultimo_id FROM JUEGO";
$result = $conn->query($sql);

if (!$result) {
    error_log("Error al ejecutar la consulta: " . $conn->error);
    die(json_encode(["success" => false, "message" => "Error al obtener datos de la tabla juego."]));
}

// Obtener datos de la consulta
$row = $result->fetch_assoc();

if (!$row || $row['inicio'] === null || $row['fin'] === null || $row['ultimo_id'] === null) {
    $inicio = 0;
    $fin = 0;
    $ultimoID = 0;
} else {
    $inicio = $row['inicio'];      // Fecha de inicio más temprana
    $fin = $row['fin'];            // Fecha de fin más reciente
    $ultimoID = $row['ultimo_id']; // ID más grande
}

// Cerrar la conexión a la base de datos
$conn->close();

?>
