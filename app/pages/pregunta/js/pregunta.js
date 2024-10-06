let currentQuestionIndex = 0;
let questions = [];

const getQuestions = async () => {
    try {
        // Realiza la petición a tu API
        const res = await fetch("http://20.206.202.123:8000/generate-question", {referrerPolicy: "unsafe-url" });
        const jsonData = await res.json();
        
        // Parsea el contenido para obtener la pregunta y las respuestas
        const parsedContent = JSON.parse(jsonData.content);

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

    if (selectedOption === correctAnswer) {
        // Si la opción es correcta, la marcamos en verde
        selectedButton.style.backgroundColor = 'green';
    } else {
        // Si es incorrecta, la marcamos en rojo
        selectedButton.style.backgroundColor = 'red';

        // También mostramos la correcta en verde
        optionButtons.forEach(button => {
            if (button.textContent === correctAnswer) {
                button.style.backgroundColor = 'green';
            }
        });
    }

    // Mostrar el botón "Continuar" una vez que se ha seleccionado una respuesta
    nextQuestionBtn.style.display = 'block';
};

// Función para pasar a la siguiente pregunta (si tienes más de una)
const nextQuestion = () => {
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        renderQuestion(questions[currentQuestionIndex]);
    } else {
        location.href ="../../../pages/leerQr/page/leerQr.php"
    }
};

// Temporizador y funciones relacionadas
let timeLeft = 30; // Tiempo inicial de 30 segundos
let timerInterval;
const timerElement = document.getElementById('timer');
const progressCircle = document.getElementById('progress-circle');
const timerNumber = document.getElementById('timer-number');
const timeOutMessage = document.getElementById('time-out-message'); // Seleccionamos el mensaje de "Tiempo Agotado"

const totalTime = 30; // Tiempo total para el temporizador
const radius = 68; // Radio del círculo
const circumference = 2 * Math.PI * radius; // Circunferencia del círculo
progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
progressCircle.style.strokeDashoffset = 0;

// Función para ocultar el mensaje de tiempo agotado
const hideTimeOutMessage = () => {
    timeOutMessage.style.display = 'none';
};

// Función para mostrar el mensaje de tiempo agotado
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

// Colores inicial (verde) y final (rojo) en formato RGB
const startColor = [0, 255, 0]; // Verde
const endColor = [255, 0, 0];   // Rojo

// Función para actualizar el temporizador
const updateTimer = () => {
    // Actualizamos el número en el centro del círculo
    timerNumber.textContent = Math.ceil(timeLeft);

    // Calculamos el progreso restante en porcentaje
    const offset = circumference - (timeLeft / totalTime) * circumference;
    progressCircle.style.strokeDashoffset = offset;

    // Calcula el factor en base al tiempo restante
    const factor = 1 - (timeLeft / totalTime); // Va de 0 (todo verde) a 1 (todo rojo)

    // Interpola entre verde y rojo
    const currentColor = interpolateColor(startColor, endColor, factor);
    progressCircle.style.stroke = currentColor;

    // Detenemos el temporizador cuando el tiempo llegue a 0
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        showTimeOutMessage(); // Mostrar el mensaje de tiempo agotado
        timerNumber.textContent = '0'; // Asegurarnos de que el número muestre 0

        // Desactivar todas las opciones
        const optionButtons = document.querySelectorAll('.option');
        optionButtons.forEach(button => button.disabled = true);

        // Colorear las respuestas correctamente
        optionButtons.forEach(button => {
            if (button.textContent === questions[currentQuestionIndex].respuesta_correcta) {
                // Respuesta correcta en verde
                button.style.backgroundColor = 'green';
            } else {
                // Respuesta incorrecta en rojo
                button.style.backgroundColor = 'red';
            }
        });

        // Mostrar el botón "Continuar" cuando se acabe el tiempo
        const nextQuestionBtn = document.getElementById('nextQuestionBtn');
        nextQuestionBtn.style.display = 'block';

        return; // Terminar la ejecución aquí
    }
    timeLeft -= 0.1; // Restamos 0.1 para hacer la transición más fluida
};

// Iniciar el temporizador
const startTimer = () => {
    timeLeft = totalTime; // Reinicia el tiempo a 30 segundos
    hideTimeOutMessage(); // Oculta el mensaje de tiempo agotado al comenzar
    updateTimer(); // Actualiza el temporizador al cargar
    timerInterval = setInterval(updateTimer, 100); // Actualiza cada 100ms para mayor fluidez
};

// Llamamos a la función para cargar la primera pregunta
getQuestions();

// Event Listener para el botón de "Continuar"
document.getElementById('nextQuestionBtn').addEventListener('click', nextQuestion);
