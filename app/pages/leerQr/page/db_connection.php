<?php
$servername = "localhost"; // Normalmente es localhost en XAMPP
$username = "root"; // El usuario por defecto en XAMPP
$password = ""; // Por defecto no hay contraseña
$dbname = "resolution_qrproyecto"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>