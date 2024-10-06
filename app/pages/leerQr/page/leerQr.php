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
                <video class="video" id="video" autoplay playsinline muted></video>
        </div>
                <!-- Botón para iniciar el escaneo del QR -->
                <button class="botonqr" id="btn-start-scan">Iniciar Escaneo</button>
        <!-- Botón para detener el escaneo -->
                <button class="botonqr" id="btn-stop-scan" style="display: none;">Detener Escaneo</button>
        <!-- Mensaje de error si el QR es incorrecto -->
                <p id="error-msg">Código QR incorrecto. Inténtelo de nuevo.</p>
    </div>
                <canvas class="video2" id="canvas"></canvas>
    </section>
    </div>
    </main>
    <!-- Modales -->
    <div id="modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Cómo Jugar</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
      </div>
    
      <div id="modalEstrategias" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Estrategias</h2>
          <p>Las mejores estrategias para mejorar tu juego. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
      </div>
    
      <div id="modalSobreNosotros" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Sobre Nosotros</h2>
          <p>Conoce más sobre nuestro equipo y nuestra misión. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
      </div>
    
      <div id="modalRanking" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="top">Ranking</h2>
            <p class="top">Aquí están los mejores jugadores clasificados. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <section class="contorno">
                <h3 class="top">Top 10</h3>
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
    <script src="../js/apiqr.js"></script>
    <script src="../js/qr.js"></script>
</body>
</html>