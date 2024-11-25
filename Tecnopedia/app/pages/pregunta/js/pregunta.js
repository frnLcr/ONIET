let currentQuestionIndex = 0;
let questions = [];
const MAX_ATTEMPTS = 3;
let attempts = 0;
const MAX_QUESTIONS = 30;
let questionCount = 0;

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
                console.error("Máximo de intentos para obtener una pregunta válida alcanzado.");
            }
        }

    } catch (error) {
        console.error("Error al obtener la pregunta:", error);
    }
};

const renderQuestion = (question) => {
    const questionElement = document.querySelector('.question');
    const optionsElement = document.getElementById('options');
    const nextQuestionBtn = document.getElementById('nextQuestionBtn');

    nextQuestionBtn.style.display = 'none';
    questionElement.textContent = question.pregunta;
    optionsElement.innerHTML = '';

    question.opciones.forEach((opcion) => {
        const button = document.createElement('button');
        button.textContent = opcion;
        button.classList.add('option');
        button.addEventListener('click', () => validateAnswer(button, opcion, question.respuesta_correcta));
        optionsElement.appendChild(button);
    });

    startTimer();
};

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

// Modificada la función nextQuestion para que redirija solo al presionar el botón de continuar
function nextQuestion() {
    questionCount += 1;
    if (questionCount < MAX_QUESTIONS) {
        window.location.href = "procesa_respuesta.php"; // Redirige al procesador de respuestas
    } else {
        window.location.href = "../../gameover/game_over.php"; // Redirige a la página de gameover si alcanza el límite de preguntas
    }
}

let timeLeft = 15;
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

const interpolateColor = (startColor, endColor, factor) => {
    const result = startColor.slice();
    for (let i = 0; i < 3; i++) {
        result[i] = Math.round(result[i] + factor * (endColor[i] - startColor[i]));
    }
    return `rgb(${result.join(',')})`;
};

const startColor = [0, 255, 0];
const endColor = [255, 0, 0];

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

const startTimer = () => {
    timeLeft = totalTime;
    hideTimeOutMessage();
    updateTimer();
    timerInterval = setInterval(updateTimer, 100);
};

getQuestions();

// Escucha para el botón de continuar
document.getElementById('nextQuestionBtn').addEventListener('click', nextQuestion);

$(document).ready(function(){
    $("#topp").load("../page/usuarios_top.php");
});
