let currentQuestionIndex = 0;
let questions = [];

// Límite de intentos para evitar bucles infinitos
const MAX_ATTEMPTS = 3;
let attempts = 0;

// Función para mezclar las opciones (algoritmo de Fisher-Yates)
const shuffleArray = (array) => {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]]; // Intercambiar elementos
    }
    return array;
};

// Máximo de intentos si la pregunta es inválida
const guardarPreguntaEnBaseDeDatos = async (pregunta) => {
    try {
        const response = await fetch("../page/guardar_pregunta.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ pregunta })
        });

        const data = await response.json();

        if (data.success) {
            console.log(`Pregunta guardada correctamente en la columna ${data.numeroPregunta}.`);
        } else {
            console.error("Error al guardar la pregunta:", data.error);
        }
    } catch (error) {
        console.error("Error al guardar la pregunta:", error);
    }
};

const getQuestions = async () => {
    try {
        const res = await fetch("https://southamerica-west1-klearty.cloudfunctions.net/funcion-pregunta-2");
        const jsonData = await res.json();

        try {
            const parsedContent = JSON.parse(jsonData.response);

            if (!parsedContent.pregunta || !Array.isArray(parsedContent.opciones) || !parsedContent.respuesta_correcta) {
                throw new Error("Formato de JSON inválido.");
            }

            const hasCorrectAnswer = parsedContent.opciones.includes(parsedContent.respuesta_correcta);

            if (!hasCorrectAnswer) {
                throw new Error("La respuesta correcta no está entre las opciones.");
            }

            // Guardar la respuesta correcta antes de mezclar
            let correctAnswer = parsedContent.respuesta_correcta;

            // Mezclar las opciones
            let shuffledOptions = shuffleArray([...parsedContent.opciones]);

            // Renderizar la pregunta con opciones mezcladas
            questions = [{
                pregunta: parsedContent.pregunta,
                opciones: shuffledOptions,
                respuesta_correcta: correctAnswer
            }];

            renderQuestion(questions[0]);
            await guardarPreguntaEnBaseDeDatos(parsedContent.pregunta);
            attempts = 0;

        } catch (parseError) {
            console.warn("Pregunta malformada o respuesta incorrecta. Solicitando una nueva pregunta...");
            attempts += 1;

            if (attempts < MAX_ATTEMPTS) {
                await getQuestions();
            } else {
                console.error("Se ha alcanzado el máximo de intentos para obtener una pregunta válida.");
            }
        }
    } catch (error) {
        console.error("Error al obtener la pregunta:", error);
    }
};

// Función para renderizar la pregunta y las opciones
const renderQuestion = (question) => {
    const questionElement = document.querySelector('.question');
    const optionsElement = document.getElementById('options');
    const nextQuestionBtn = document.getElementById('nextQuestionBtn');

    // Ocultar el botón "Continuar" al renderizar la pregunta
    nextQuestionBtn.style.display = 'none';

    // Mostrar la pregunta
    questionElement.textContent = question.pregunta;

    // Limpiar las opciones anteriores
    optionsElement.innerHTML = '';

    // Mostrar las opciones como botones
    question.opciones.forEach((opcion) => {
        const button = document.createElement('button');
        button.textContent = opcion;
        button.classList.add('option');

        // Evento al seleccionar una opción
        button.addEventListener('click', () => validateAnswer(button, opcion, question.respuesta_correcta));

        optionsElement.appendChild(button);
    });
    // Iniciar el temporizador una vez que se ha renderizado la pregunta
    startTimer();
};

// Función para validar la respuesta

const guardarRespuestasUsuario = async (respuesta) => {
    try {
        const response = await fetch("../page/guardar_respuesta_usuario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                respuesta
            }) // Nota: No se envía usuarioId
        });

        const data = await response.json();

        if (data.success) {
            console.log(`Respuesta guardada correctamente para la pregunta.`);
        } else {
            console.error("Error al guardar la respuesta:", data.error);
        }
    } catch (error) {
        console.error("Error al guardar la respuesta:", error);
    }
};

