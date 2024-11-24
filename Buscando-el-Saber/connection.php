<?php
// connection.php

// Configuración de la base de datos
$servername = "localhost"; // Cambia esto si el servidor no es localhost
$username = "root"; // Cambia esto si tu usuario de base de datos es diferente
$password = ""; // Cambia esto si tu base de datos tiene una contraseña
$dbname = "resolution_qrproyecto";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8mb4");

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>