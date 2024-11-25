<?php
// Incluir la configuración de la conexión
include '../../../../connection.php';

// Asegurarnos de que la conexión utilice UTF-8
$conn->set_charset("utf8mb4");

// Función para obtener las respuestas de RESPUESTAS_USUARIO
function obtenerRespuestasUsuario($conn) {
    $respuestas = [];
    
    // Consulta para obtener las respuestas de las 30 preguntas de la tabla RESPUESTAS_USUARIO
    $sql = "SELECT respuesta_1, respuesta_2, respuesta_3, respuesta_4, respuesta_5, 
                   respuesta_6, respuesta_7, respuesta_8, respuesta_9, respuesta_10,
                   respuesta_11, respuesta_12, respuesta_13, respuesta_14, respuesta_15,
                   respuesta_16, respuesta_17, respuesta_18, respuesta_19, respuesta_20,
                   respuesta_21, respuesta_22, respuesta_23, respuesta_24, respuesta_25,
                   respuesta_26, respuesta_27, respuesta_28, respuesta_29, respuesta_30
            FROM RESPUESTAS_USUARIO
            LIMIT 1"; // Asegúrate de que solo obtienes un único registro

    $resultado = $conn->query($sql);
    
    if ($resultado && $fila = $resultado->fetch_assoc()) {
        // Iterar sobre las respuestas y asignar valores solo si están respondidas
        for ($i = 1; $i <= 30; $i++) {
            $respuesta = $fila["respuesta_$i"];
            if ($respuesta && $respuesta != "Aún no has llegado a responder esta pregunta.") {
                $respuestas["respuesta_$i"] = $respuesta;
            }
        }
    }
    
    return $respuestas;
}

$respuestasUsuario = obtenerRespuestasUsuario($conn);

$conn->close();
?>
