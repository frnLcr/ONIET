<?php
// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "resolution_qrproyecto");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Consulta para obtener los 5 usuarios con mayor puntaje
$sql = "SELECT nombre, puntaje FROM usuarios ORDER BY puntaje DESC LIMIT 5";
$resultado = $conexion->query($sql);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    // Imprimir los resultados en formato HTML
    echo "<ul>";
    while($fila = $resultado->fetch_assoc()) {
        echo "<li>" . $fila["nombre"] . " - Puntaje: " . $fila["puntaje"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No hay resultados.";
}

// Cerrar la conexión
$conexion->close();
?>
