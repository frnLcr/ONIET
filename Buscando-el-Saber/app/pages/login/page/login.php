<?php
session_start();

// Encabezado para asegurarte de que la respuesta sea JSON
header('Content-Type: application/json; charset=utf-8');

// Datos de conexión a la base de datos
include '../../../../connection.php';

// Obtener datos del formulario
$usuario = $_POST['usuario'] ?? null;
$dni = $_POST['dni'] ?? null;

// Verificar si los campos no están vacíos
if (empty($usuario) || empty($dni)) {
    echo json_encode(["success" => false, "message" => "Por favor, complete todos los campos."]);
    exit;
}

// Preparar la consulta SQL
$sql = "SELECT * FROM usuarios WHERE usuario = ? AND dni = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Error en la preparación de la consulta: " . $conn->error);
    echo json_encode(["success" => false, "message" => "Error interno del servidor."]);
    exit;
}

$stmt->bind_param("ss", $usuario, $dni);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró un usuario
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row['nombre']; // Este es el nombre completo que mostrarás
    $puntaje = $row['puntaje'];
    $dni = $row['dni'];
    $mail = $row['mail'];
    $id = $row['id']; // Asegúrate de que esto corresponda al campo ID en tu tabla
    $preguntaCOR = $row['preguntaCOR'];
    $preguntaINC = $row['preguntaINC'];

    // Guardar el nombre de usuario, puntaje y ID en la sesión
    $_SESSION['nombre'] = $nombre;
    $_SESSION['puntaje'] = $puntaje;
    $_SESSION['usuario'] = $usuario;
    $_SESSION['dni'] = $dni;
    $_SESSION['mail'] = $mail;
    $_SESSION['id'] = $id; // Guarda el ID del usuario
    $_SESSION['preguntaCOR'] = $preguntaCOR;
    $_SESSION['preguntaINC'] = $preguntaINC;


    // Incluir DNI en la respuesta JSON
    echo json_encode(["success" => true, "nombre" => $nombre, "dni" => $dni]);
} else {
    echo json_encode(["success" => false, "message" => "Usuario o DNI incorrecto."]);
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
