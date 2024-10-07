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
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

</head> 
<body class="bodyQR">
    <header class="cabeza">
        <div class="CP">
        <h3 class="puntos"><?php echo $puntaje; ?></h3>
        <h3 class="puntos"><?php echo $usuario; ?></h3> <!-- Muestra el nombre del usuario -->
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
        <h1>PISTA</h1>
    </section>
    <div class="conteQr">
    <section class="QR">
    <div class="container">
        <div class="contvideo">
        <video id="preview" class="video"></video>
        </div>

                <button class="botonqr" id="open-camera">Iniciar Escaneo</button>

                <button class="botonqr" id="close-camara" style="display: none;">Detener Escaneo</button>
                <div>
                <p id="error-msg" style="display: none;">Código QR incorrecto. Inténtelo de nuevo.</p>
                </div>
    </div>

    </section>
    </div>
    </main>
    <!-- Modales -->
    <div id="modal" class="modal">
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
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="top">Ranking</h2>
            <p class="top">Aquí están los mejores jugadores clasificados.</p>
            <section class="contorno">
                <h3 class="top">Top 5</h3>
                <ul id="rankingList">
                    <!-- El contenido del ranking se llenará aquí -->
                </ul>
            </section>
        </div>
    </div>
    <div id="modalcerrarsesion" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2 class="top">Cerrar sesión</h2>
            <section class="cerrarsesion">
                <button class="logout" id="confirmacion">Si</button>
                <button class="logout"  id="negacion">No</button>
            </section>
            
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    // Pasar el valor de PHP a una variable de JavaScript
    const ordenEsperado = <?php echo json_encode($orden); ?>;

    fetch('get_pista.php?orden=' + ordenEsperado)
        .then(response => response.json())
        .then(data => {
            document.getElementById('pista').innerText = data.pista;
        });

    const scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function(content) {
        if (content === `qr${ordenEsperado}`) {
            alert("Escaneado correctamente!");
            fetch(`success.php?orden=${ordenEsperado}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert("Error al actualizar el orden.");
                    }
                });
        } else {
            document.getElementById('error-msg').style.display = 'inline';
        }
    });
    document.getElementById('close-camara').addEventListener('click', function() {
        scanner.stop();
        document.getElementById('preview').style.display = 'none';
        document.getElementById('open-camera').style.display = 'inline';
        document.getElementById('close-camara').style.display = 'none';
        document.getElementById('error-msg').style.display = 'none';
    });

    document.getElementById('open-camera').addEventListener('click', function() {
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
                document.getElementById('preview').style.display = 'inline';
                document.getElementById('open-camera').style.display = 'none';
                document.getElementById('close-camara').style.display = 'inline';

            } else {
                console.error('No hay cámaras disponibles.');
            }
        }).catch(function(e) {
            console.error(e);
        });
    });
});
    </script>
</body>
</html>