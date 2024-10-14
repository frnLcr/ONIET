<?php
// Iniciar la sesión
session_start();

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "resolution_qrproyecto");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener el usuario actual desde la sesión
$usuario_actual = $_SESSION['usuario']; // El usuario actual (usuario de la sesión)

// Consulta para obtener todos los usuarios ordenados por puntaje
$sql = "SELECT nombre, puntaje, usuario FROM usuarios ORDER BY puntaje DESC";
$resultado = $conexion->query($sql);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    // Consulta para obtener el top 3
    echo "<h2>Top 3 Usuarios</h2>";
    echo "<ul>";
    for ($i = 0; $i < 3 && $fila = $resultado->fetch_assoc(); $i++) {
        // Verificar si el usuario actual es el que está en la fila
        if ($fila["usuario"] == $usuario_actual) {
            echo "<li 
            style='background-color: #a62c2c;
            border: 2px solid #800020; /* Bordó */
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            font-size: 18px;
            color: #ffffff;
            transition: background-color 0.3s ease;
            text-align: left;
            display: flex;
            justify-content: space-between;'>" . $fila["nombre"] . " - Puntaje: " . $fila["puntaje"] . "</li>";
        } else {
            echo "<li>" . $fila["nombre"] . " - Puntaje: " . $fila["puntaje"] . "</li>";
        }
    }
    echo "</ul>";

    // Resetear el puntero de resultado para la segunda consulta
    $resultado->data_seek(0);

    // Imprimir el ranking completo
    echo "<h1>Ranking de Usuarios</h1>";
    echo "<ul>";
    while ($fila = $resultado->fetch_assoc()) {
        // Verificar si el usuario actual es el que está en la fila
        if ($fila["usuario"] == $usuario_actual) {
            echo "<li
            style='background-color: #a62c2c;
            border: 2px solid #800020; /* Bordó */
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            font-size: 18px;
            color: #ffffff;
            transition: background-color 0.3s ease;
            text-align: left;
            display: flex;
            justify-content: space-between;'>" . $fila["nombre"] . " - Puntaje: " . $fila["puntaje"] . "</li>";
        } else {
            echo "<li>" . $fila["nombre"] . " - Puntaje: " . $fila["puntaje"] . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "No hay resultados.";
}

// Cerrar la conexión
$conexion->close();
