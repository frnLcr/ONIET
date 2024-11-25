<?php
session_start();


// Incluir el archivo confi.php
require_once 'config.php';



// Establecer la zona horaria de Buenos Aires, Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Validar que los valores obtenidos de confi.php estén disponibles
if (!isset($inicio, $fin, $ultimoID)) {
    die("Error al obtener los valores de inicio, fin o el último ID desde confi.php.");
}

// Validar que los valores obtenidos de config.php estén disponibles y sean válidos
if (!isset($inicio, $fin, $ultimoID) || $inicio == 0 || $fin == 0 || $ultimoID == 0) {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Juego</title>
        <style>
            body {
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: black;
                color: white;
                font-family: Arial, sans-serif;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>YA VA A COMENZAR EL JUEGO</h1>
    </body>
    </html>';
    exit;
}

// Convertir los valores de inicio y fin a formato 'Y-m-d H:i:s'
$horaInicio = date('Y-m-d H:i:s', strtotime($inicio));
$horaFin = date('Y-m-d H:i:s', strtotime($fin));

// Convertir las horas de inicio y fin a timestamps
$timestampInicio = strtotime($horaInicio);
$timestampFin = strtotime($horaFin);

// Calcular el tiempo restante en segundos
$tiempoActual = time();  // Hora actual en el servidor
$tiempoRestante = $timestampFin - $tiempoActual;

include '../../../../connection.php';

// Tiempo de inicio para el usuario al iniciar la sesión
if (!isset($_SESSION['tiempo_inicio'])) {
    $_SESSION['tiempo_inicio'] = time();
}

if (!isset($_SESSION['contador_preguntas'])) {
    $_SESSION['contador_preguntas'] = 0;
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no ha iniciado sesión, redirigir al login
    header("Location: ../../../../index.php");
    exit;
}

// Limitar preguntas
define('LIMITE_PREGUNTAS', 30);

// Verificar si llegó al límite y redirigir
if ($_SESSION['contador_preguntas'] >= LIMITE_PREGUNTAS) {
    header("Location: ../../gameover/game_over.php");
    exit();
}


$usuario = $_SESSION['usuario'];
$puntaje = $_SESSION['puntaje'];
$nombre = $_SESSION['nombre'];
$dni = $_SESSION['dni'];
$mail = $_SESSION['mail'];
$preguntaCOR = $_SESSION['preguntaCOR'];
$preguntaINC = $_SESSION['preguntaINC'];

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$usuario = $_SESSION['usuario'];

// Consulta para obtener el puntaje actual del usuario
$sql = "SELECT puntaje FROM USUARIOS WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$puntaje = $row['puntaje'] ?? 0; // Si no hay puntaje, asigna 0

$stmt->close();
$conn->close();

if ($result->num_rows === 0) {
    echo "El juego ha sido detenido. Por favor, contacte al administrador.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntados</title>
    <link rel="stylesheet" href="../../../components/hamburguesa/menu.css">
    <link rel="stylesheet" href="../css/pregunta-styles.css">
    <script defer src="../../../components/hamburguesa/menu.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>
    <header class="cabeza">
        <div class="CP">
            <h3 class="puntos"><?php echo $puntaje ?></h3>
            <p class="contador_preguntas">Pregunta <?php echo $_SESSION['contador_preguntas'] + 1; ?> de <?php echo LIMITE_PREGUNTAS; ?></p>
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
                    <li><a href="#" id="perfilBtn">PERFIL</a></li>
                    <li><a href="#" id="Cerrarsesión">CERRAR SESIÓN</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="cronometroo" id="cronometro">
    </div>
    <div class="cronometrooo" id="cronometrooo">
    </div>
    <main>
        <div class="msj-pregunta"> Si la pregunta no carga correctamente, reinicie la pagina porfavor.</div>
        <div class="question-section">
            <div id="time-out-message" class="time-out-message">Tiempo Agotado!!</div>
            <div id="timer" class="timer">
                <svg width="150" height="150" viewBox="0 0 150 150" class="circle-svg">
                    <circle cx="75" cy="75" r="68" stroke="#d3d3d3" stroke-width="10" fill="none" />
                    <circle id="progress-circle" cx="75" cy="75" r="68" stroke="#4CAF50" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="427.256" stroke-dashoffset="0" />
                </svg>
                <div id="timer-number" class="timer-number">15</div>
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
            <p class="intro">Instrucciones detalladas sobre cómo jugar el juego.</p>
            <p>Este juego consiste en responder preguntas aleatorias relacionadas a los principales temas de ....
                Al responder correctamente, el jugador obtendrá puntos. De lo contrario, si responde mal, o se acaba el tiempo no sumara nada.
                En ambos escenarios, le aparecera la proxima pregunta a responder.
                Al final del juego, se mostrara el ranking general y se decidiran los ganadores.</p>
        </div>
    </div>

    <!-- Modal: Estrategias -->
    <div id="modalEstrategias" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2>Estrategias</h2>
            <p class="intro">Consejos y estrategias para mejorar tu puntuación.</p>
            <p>1. Leer bien las preguntas. No responder por responder.</p>
            <p>2. Responder en tiempo. Si respondes antes, ganaras mas puntos.</p>
            <p>3. Cuidar tu tiempo.</p>
        </div>
    </div>

    <!-- Modal: Sobre Nosotros -->
    <div id="modalSobreNosotros" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2>Sobre Nosotros</h2>
            <p class="intro">Información acerca del equipo detrás del juego.</p>
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

    <!-- Modal: Ranking -->
    <div id="modalRanking" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2 class="top">Ranking</h2>
            <p class="intro">Aquí están los mejores jugadores clasificados.</p>
            <h3 class="topp">Top 5</h3>
            <div id="topp">
            </div>
        </div>
    </div>

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

    <script defer src="../js/pregunta.js"></script>

    <script>
        // Cronómetro general (tiempo total desde el inicio de sesión)
        let tiempoGeneral = <?php echo time() - $_SESSION['tiempo_inicio']; ?>;
        let cronometroGeneral = setInterval(function() {
            let horasGeneral = Math.floor(tiempoGeneral / 3600);
            let minutosGeneral = Math.floor((tiempoGeneral % 3600) / 60);
            let segundosGeneral = tiempoGeneral % 60;

            horasGeneral = horasGeneral < 10 ? "0" + horasGeneral : horasGeneral;
            minutosGeneral = minutosGeneral < 10 ? "0" + minutosGeneral : minutosGeneral;
            segundosGeneral = segundosGeneral < 10 ? "0" + segundosGeneral : segundosGeneral;

            document.getElementById("cronometro").innerHTML = "Tiempo total: " + horasGeneral + ":" + minutosGeneral + ":" + segundosGeneral;
            tiempoGeneral++; // Incrementa el tiempo cada segundo
        }, 1000);
        // Cronómetro de la pregunta
        //let tiempoRestante = 30 * 60; // 30 minutos como ejemplo, modificar según sea necesario
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
            document.getElementById("cronometrooo").innerHTML = "Fin del juego en:" + horas + ":" + minutos + ":" + segundos;

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