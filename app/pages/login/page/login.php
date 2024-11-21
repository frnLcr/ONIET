<?php
session_start();

// Encabezado para asegurarte de que la respuesta sea JSON
header('Content-Type: application/json; charset=utf-8');

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resolution_qrproyecto";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    error_log("Error de conexión a la base de datos: " . $conn->connect_error);
    die(json_encode(["success" => false, "message" => "Error al conectar con la base de datos."]));
}

$conn->set_charset("utf8mb4");

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
    $nombre = $row['nombre'];
    $puntaje = $row['puntaje'];
    $id = $row['id'];

    $_SESSION['nombre'] = $nombre;
    $_SESSION['puntaje'] = $puntaje;
    $_SESSION['usuario'] = $usuario;

    echo json_encode(["success" => true, "nombre" => $nombre]);
} else {
    echo json_encode(["success" => false, "message" => "Usuario o DNI incorrecto."]);
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
