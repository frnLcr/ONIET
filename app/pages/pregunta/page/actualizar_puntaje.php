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
    die("Conexi贸n fallida: " . $conn->connect_error);
}

// Recibir los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);
$puntaje = $data['puntaje'];
$isCorrect = $data['isCorrect']; // Verificar si la respuesta fue correcta
$usuario = $_SESSION['usuario'];

// Actualizamos preguntaCOR o preguntaINC dependiendo si es correcta o incorrecta
if ($isCorrect) {
    $sql = "UPDATE USUARIOS SET puntaje = puntaje + ?, preguntaCOR = preguntaCOR + 1 WHERE usuario = ?";
} else {
    $sql = "UPDATE USUARIOS SET puntaje = puntaje + ?, preguntaINC = preguntaINC + 1 WHERE usuario = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $puntaje, $usuario);

if ($stmt->execute()) {
    // Actualizar el puntaje en la sesi贸n
    $_SESSION['puntaje'] += $puntaje;

    // Recuperar los valores actualizados de preguntas correctas/incorrectas
    $result = $conn->query("SELECT preguntaCOR, preguntaINC FROM USUARIOS WHERE usuario = '$usuario'");
    $row = $result->fetch_assoc();

    // Actualizar los valores en la sesi贸n
    $_SESSION['preguntaCOR'] = $row['preguntaCOR'];
    $_SESSION['preguntaINC'] = $row['preguntaINC'];

    // Devolver los valores actualizados como respuesta JSON
    echo json_encode([
        "success" => true, 
        "puntaje" => $_SESSION['puntaje'], 
        "preguntaCOR" => $_SESSION['preguntaCOR'], 
        "preguntaINC" => $_SESSION['preguntaINC']
    ]);
} else {
    // En caso de error en la actualizaci贸n
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
