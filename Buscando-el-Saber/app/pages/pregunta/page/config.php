<?php
// Establecer la zona horaria de Buenos Aires, Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

include '../../../../connection.php';

// Consulta para obtener inicio, fin y el ID más grande
$sql = "SELECT MIN(inicio) AS inicio, MAX(fin) AS fin, MAX(id) AS ultimo_id FROM juego";

$result = $conn->query($sql);

if (!$result) {
    error_log("Error al ejecutar la consulta: " . $conn->error);
    die(json_encode(["success" => false, "message" => "Error al obtener datos de la tabla juego."]));
}

// Verificar si se obtuvo un resultado válido
if ($result->num_rows === 0) {
    error_log("No se encontraron resultados en la tabla juego.");
    die(json_encode(["success" => false, "message" => "No se encontraron datos en la tabla juego."]));
}

// Obtener datos
$row = $result->fetch_assoc();
$inicio = $row['inicio'];      // Fecha de inicio más temprana
$fin = $row['fin'];            // Fecha de fin más reciente
$ultimoID = $row['ultimo_id']; // ID más grande (último)

// Cerrar la conexión a la base de datos
$conn->close();

// Mostrar los resultados (puedes quitar esta parte si no deseas imprimir directamente)
// echo json_encode([
//     "success" => true,
//     "inicio" => $inicio,
//     "fin" => $fin,
//     "ultimo_id" => $ultimoID
// ]);
?>
