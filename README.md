# Kahoot Clone - ONIET

Este es un videojuego web desarrollado para los alumnos de la ONIET en la Universidad Blas Pascal, en el cual deben escanear códigos QR distribuidos alrededor del campus en un orden específico para desbloquear preguntas. El objetivo es responder la mayor cantidad de preguntas posibles en un lapso de dos horas. Las preguntas son generadas dinámicamente utilizando la API de ChatGPT. El jugador con más respuestas correctas al final del tiempo, gana un premio.

## Tecnologías Utilizadas

- **HTML**: Estructura del sitio web.
- **CSS**: Estilos y diseño del videojuego.
- **JavaScript**: Lógica de escaneo de QR y manejo de respuestas.
- **PHP**: Gestión de sesiones, autenticación de usuarios y comunicación con la base de datos.
- **API de ChatGPT**: Generación dinámica de preguntas.
- **MySQL**: Base de datos para almacenar información de los usuarios y sus respuestas.

## Funcionamiento del Juego

1. **Distribución de los QR**: Hay 15 códigos QR distribuidos por todo el campus de la Universidad Blas Pascal.
2. **Escaneo en Orden**: Los jugadores deben escanear los QR en el orden correcto utilizando su dispositivo móvil.
3. **Desbloqueo de Preguntas**: Cada QR escaneado desbloquea una nueva pregunta que el jugador debe responder.
4. **Preguntas Generadas por IA**: Las preguntas son obtenidas de la API de ChatGPT y son únicas para cada jugador.
5. **Tiempo Límite**: El juego tiene una duración de **2 horas**. Al finalizar este periodo, se calcula quién respondió más preguntas correctamente.
6. **Ganador**: El jugador con mayor cantidad de respuestas correctas al finalizar el tiempo es declarado ganador y recibe un premio.

## Reglas del Juego

- Los jugadores deben estar autenticados con su usuario y DNI.
- Las preguntas solo son accesibles al escanear el QR correcto en el orden establecido.
- Cada pregunta debe responderse antes de escanear el siguiente código QR.
- Si un jugador no responde correctamente una pregunta, no podrá avanzar al siguiente QR.
- El tiempo límite para el juego es de **2 horas**.

## Instalación

1. Clonar este repositorio:
   ```bash
   git clone https://github.com/usuario/repo
