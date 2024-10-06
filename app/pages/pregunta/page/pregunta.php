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
    <title>Preguntados</title>
    <link rel="stylesheet" href="../../../components/hamburguesa/menu.css">
    <link rel="stylesheet" href="../css/pregunta-styles.css">
    <script defer src="../../../components/ranking/ranking.js"></script>
    <script defer src="../../../components/hamburguesa/menu.js"></script>

</head>
<body>
    <header class="cabeza">
        <div class="CP">
        <h3 class="puntos"><?php echo $puntaje?></h3>
         <h3 class="puntos"><?php echo $usuario; ?></h3> 
        </div>
        <div></div>
          <img class="logoheader" src="../../../../public/images/oniet-logo.png" alt="Logo del sitio" class="logo">
        </div>
    
        <div class="bars__menu">
          <span class="line1__bars-menu"></span>
          <span class="line2__bars-menu"></span>
          <span class="line3__bars-menu"></span>
        </div>
    
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
      <main>
        <div class="question-section">
            <div id="time-out-message" class="time-out-message">Tiempo Agotado!!</div>
            <div id="timer" class="timer">
                <svg width="150" height="150" viewBox="0 0 150 150" class="circle-svg">
                    <circle cx="75" cy="75" r="68" stroke="#d3d3d3" stroke-width="10" fill="none" />
                    <circle id="progress-circle" cx="75" cy="75" r="68" stroke="#4CAF50" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="427.256" stroke-dashoffset="0" />
                </svg>
                <div id="timer-number" class="timer-number">30</div>
            </div>
            <h2 class="question">Cargando pregunta...</h2>
            <div id="options" class="options"></div>
            <div class="next-question-container">
                <button id="nextQuestionBtn" class="next-question-btn">Continuar</button>
            </div>
        </div>
      </main>
       <!-- Modal: Cómo Jugar -->
    <div id="modalComoJugar" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2>Cómo Jugar</h2>
            <p>Instrucciones detalladas sobre cómo jugar el juego.</p>
        </div>
    </div>

    <!-- Modal: Estrategias -->
    <div id="modalEstrategias" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2>Estrategias</h2>
            <p>Consejos y estrategias para mejorar tu puntuación.</p>
        </div>
    </div>

    <!-- Modal: Sobre Nosotros -->
    <div id="modalSobreNosotros" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2>Sobre Nosotros</h2>
            <p>Información acerca del equipo detrás del juego.</p>
        </div>
    </div>

    <!-- Modal: Ranking -->
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
                <button class="logout"  id="negacion">No</button>
            </section>
            
        </div>
    </div>
      <script defer src="../js/pregunta.js"></script>
</body>
</html>