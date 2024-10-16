<?php
// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "resolution_qrproyecto");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener el usuario actual desde la sesión
$usuario_actual = $_SESSION['usuario']; // El usuario actual (usuario de la sesión)

// Consulta para obtener todos los usuarios ordenados por puntaje
$sql = "SELECT nombre, puntaje, usuario FROM USUARIOS ORDER BY puntaje DESC";
$resultado = $conexion->query($sql);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    // Mostrar el top 3 con tabla
    echo "<h2 style='text-align: center;'>Top 3 Usuarios</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; border-radius: 5px; overflow: hidden;'>";
    echo "<tr><th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Nombre</th><th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Puntaje</th></tr>";

    for ($i = 0; $i < 3 && $fila = $resultado->fetch_assoc(); $i++) {
        // Verificar si el usuario actual es el que está en la fila
        $row_style = ($fila["usuario"] == $usuario_actual) ? "background-color: #a62c2c; color: #ffffff;" : "";
        
        echo "<tr style='$row_style'>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["nombre"] ."</td>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["puntaje"] ."</td>
            </tr>";
    }

    echo "</table>";

    // Resetear el puntero de resultado para la segunda consulta
    $resultado->data_seek(0);

    // Mostrar el ranking completo con tabla
    echo "<h2 style='text-align: center;'>Ranking de Usuarios</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; border-radius: 5px; overflow: hidden;'>";
    echo "<tr><th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Nombre</th><th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Puntaje</th></tr>";

    while ($fila = $resultado->fetch_assoc()) {
        // Verificar si el usuario actual es el que está en la fila
        $row_style = ($fila["usuario"] == $usuario_actual) ? "background-color: #a62c2c; color: #ffffff;" : "";
        
        echo "<tr style='$row_style'>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["nombre"] ."</td>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["puntaje"] ."</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No hay resultados.";
}

// Cerrar la conexión
$conexion->close();
?>
