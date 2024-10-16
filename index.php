<?php
// Iniciar la sesión
session_start();

// Establecer la zona horaria de Buenos Aires, Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Define la hora de inicio y fin en formato 'Y-m-d H:i:s'
$horaInicio = '2024-10-14 00:28:00';  // Fecha y hora de inicio que tú determines
$horaFin = '2024-10-16 01:10:00';     // Fecha y hora de fin que tú determines

// Convertir las horas de inicio y fin a timestamps
$timestampInicio = strtotime($horaInicio);
$timestampFin = strtotime($horaFin);

// Calcular el tiempo restante en segundos
$tiempoActual = time();  // Hora actual en el servidor
$tiempoRestante = $timestampFin - $tiempoActual;
?>

<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./app/pages/login/css/login-styles.css">
    <title>ONIET - Iniciar Sesión</title>
    <style>
        /* Estilos para cubrir el login con el cronómetro */
        #overlay-cronometro {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8); /* Fondo oscuro para tapar */
            z-index: 1000; /* Encima de todo */
            color: white;
            font-size: 48px;
            font-family: Arial, sans-serif;
            text-align: center;
            .inicio{
                margin-top: 200px;
            }
        }
    </style>
</head>

<body class="bodymenu">
    <div id="overlay-cronometro" style="display: none;" >
        <div id="overlay-cronometro" >
            <p class="inicio ">El juego comienza en: </p>
            <div class="timer" id="cronometro"></div>
        </div>
    </div>
    <div>
        <div class="login" id="loginForm">
            <div class="login-header">
                <a href="https://oniet.ubp.edu.ar/">
                    <img src="./public/images/oniet-logo.png" alt="ONIET logo" style="width: 150px;">
                </a>
                <h2>Bienvenido</h2>
            </div>
            <form id="login-form" class="login-form" action="./app/pages/login/page/login.php" method="POST">
                <div class="login-labels">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required placeholder="Usuario">
                </div>
                <div class="login-labels">
                    <label for="dni">DNI</label>
                    <input type="text" id="dni" name="dni" required placeholder="DNI" inputmode="numeric" maxlength="8">
                </div>
                <button type="submit" class="submit-btn">Entrar</button>
            </form>
            <div style="display: flex; justify-content: space-between; align-items: center; ">
                <a class="link" href="http://www.ubp.edu.ar" target="_blank">
                    <img src="./public/images/ubp-white.jpg" alt="logo blas pascal" style: {width: 241px; height= "50px" }>
                </a>
                <a href="https://platform.openai.com/docs/api-reference/introduction">
                    <img  src="./public/images/openia-logo.png" alt="openia-logo" style="width:141px; height:30px ;">
                </a>
            </div>
            <div id="message" style="display: none; text-align: center; padding: 1.25rem;"></div>

        </div>
        <div style="text-align: center; margin-top: 5%;">
            2024 ©
            <a class="link" href="http://www.ubp.edu.ar" target="_blank">Universidad Blas Pascal</a> - ONIET.
        </div>
    </div>
    <script>
        //cronometro
        // Obtén el tiempo restante desde PHP (en segundos)
        let tiempoRestante = <?php echo $tiempoRestante; ?>;


        // Actualiza el cronómetro cada segundo
        let cronometro = setInterval(function() {
            // Calcula horas, minutos y segundos
            let horas = Math.floor(tiempoRestante / 3600);
            let minutos = Math.floor((tiempoRestante % 3600) / 60);
            let segundos = tiempoRestante % 60;

            // Agrega ceros si es necesario
            horas = horas < 10 ? "0" + horas : horas;
            minutos = minutos < 10 ? "0" + minutos : minutos;
            segundos = segundos < 10 ? "0" + segundos : segundos;

            // Muestra el cronómetro
            document.getElementById("cronometro").innerHTML = horas + ":" + minutos + ":" + segundos;
            // Comprueba si el tiempo se ha agotado
            if (tiempoRestante <= 0) {
                clearInterval(cronometro);
                // Redirige a otra página cuando el cronómetro se acaba
                document.getElementById("overlay-cronometro").style.display = "none";
            }else{
                 document.getElementById("overlay-cronometro").style.display = "inline";
            }

            tiempoRestante--;
        }, 1000);
    </script>
    <script src="./app/pages/login/js/login.js"></script>
</body>

</html>