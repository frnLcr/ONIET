<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Configurar la zona horaria de Buenos Aires
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Validar si el usuario tiene acceso como administrador
if (!isset($_SESSION['dni']) || $_SESSION['dni'] !== '2025') {
    echo json_encode(["success" => false, "message" => "Acceso denegado. Solo permitido para administradores."]);
    exit;
}

// Obtener datos enviados desde el formulario
$input = json_decode(file_get_contents('php://input'), true);
$duration = $input['duration'] ?? null;

// Validar que la duración es un número válido
if (is_null($duration) || !is_numeric($duration) || $duration <= 0) {
    echo json_encode(["success" => false, "message" => "Duración inválida."]);
    exit;
}

// Calcular el tiempo de inicio y fin
$start_time = new DateTime(); // Hora actual en Buenos Aires
$end_time = clone $start_time;
$end_time->add(new DateInterval('PT' . intval($duration) . 'M')); // Sumar la duración en minutos

// Conectar a la base de datos
include '../../../../connection.php';

// Formatear las fechas para usarlas en la consulta
$start_time_str = $start_time->format('Y-m-d H:i:s');
$end_time_str = $end_time->format('Y-m-d H:i:s');

// Verificar si ya existe un juego activo
$sql_check = "SELECT id FROM JUEGO WHERE fin > NOW()";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    // Si ya existe un juego activo, actualizar las fechas
    $sql_update = "UPDATE JUEGO SET inicio = ?, fin = ? WHERE id = ?";
    $row = $result->fetch_assoc();
    $game_id = $row['id'];

    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        echo json_encode(["success" => false, "message" => "Error interno del servidor (actualización)."]);
        exit;
    }
    $stmt_update->bind_param("ssi", $start_time_str, $end_time_str, $game_id);

    if ($stmt_update->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Juego actualizado correctamente.",
            "start_time" => $start_time_str,
            "end_time" => $end_time_str
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar el juego."]);
    }

    $stmt_update->close();
} else {
    // Si no existe un juego activo, insertar uno nuevo
    $sql_insert = "INSERT INTO JUEGO (inicio, fin) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    if (!$stmt_insert) {
        echo json_encode(["success" => false, "message" => "Error interno del servidor (inserción)."]);
        exit;
    }
    $stmt_insert->bind_param("ss", $start_time_str, $end_time_str);

    if ($stmt_insert->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Juego iniciado correctamente.",
            "start_time" => $start_time_str,
            "end_time" => $end_time_str
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al iniciar el juego."]);
    }

    $stmt_insert->close();
}

// Cerrar conexiones
$conn->close();
?>
