<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no ha iniciado sesión, redirigir al login
    header("Location: ../../../../index.html");
    exit;
}

// Obtener el nombre del usuario y el puntaje de la sesión
$nombre = $_SESSION['nombre'];
$puntaje = $_SESSION['puntaje'];
$usuario = $_SESSION['usuario'];

// Inicializar el orden
$orden = isset($_SESSION['orden']) ? $_SESSION['orden'] : 1;



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leer QR</title>
    <script defer src="../../../components/hamburguesa/menu.js"></script>
    <script defer src="../../../components/ranking/ranking.js"></script>
    <link rel="stylesheet" href="../../../components/hamburguesa/menu.css">
    <link rel="stylesheet" href="../css/qr-styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                    <li><a href="#" id="Cerrarsesión">CERRAR SESIÓN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="leeqr">
    <section class="psita">
            <p class="estilo-msj">PISTA:</p>
            <h1 id=pista></h1>
        </section>
        <div style=" text-align: center; color: #5C0000">
            <p >Escanee el QR para ver la siguiente pregunta!</p>
        </div>
        <div class="conteQr">
            <section class="QR">
                <div class="container">
                  
                    <button class="botonqr" id="open-camera">Iniciar Escaneo</button>
                    <button class="botonqr" id="close-camara" style="display: none;">Detener Escaneo</button>
                    <div id="preview" style="width: 100%; height: auto; display: none;">
                        <video id="video" style="width: 100%; height: auto;"></video>
                    </div>
                    <div>
                        <p id="error-msg" style="display: none;">Código QR incorrecto. Inténtelo de nuevo.</p>
                    </div>
                </div>

            </section>
        </div>
    </main>
    <!-- Modales -->
    <div id="modalComoJugar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Cómo Jugar</h2>
            <p>Instrucciones detalladas sobre cómo jugar el juego.</p>
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
            <p>Consejos y estrategias para mejorar tu puntuación.</p>
            <p>1. Leer bien las pistas. Estas son información valiosa para ayudarte a encontrar los codigos.</p>
            <p>2. Responder en tiempo. Si respondes antes, ganaras mas puntos.</p>
            <p>3. Cuidar tu tiempo. No dejes que el tiempo se agote antes de responder.</p>
        </div>
    </div>

    <div id="modalSobreNosotros" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Sobre Nosotros</h2>
            <p>Información acerca del equipo detrás del juego.</p>
            <p>Alumnos de tercer año de la carrera de Ingenieria Informatica de la Universidad Blas Pascal</p>
            <p>Alumnos:
            <ul class="alumnos">
                <li>Calzada Tomas</li>
                <li>Douglas Octavio</li>
                <li>Escalante Tomas</li>
                <li>Galvan Ignacio</li>
                <li>Gentilli Santiago</li>
                <li>Lucero Franco</li>
            </ul>
            Profesor:
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
            <p class="top">Aquí están los mejores jugadores clasificados.</p>
            <h3 class="topp">Top 5</h3>
            <section id="rank" class="contorno"> <!-- Aquí se llenará con los datos del ranking -->
            </section>
        </div>
    </div>

    <div id="modalcerrarsesion" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2 class="top">Cerrar sesión</h2>
            <section class="cerrarsesion">
                <button class="logout" id="confirmacion">Si</button>
                <button class="logout" id="negacion">No</button>
            </section>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Pasar el valor de PHP a una variable de JavaScript
            const ordenEsperado = <?php echo json_encode($orden); ?>;

            fetch('get_pista.php?orden=' + ordenEsperado)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la respuesta del servidor");
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('pista').innerText = data.pista;
                })
                .catch(error => {
                    console.error("Error al obtener la pista:", error);
                    document.getElementById('pista').innerText = "No se pudo obtener la pista";
                });

            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // Comparar el código QR escaneado con el esperado
                if (decodedText === `qr${ordenEsperado}`) {
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
                console.error(`QR Error: ${errorMessage}`);
            };

            const html5QrCode = new Html5Qrcode("preview");

            let scannerRunning = false; // Variable para rastrear el estado del escáner

            document.getElementById('open-camera').addEventListener('click', function() {
                Html5Qrcode.getCameras().then(cameras => {
                    if (cameras && cameras.length) {
                        const camera = cameras.find(cam => cam.label.toLowerCase().includes('back')) || cameras[0];

                        if (camera) {
                            setTimeout(() => {
                                html5QrCode.start(
                                    camera.id, {
                                        fps: 10,
                                        qrbox: {
                                            width: 300,
                                            height: 300
                                        },
                                        videoConstraints: {
                                            facingMode: "environment"
                                        }
                                    },
                                    qrCodeSuccessCallback,
                                    qrCodeErrorCallback
                                );
                                scannerRunning = true; // Cambia el estado a corriendo
                                document.getElementById('preview').style.display = 'block';
                                document.getElementById('open-camera').style.display = 'none';
                                document.getElementById('close-camara').style.display = 'inline';
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
                if (scannerRunning) { // Solo intenta detener si el escáner está corriendo
                    html5QrCode.stop().then(() => {
                        scannerRunning = false; // Cambia el estado a detenido
                        document.getElementById('preview').style.display = 'none';
                        document.getElementById('open-camera').style.display = 'inline';
                        document.getElementById('close-camara').style.display = 'none';
                        document.getElementById('error-msg').style.display = 'none';
                    }).catch(err => {
                        console.error(`Error al detener el escaneo: ${err}`);
                    });
                } else {
                    console.log("El escáner ya está detenido o no se ha iniciado.");
                }
            });



            document.getElementById('close-camara').addEventListener('click', function() {
                html5QrCode.stop().then(() => {
                    document.getElementById('preview').style.display = 'none';
                    document.getElementById('open-camera').style.display = 'inline';
                    document.getElementById('close-camara').style.display = 'none';
                    document.getElementById('error-msg').style.display = 'none';
                }).catch(err => {
                    console.error(`Error al detener el escaneo: ${err}`);
                });
            });
        });
    </script>

</body>

</html>