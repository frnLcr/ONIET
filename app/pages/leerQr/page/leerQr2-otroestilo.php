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
    <link rel="stylesheet" href="../../../components/hamburguesa/menu.css">
    <link rel="stylesheet" href="../css/qr-styles.css">
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body class="bodyQR">
    <header class="cabeza">
        <div class="CP">
            <h3 class="puntos"><?php echo $puntaje; ?></h3>
            <h3 class="puntos"><?php echo $usuario; ?></h3>
        </div>
        <img class="logoheader" src="../../../../public/images/oniet-logo.png" alt="Logo del sitio" class="logo"> 
    </header>

    <main class="leeqr">
        <section class="psita">
            <h1 id="pista"></h1>
        </section>
        <div class="conteQr">
            <section class="QR">
                <div class="container">
                    <video id="preview" style="width: 100%; height: auto;"></video>
                    <button class="botonqr" id="open-camera">Iniciar Escaneo</button>
                    <button class="botonqr" id="close-camara" style="display: none;">Detener Escaneo</button>
                    <div>
                        <p id="error-msg" style="display: none;">Código QR incorrecto. Inténtelo de nuevo.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        const scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false }); // Configurar mirror a false

        document.getElementById('open-camera').addEventListener('click', () => {
            Instascan.Camera.getCameras().then(cameras => {
                if (cameras.length > 0) {
                    // Forzar el uso de la cámara trasera
                    const rearCamera = cameras.find(camera => camera.name.toLowerCase().includes('back')) || cameras[0];
                    scanner.start(rearCamera);
                    document.getElementById('open-camera').style.display = 'none';
                    document.getElementById('close-camara').style.display = 'inline';
                } else {
                    alert('No se encontraron cámaras.');
                }
            }).catch(e => {
                console.error(e);
            });
        });

        document.getElementById('close-camara').addEventListener('click', () => {
            scanner.stop();
            document.getElementById('preview').style.display = 'none';
            document.getElementById('open-camera').style.display = 'inline';
            document.getElementById('close-camara').style.display = 'none';
            document.getElementById('error-msg').style.display = 'none';
        });

        scanner.addListener('scan', content => {
            const ordenEsperado = <?php echo json_encode($orden); ?>;
            if (content === `qr${ordenEsperado}`) {
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
        });

        document.addEventListener("DOMContentLoaded", function() {
            fetch('get_pista.php?orden=' + <?php echo json_encode($orden); ?>)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('pista').innerText = data.pista;
                });
        });
    </script>
</body>
</html>