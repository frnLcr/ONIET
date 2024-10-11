let currentQuestionIndex = 0;
let questions = [];

const getQuestions = async () => {
    try {
        // Realiza la petición a tu API
        const res = await fetch("https://southamerica-west1-klearty.cloudfunctions.net/funcion-pregunta-2");

        const jsonData = await res.json();
        
        // Parsea el contenido para obtener la pregunta y las respuestas
        const parsedContent = JSON.parse(jsonData.response);

        questions = [{
            pregunta: parsedContent.pregunta,
            opciones: parsedContent.opciones,
            respuesta_correcta: parsedContent.respuesta_correcta
        }];

        // Renderiza la primera pregunta
        renderQuestion(questions[currentQuestionIndex]);
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
const validateAnswer = (selectedButton, selectedOption, correctAnswer) => {
    const optionButtons = document.querySelectorAll('.option');
    const nextQuestionBtn = document.getElementById('nextQuestionBtn');

    // Detener el temporizador cuando se selecciona una respuesta
    clearInterval(timerInterval);

    // Desactivar todas las opciones una vez que se selecciona una
    optionButtons.forEach(button => button.disabled = true);

    // Calcular los puntos basados en el tiempo restante (si la respuesta es correcta)
    let puntosGanados = 0;
    if (selectedOption === correctAnswer) {
        // Si la opción es correcta, la marcamos en verde
        selectedButton.style.backgroundColor = 'green';
        puntosGanados = Math.round((timeLeft / totalTime) * 100); // Suma entre 0 y 100 puntos en base al tiempo
    } else {
        // Si es incorrecta, la marcamos en rojo
        selectedButton.style.backgroundColor = 'red';
        puntosGanados = +0; // No suma

        // Mostrar la correcta en verde
        optionButtons.forEach(button => {
            if (button.textContent === correctAnswer) {
                button.style.backgroundColor = 'green';
            }
        });
    }

    // Enviar los puntos al servidor para actualizar el puntaje
    actualizarPuntaje(puntosGanados);

    // Mostrar el botón "Continuar" una vez que se ha seleccionado una respuesta
    nextQuestionBtn.style.display = 'block';
};

// Función para enviar el puntaje al servidor (PHP)
const actualizarPuntaje = (puntosGanados) => {
    fetch("../page/actualizar_puntaje.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ puntaje: puntosGanados })
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
        location.href ="../../../pages/leerQr/page/leerQr.php";
    }
};

// Temporizador y funciones relacionadas
let timeLeft = 30; // Tiempo inicial de 30 segundos
let timerInterval;
const timerElement = document.getElementById('timer');
const progressCircle = document.getElementById('progress-circle');
const timerNumber = document.getElementById('timer-number');
const timeOutMessage = document.getElementById('time-out-message');

const totalTime = 30;
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

// Llamamos a la función para cargar la primera pregunta
getQuestions();

document.getElementById('nextQuestionBtn').addEventListener('click', nextQuestion);

$(document).ready(function(){
    $("#topp").load("../page/usuarios_top.php");
});


