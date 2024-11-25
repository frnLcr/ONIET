<?php
session_start();

// Restablece las variables de sesión
unset($_SESSION['tiempo_inicio']);
unset($_SESSION['contador_preguntas']);
unset($_SESSION['puntaje']);

// Puedes agregar aquí otras variables de sesión que necesites reiniciar
session_destroy(); // Opcional: destruye toda la sesión si es necesario

// No es necesario redireccionar aquí ya que `resetGame()` en JavaScript redirige al index
?>
