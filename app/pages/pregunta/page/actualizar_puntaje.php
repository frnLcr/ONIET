<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["success" => false, "error" => "Usuario no logueado"]);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resolution_qrproyecto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$puntaje = $data['puntaje'];
$usuario = $_SESSION['usuario'];

$sql = "UPDATE USUARIOS SET puntaje = puntaje + ? WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $puntaje, $usuario);

if ($stmt->execute()) {
    $_SESSION['puntaje'] += $puntaje;
    echo json_encode(["success" => true, "puntaje" => $_SESSION['puntaje']]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
