<?php
session_start();

// Conectar a la base de datos
include "../../../../connection.php";

// Obtener los datos del frontend (JSON)
$data = json_decode(file_get_contents("php://input"), true);

$respuesta = $data["respuesta"];

// Validar si los datos llegaron correctamente
if (isset($respuesta)) {
    // Obtener el ID del usuario directamente desde la sesión
    if (isset($_SESSION["id"])) {
        $usuario_id = $_SESSION["id"];

        // Determinar si el usuario ya tiene un registro en RESPUESTAS_USUARIO
        $query = "SELECT * FROM RESPUESTAS_USUARIO WHERE usuario_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Si no hay registro, insertar una nueva fila para el usuario
            $insertQuery =
                "INSERT INTO RESPUESTAS_USUARIO (usuario_id) VALUES (?)";
            $stmtInsert = $conn->prepare($insertQuery);
            $stmtInsert->bind_param("i", $usuario_id);

            if (!$stmtInsert->execute()) {
                echo json_encode([
                    "success" => false,
                    "error" =>
                        "Error al crear el registro del usuario: " .
                        $stmtInsert->error,
                ]);
                $stmtInsert->close();
                $conn->close();
                exit();
            }
            $stmtInsert->close();
        }

        // Volver a ejecutar la consulta para obtener el registro actualizado
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $fila = $result->fetch_assoc();

            // Buscar la primera columna vacía (pregunta_1, pregunta_2, ..., pregunta_30)
            $columnaRespuesta = null;
            for ($i = 1; $i <= 30; $i++) {
                if (empty($fila["respuesta_$i"])) {
                    $columnaRespuesta = "respuesta_$i";
                    break;
                }
            }

            // Verificar si se encontró una columna vacía
            if ($columnaRespuesta) {
                // Actualizar la respuesta en la columna correspondiente
                $sql = "UPDATE RESPUESTAS_USUARIO SET $columnaRespuesta = ? WHERE usuario_id = ?";
                $stmtUpdate = $conn->prepare($sql);
                $stmtUpdate->bind_param("si", $respuesta, $usuario_id);

                if ($stmtUpdate->execute()) {
                    echo json_encode([
                        "success" => true,
                        "columna" => $columnaRespuesta,
                    ]); // Enviar la columna al frontend
                } else {
                    echo json_encode([
                        "success" => false,
                        "error" =>
                            "Error al guardar la respuesta: " .
                            $stmtUpdate->error,
                    ]);
                }

                $stmtUpdate->close();
            } else {
                echo json_encode([
                    "success" => false,
                    "error" => "No hay columnas disponibles",
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "error" =>
                    "Error inesperado: usuario no encontrado tras la inserción.",
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Usuario no está logueado",
        ]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Datos inválidos"]);
}

$conn->close();
?>
        