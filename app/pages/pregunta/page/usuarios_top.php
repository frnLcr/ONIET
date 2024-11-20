<?php
// Iniciar sesión
session_start();

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "resolution_qrproyecto");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    echo "Por favor, inicia sesión para acceder a esta página.";
    exit; // Termina el script si no hay sesión activa
}

// Usar el ID del usuario de la sesión
$id_usuario_actual = $_SESSION['id']; // Obtiene el ID del usuario desde la sesión

// Consulta para obtener los 5 usuarios con mayor puntaje
$sql_top = "SELECT nombre, puntaje FROM USUARIOS ORDER BY puntaje DESC LIMIT 5";
$resultado_top = $conexion->query($sql_top);

// Verificar si hay resultados
$top_usuarios = [];
if ($resultado_top->num_rows > 0) {
    echo "<table style='width: 100%; border-collapse: collapse; text-align: center;'>";
    echo "<thead><tr><th style='border: 1px solid #800020; padding: 8px;'>Nombre</th><th style='border: 1px solid #800020; padding: 8px;'>Puntaje</th></tr></thead>";
    echo "<tbody>";

    // Almacenar los resultados de los top 5 en un arreglo
    while($fila = $resultado_top->fetch_assoc()) {
        $top_usuarios[] = $fila;

        // Mostrar cada fila del top 5 con dos columnas
        echo "<tr>
                <td style='border: 1px solid #800020; padding: 8px;'>" . $fila["nombre"] . "</td>
                <td style='border: 1px solid #800020; padding: 8px;'>" . $fila["puntaje"] . "</td>
              </tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "No hay resultados.";
}

// Consulta para obtener el puntaje del usuario actual
$sql_usuario = "SELECT nombre, puntaje FROM USUARIOS WHERE id = ?";
$stmt = $conexion->prepare($sql_usuario);
$stmt->bind_param("i", $id_usuario_actual);
$stmt->execute();
$resultado_usuario = $stmt->get_result();

if ($resultado_usuario->num_rows > 0) {
    $usuario_actual = $resultado_usuario->fetch_assoc();

    // Verificar si el usuario actual está en el top 5
    $en_top_5 = false;
    foreach ($top_usuarios as $usuario) {
        if ($usuario["nombre"] == $usuario_actual["nombre"]) {
            $en_top_5 = true;
            break;
        }
    }

    // Si el usuario no está en el top 5, mostrar su puntaje
    if (!$en_top_5) {
        // Agregar los puntos como separador
        echo "<p>...</p>"; // Puedes cambiar esto a <hr> o algo que desees si prefieres otra estética
        
        echo "<table style='width: 100%; border-collapse: collapse; text-align: center;'>";
        echo "<tr>
                <td style='border: 1px solid #800020; padding: 8px;'>" . $usuario_actual["nombre"] . "</td>
                <td style='border: 1px solid #800020; padding: 8px;'>" . $usuario_actual["puntaje"] . "</td>
              </tr>";
        echo "</table>";
    }
} else {
    echo "Usuario no encontrado.";
}

// Cerrar la conexión
$conexion->close();
?>