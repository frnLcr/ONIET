<?php
// Iniciar la sesión
session_start();

// Establecer la zona horaria de Buenos Aires, Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Define la hora de inicio y fin en formato 'Y-m-d H:i:s'
$horaInicio = '2024-11-17 09:00:00';  // Fecha y hora de inicio que tú determines
$horaFin = '2025-11-20 13:30:00';     // Fecha y hora de fin que tú determines

// Convertir las horas de inicio y fin a timestamps
$timestampInicio = strtotime($horaInicio);
$timestampFin = strtotime($horaFin);

// Calcular el tiempo restante en segundos
$tiempoActual = time();  // Hora actual en el servidor
$tiempoRestante = $timestampFin - $tiempoActual;


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no ha iniciado sesión, redirigir al login
    header("Location: ../../../../index.php");
    exit;
}

$_SESSION['qr_escaneado'] = false;

// Obtener el nombre del usuario y el puntaje de la sesión
$nombre = $_SESSION['nombre'];
$puntaje = $_SESSION['puntaje'];
$usuario = $_SESSION['usuario'];
$dni = $_SESSION['dni'];
$mail = $_SESSION['mail'];
$preguntaCOR = $_SESSION['preguntaCOR'];
$preguntaINC = $_SESSION['preguntaINC'];

// Inicializar el orden
$orden = isset($_SESSION['orden']) ? $_SESSION['orden'] : 1;

// Número total de pistas (en este caso, 30)
$total_pistas = 30;