// Actualizar validateAnswer para llamar a guardarRespuestasUsuario
const validateAnswer = (selectedButton, selectedOption, correctAnswer) => {
    const optionButtons = document.querySelectorAll('.option');
    const nextQuestionBtn = document.getElementById('nextQuestionBtn');

    // Detener el temporizador cuando se selecciona una respuesta
    clearInterval(timerInterval);

    // Desactivar todas las opciones una vez que se selecciona una
    optionButtons.forEach(button => button.disabled = true);
    let puntosGanados = 0;
    let isCorrect = false;

    // Determinar si la respuesta es correcta
    if (selectedOption === correctAnswer) {
        selectedButton.style.backgroundColor = 'green';
        puntosGanados = Math.round((timeLeft / totalTime) * 100); // Suma entre 0 y 100 puntos en base al tiempo
        isCorrect = true;
    } else {
        selectedButton.style.backgroundColor = 'red';
        puntosGanados = +0; // No se suman puntos si la respuesta es incorrecta
        optionButtons.forEach(button => {
            if (button.textContent === correctAnswer) {
                button.style.backgroundColor = 'green';
            }
        });
    }

    // Guardar la respuesta del usuario
    guardarRespuestasUsuario(selectedOption);

    actualizarPuntaje(puntosGanados, isCorrect);

    // Mostrar el botón "Continuar"
    nextQuestionBtn.style.display = 'block';
};


// Función para enviar el puntaje al servidor (PHP)
const actualizarPuntaje = (puntosGanados, isCorrect) => {
    fetch("../page/actualizar_puntaje.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ puntaje: puntosGanados, isCorrect: isCorrect })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Puntaje actualizado:", puntosGanados);
            } else {
                console.error("Error al actualizar el puntaje:", data.error);
            }
        })
        .catch(error => console.error("Error en la petición:", error));
};

// Función para pasar a la siguiente pregunta
const nextQuestion = () => {
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        renderQuestion(questions[currentQuestionIndex]);
    } else {
        location.href = "../../../pages/leerQr/page/leerQr.php";
    }
};

// Temporizador y funciones relacionadas
let timeLeft = 15; // Tiempo inicial de 15 segundos
let timerInterval;
const timerElement = document.getElementById('timer');
const progressCircle = document.getElementById('progress-circle');
const timerNumber = document.getElementById('timer-number');
const timeOutMessage = document.getElementById('time-out-message');

const totalTime = 15;
const radius = 68;
const circumference = 2 * Math.PI * radius;
progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
progressCircle.style.strokeDashoffset = 0;

const hideTimeOutMessage = () => {
    timeOutMessage.style.display = 'none';
};

const showTimeOutMessage = () => {
    timeOutMessage.style.display = 'block';
};

// Función para interpolar entre dos colores (verde y rojo)
const interpolateColor = (startColor, endColor, factor) => {
    const result = startColor.slice();
    for (let i = 0; i < 3; i++) {
        result[i] = Math.round(result[i] + factor * (endColor[i] - startColor[i]));
    }
    return `rgb(${result.join(',')})`;
};

const startColor = [0, 255, 0]; // Verde
const endColor = [255, 0, 0];   // Rojo

// Función para actualizar el temporizador
const updateTimer = () => {
    timerNumber.textContent = Math.ceil(timeLeft);

    const offset = circumference - (timeLeft / totalTime) * circumference;
    progressCircle.style.strokeDashoffset = offset;

    const factor = 1 - (timeLeft / totalTime);
    const currentColor = interpolateColor(startColor, endColor, factor);
    progressCircle.style.stroke = currentColor;

    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        showTimeOutMessage();
        timerNumber.textContent = '0';

        const optionButtons = document.querySelectorAll('.option');
        optionButtons.forEach(button => button.disabled = true);

        optionButtons.forEach(button => {
            if (button.textContent === questions[currentQuestionIndex].respuesta_correcta) {
                button.style.backgroundColor = 'green';
            } else {
                button.style.backgroundColor = 'red';
            }
        });

        const nextQuestionBtn = document.getElementById('nextQuestionBtn');
        nextQuestionBtn.style.display = 'block';

        return;
    }
    timeLeft -= 0.1;
};

// Iniciar el temporizador
const startTimer = () => {
    timeLeft = totalTime;
    hideTimeOutMessage();
    updateTimer();
    timerInterval = setInterval(updateTimer, 100);
};

// Llamar a la función para iniciar la obtención de preguntas
getQuestions();

document.getElementById('nextQuestionBtn').addEventListener('click', nextQuestion);

$(document).ready(function () {
    $("#topp").load("../page/usuarios_top.php");
});

