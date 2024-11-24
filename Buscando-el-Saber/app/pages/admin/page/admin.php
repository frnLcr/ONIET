<?php
session_start();
if (!isset($_SESSION['dni']) || $_SESSION['dni'] !== '2025') {
    header('Location: ../leerQr/page/leerQr.php'); // Redirige si no es administrador
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Comenzar Juego</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
    </header>
    <main>
        <form id="game-form">
            <label for="duration">DURACION DEL JUEGO (MINUTOS):</label>
            <input type="number" id="duration" name="duration" min="1" required>
            <button type="submit">COMENZAR JUEGO</button>
        </form>

        <form id="stop-game-form">
            <button type="submit" class="stop-button">DETENER JUEGO</button>
        </form>

        <form id="reset-game-form">
            <button type="submit" class="stop-button">REINICIAR JUEGO</button>
        </form>

        <div id="message" class="message" style="display: none;"></div>
    </main>

    <script>
        document.getElementById('game-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const duration = document.getElementById('duration').value;

            fetch('start_game.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ duration })
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.textContent = `¡Juego iniciado! Finalizará en ${data.end_time}`;
                    messageDiv.className = 'message success';
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'message error';
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('stop-game-form').addEventListener('submit', function(event) {
            event.preventDefault();
            fetch('stop_game.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.textContent = "El juego ha sido detenido.";
                    messageDiv.className = 'message error';
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'message error';
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('reset-game-form').addEventListener('submit', function(event) {
            event.preventDefault();
            fetch('reset_game.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.textContent = "El juego ha sido reiniciado. Todos los datos se han borrado.";
                    messageDiv.className = 'message success';
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'message error';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
