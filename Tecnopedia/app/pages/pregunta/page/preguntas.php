<?php
// Incluir la configuración de la conexión
include '../../../../connection.php';

// Asegurarnos de que la conexión utilice UTF-8
$conn->set_charset("utf8mb4");

// Función para obtener las preguntas respondidas
function obtenerPreguntasRespondidas($conn) {
    $preguntas = [];
    
    // Consulta para obtener las 30 preguntas
    $sql = "SELECT pregunta_1, pregunta_2, pregunta_3, pregunta_4, pregunta_5, 
                   pregunta_6, pregunta_7, pregunta_8, pregunta_9, pregunta_10,
                   pregunta_11, pregunta_12, pregunta_13, pregunta_14, pregunta_15,
                   pregunta_16, pregunta_17, pregunta_18, pregunta_19, pregunta_20,
                   pregunta_21, pregunta_22, pregunta_23, pregunta_24, pregunta_25,
                   pregunta_26, pregunta_27, pregunta_28, pregunta_29, pregunta_30
            FROM PREGUNTAS_RESPUESTAS
            LIMIT 1";

    $resultado = $conn->query($sql);
    
    if ($resultado && $fila = $resultado->fetch_assoc()) {
        // Iterar sobre las preguntas y asignar valores solo si están respondidas
        for ($i = 1; $i <= 30; $i++) {
            $respuesta = $fila["pregunta_$i"];
            if ($respuesta && $respuesta != "Aún no has llegado a responder esta pregunta.") {
                $preguntas["pregunta_$i"] = $respuesta;
            }
        }
    }
    
    return $preguntas;
}

// Llamar a la función y devolver las preguntas
$preguntasRespondidas = obtenerPreguntasRespondidas($conn);

// Cerrar la conexión
$conn->close();
?>
