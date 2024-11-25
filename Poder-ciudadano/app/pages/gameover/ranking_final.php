<?php
// Conectar a la base de datos
include '../../../connection.php';

// Verificar conexión
//if ($conexion->connect_error) {
//    die("Error en la conexión: " . $conexion->connect_error);
//}

// Obtener el usuario actual desde la sesión
$usuario_actual = $_SESSION['usuario']; // El usuario actual (usuario de la sesión)

// Consulta para obtener todos los usuarios ordenados por puntaje y tiempo_total
$sql = "SELECT nombre, puntaje, usuario, tiempo_total FROM USUARIOS ORDER BY puntaje DESC, tiempo_total ASC";
$resultado = $conexion->query($sql);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    // Mostrar el top 3 con tabla
    echo "<h2 style='text-align: center;'>Top 3 Usuarios</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; border-radius: 5px; overflow: hidden;'>";
    echo "<tr><th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Nombre</th>
              <th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Puntaje</th>
              <th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Tiempo Total</th></tr>";

    for ($i = 0; $i < 3 && $fila = $resultado->fetch_assoc(); $i++) {
        // Verificar si el usuario actual es el que está en la fila
        $row_style = ($fila["usuario"] == $usuario_actual) ? "background-color: #a62c2c; color: #ffffff;" : "";
        
        // Formatear el tiempo total (en segundos) a horas, minutos, segundos
        $tiempo_total_formateado = gmdate("H:i:s", $fila["tiempo_total"]);

        echo "<tr style='$row_style'>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["nombre"] ."</td>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["puntaje"] ."</td>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $tiempo_total_formateado ."</td>
            </tr>";
    }

    echo "</table>";

    // Resetear el puntero de resultado para la segunda consulta
    $resultado->data_seek(0);

    // Mostrar el ranking completo con tabla
    echo "<h2 style='text-align: center;'>Ranking de Usuarios</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; border-radius: 5px; overflow: hidden;'>";
    echo "<tr>
            <th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Nombre</th>
            <th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Puntaje</th>
            <th style='border: 2px solid #800020; padding: 8px; text-align: center;'>Tiempo Total</th>
        </tr>";

    while ($fila = $resultado->fetch_assoc()) {
        // Verificar si el usuario actual es el que está en la fila
        $row_style = ($fila["usuario"] == $usuario_actual) ? "background-color: #a62c2c; color: #ffffff;" : "";
        
        // Formatear el tiempo total (en segundos) a horas, minutos, segundos
        $tiempo_total_formateado = gmdate("H:i:s", $fila["tiempo_total"]);

        echo "<tr style='$row_style'>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["nombre"] ."</td>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $fila["puntaje"] ."</td>
                <td style='border: 2px solid #800020; padding: 10px; text-align: center;'>". $tiempo_total_formateado ."</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No hay resultados.";
}

// Cerrar la conexión
$conexion->close();
?>