// Si el orden es mayor que el total de pistas, reiniciar a 1
if ($orden > $total_pistas) {
    $orden = 1;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leer QR</title>
    <script defer src="../../../components/hamburguesa/menu.js"></script>
    <link rel="stylesheet" href="../../../components/hamburguesa/menu.css">
    <link rel="stylesheet" href="../css/qr-styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body class="bodyQR">
    <header class="cabeza">
        <div class="CP">
            <h3 class="puntos"><?php echo $puntaje; ?></h3>
        </div>
        <div></div>
        <img class="logoheader" src="../../../../public/images/oniet-logo.png" alt="Logo del sitio" class="logo">
        </div>

        <div class="bars__menu">
            <span class="line1__bars-menu"></span>
            <span class="line2__bars-menu"></span>
            <span class="line3__bars-menu"></span>
        </div>

        <!-- Contenedor del menú que se muestra/oculta -->
        <div class="container__menu">
            <nav>
                <ul>
                    <li><a href="#" id="comoJugarBtn">COMO JUGAR</a></li>
                    <li><a href="#" id="estrategiasBtn">ESTRATEGIAS</a></li>
                    <li><a href="#" id="sobreNosotrosBtn">SOBRE NOSOTROS</a></li>
                    <li><a href="#" id="rankingBtn">RANKING</a></li>
                    <li><a href="#" id="perfilBtn">PERFIL</a></li>
                    <li><a href="#" id="Cerrarsesión">CERRAR SESIÓN</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="cronometroo" id="cronometro"></div>
    <main class="leeqr">
    <p class="estilo-msj">PISTA:</p>
        <section class="psita">
            <h1 id=pista></h1>
        </section>
        <div style=" text-align: center; color: #5C0000">
            <p>Escanee el QR para ver la siguiente pregunta!</p>
        </div>
        <div class="conteQr">
            <section class="QR">
                <div class="container">

                    <button class="botonqr" id="open-camera">Iniciar<br>Escaneo</button>
                    <button class="botonqr" id="close-camara" style="display: none;">Detener Escaneo</button>
                    <!-- Botón para cambiar la cámara -->
                    <button class="botonqr" id="toggle-camera" style="display: none;">Cambiar Cámara</button>
                    <div id="preview" style="width: 100%; height: auto; display: none;">
                        <video id="video" style="width: 100%; height: auto;"></video>
                    </div>
                </div>
        </div>
        <div class="diverror">
            <p id="error-msg" style="display: none;">Código QR incorrecto. Inténtelo de nuevo.</p>
        </div>

        </section>
        </div>
    </main>
    <!-- Modales -->
    <div id="modalComoJugar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Cómo Jugar</h2>
            <p class="intro">Instrucciones detalladas sobre cómo jugar el juego.</p>
            <p>Este juego consiste en ir escaneando codigos QR alrededor del campus, y responder preguntas aleatorias relacionadas a los principales temas de la ONIET.
                Al responder correctamente, el jugador obtendrá puntos. De lo contrario, si responde mal, o se acaba el tiempo no sumara nada.
                En ambos escenarios, el jugador avanzara hacia el proximo QR gracias a una pista revelada despues de responder.
                Al final del juego, se mostrara el ranking general y se decidiran los ganadores.</p>
        </div>
    </div>

    <div id="modalEstrategias" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Estrategias</h2>
            <p class="intro">Consejos y estrategias para mejorar tu puntuación.</p>
            <p>1. Leer bien las pistas. Estas son información valiosa para ayudarte a encontrar los codigos.</p>
            <p>2. Responder en tiempo. Si respondes antes, ganaras mas puntos.</p>
            <p>3. Cuidar tu tiempo. No dejes que el tiempo se agote antes de responder.</p>
        </div>
    </div>

    <div id="modalSobreNosotros" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Sobre Nosotros</h2>
            <p class="intro">Información acerca del equipo detrás del juego.</p>
            <p>Alumnos de tercer año de la carrera de Ingenieria Informatica de la Universidad Blas Pascal</p>
            <p>Alumnos:</p>
            <ul class="alumnos">
                <li>Calzada Tomas</li>
                <li>Douglas Octavio</li>
                <li>Escalante Tomas</li>
                <li>Galvan Ignacio</li>
                <li>Gentilli Santiago</li>
                <li>Lucero Franco</li>
            </ul>
            <p>Profesor:</p>
            <ul class="profesor">
                <li>Funes Gustavo</li>
            </ul>
            </p>
        </div>
    </div>

    <div id="modalRanking" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2 class="top">Ranking</h2>
            <p class="intro">Aquí están los mejores jugadores clasificados.</p>
            <h3 class="topp">Top 5</h3>
            <div id="topp"></div>
        </div>
    </div>

    <div id="modalPerfil" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2 class="topp"> MIS DATOS </h2>
            <p>Nombre de usuario: <?php echo $usuario; ?></p>
            <p>Nombre y Apellido: <?php echo $nombre; ?></p>
            <p>Puntaje: <?php echo $puntaje; ?></p>
            <p>Dni: <?php echo $dni; ?></p>
            <p>Mail: <?php echo $mail; ?></p>
            <p>Respuestas Correctas: <?php echo $preguntaCOR; ?></p>
            <p>Respuestas Incorrectas: <?php echo $preguntaINC; ?></p>
        </div>
    </div>

    <div id="modalcerrarsesion" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2 class="top">Cerrar sesión</h2>
            <section class="cerrarsesion">
                <button class="logout" id="confirmacion">Sí</button>
                <button class="logout" id="negacion">No</button>
            </section>
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

            document.getElementById("cronometro").innerHTML = horas + ":" + minutos + ":" + segundos;


            // Comprueba si el tiempo se ha agotado
            if (tiempoRestante <= 0) {
                clearInterval(cronometro);
                // Redirige a otra página cuando el cronómetro se acaba
                window.location.href = "../../gameover/game_over.php";
            }

            tiempoRestante--;
        }, 1000);
    </script>

    <script>
        // Variables globales
        let scannerRunning = false;
        let html5QrCode = null;
        let currentCamera = 'environment'; // Cámara actual ('environment' para trasera, 'user' para frontal)
        let activeCameraId = null;

        // Funciones de éxito y error para el escaneo del QR
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            const ordenEsperado = <?php echo json_encode($orden); ?>;

            // Comparar el código QR escaneado con el esperado
            if (decodedText === `ONIET-qr${ordenEsperado}`) {
                fetch(`success.php?orden=${ordenEsperado}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "../../pregunta/page/pregunta.php";
                        } else {
                            alert("Error al actualizar el orden.");
                        }
                    });
            } else {
                document.getElementById('error-msg').style.display = 'inline';
            }
        };

        const qrCodeErrorCallback = (errorMessage) => {
            // Aquí puedes manejar errores en el proceso de escaneo si es necesario
            // console.error(`QR Error: ${errorMessage}`);
        };

        document.addEventListener("DOMContentLoaded", function() {
            // Pasar el valor de PHP a una variable de JavaScript
            const ordenEsperado = <?php echo json_encode($orden); ?>;

            console.log('Orden esperado:', ordenEsperado);

            fetch('get_pista.php?orden=' + ordenEsperado)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la respuesta del servidor");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                    document.getElementById('pista').innerText = data.pista;
                })
                .catch(error => {
                    console.error("Error al obtener la pista:", error);
                    document.getElementById('pista').innerText = "No se pudo obtener la pista";
                });


            html5QrCode = new Html5Qrcode("preview");

            document.getElementById('open-camera').addEventListener('click', function() {
                Html5Qrcode.getCameras().then(cameras => {
                    if (cameras && cameras.length) {
                        activeCameraId = cameras.find(cam => cam.label.toLowerCase().includes('back'))?.id || cameras[0].id;

                        if (activeCameraId) {
                            setTimeout(() => {
                                html5QrCode.start(
                                    activeCameraId, {
                                        fps: 10,
                                        qrbox: {
                                            width: 300,
                                            height: 300
                                        },
                                        videoConstraints: {
                                            facingMode: currentCamera
                                        }
                                    },
                                    qrCodeSuccessCallback,
                                    qrCodeErrorCallback
                                );
                                scannerRunning = true;

                                // Aplica transformación CSS si la cámara frontal está activa
                                if (currentCamera === 'user') {
                                    document.getElementById('preview').style.transform = 'scaleX(-1)';
                                } else {
                                    document.getElementById('preview').style.transform = 'none';
                                }

                                document.getElementById('preview').style.display = 'block';
                                document.getElementById('open-camera').style.display = 'none';
                                document.getElementById('close-camara').style.display = 'inline';
                                document.getElementById('toggle-camera').style.display = 'inline';
                            }, 1000);
                        } else {
                            console.error("No se pudo encontrar la cámara trasera.");
                        }
                    } else {
                        console.error("No hay cámaras disponibles.");
                    }
                }).catch(err => {
                    console.error(`Error al obtener cámaras: ${err}`);
                });
            });

            document.getElementById('close-camara').addEventListener('click', function() {
                if (scannerRunning) {
                    html5QrCode.stop().then(() => {
                        scannerRunning = false;
                        document.getElementById('preview').style.display = 'none';
                        document.getElementById('open-camera').style.display = 'inline';
                        document.getElementById('close-camara').style.display = 'none';
                        document.getElementById('toggle-camera').style.display = 'none';
                        document.getElementById('error-msg').style.display = 'none';
                    }).catch(err => {
                        console.error(`Error al detener el escaneo: ${err}`);
                    });
                } else {
                    console.log("El escáner ya está detenido o no se ha iniciado.");
                }
            });
        });

        // Botón para cambiar de cámara
        document.getElementById('toggle-camera').addEventListener('click', function() {
            currentCamera = currentCamera === 'environment' ? 'user' : 'environment';

            // Reiniciar el escáner con la nueva cámara
            if (scannerRunning) {
                html5QrCode.stop().then(() => {
                    html5QrCode.start(
                        activeCameraId, {
                            fps: 10,
                            qrbox: {
                                width: 300,
                                height: 300
                            },
                            videoConstraints: {
                                facingMode: currentCamera
                            }
                        },
                        qrCodeSuccessCallback,
                        qrCodeErrorCallback
                    ).then(() => {
                        // Aplica o quita la transformación CSS si la cámara frontal está activa
                        if (currentCamera === 'user') {
                            document.getElementById('preview').style.transform = 'scaleX(-1)';
                        } else {
                            document.getElementById('preview').style.transform = 'none';
                        }
                    });
                }).catch(err => {
                    console.error(`Error al reiniciar el escaneo: ${err}`);
                });
            }
        });
        $(document).ready(function() {
            $("#topp").load("../../pregunta/page/usuarios_top.php");
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Botón de "Sí" en el modal de cerrar sesión
            document.getElementById('confirmacion').addEventListener('click', function() {
                // Redirigir al archivo logout.php para cerrar la sesión
                window.location.href = '../../../components/logout/logout.php';
            });

            // Botón de "No" en el modal de cerrar sesión
            document.getElementById('negacion').addEventListener('click', function() {
                // Cerrar el modal y regresar al menú hamburguesa
                document.getElementById('modalcerrarsesion').style.display = 'none';
            });

            // Código para abrir y cerrar modales (ya existente en tu código)
            const modals = document.querySelectorAll('.modal');
            const closeButtons = document.querySelectorAll('.close');

            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    modals.forEach(modal => {
                        modal.style.display = 'none';
                    });
                });
            });

            // Abre el modal de cerrar sesión
            document.getElementById('Cerrarsesión').addEventListener('click', function() {
                document.getElementById('modalcerrarsesion').style.display = 'block';
            });
        });
    </script>
</body>

</html>