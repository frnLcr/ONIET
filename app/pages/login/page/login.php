<?php
// Iniciar sesión
session_start();

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";  // Usuario por defecto de XAMPP
$password = "";  // Contraseña vacía por defecto en XAMPP
$dbname = "resolution_qrproyecto";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$usuario = $_POST['usuario']; // Cambiar aquí para obtener el nombre de usuario
$dni = $_POST['dni'];

// Verificar si los campos no están vacíos
if (!empty($usuario) && !empty($dni)) {
    // Preparar la consulta SQL
    $sql = "SELECT * FROM USUARIOS WHERE usuario = ? AND dni = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $dni); // Cambiar $nombre por $usuario

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

        // Guardar el nombre de usuario y puntaje en la sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['puntaje'] = $puntaje;
        $_SESSION['usuario'] = $usuario;
        $_SESSION['dni'] = $dni;
        $_SESSION['mail'] = $mail;

        // Enviar respuesta como JSON
        echo json_encode(["success" => true, "nombre" => $nombre]);
        exit;
    } else {
        // Usuario o DNI incorrecto
        echo json_encode(["success" => false, "message" => "Usuario o DNI incorrecto."]);
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Por favor, complete todos los campos."]);
}
?>