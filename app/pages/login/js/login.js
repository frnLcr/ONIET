document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById('login-form');
    const messageDiv = document.getElementById('message');

    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Evita el envío normal del formulario

        const formData = new FormData(loginForm);

        // Enviar datos mediante fetch
        fetch(loginForm.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            messageDiv.style.display = 'block'; // Muestra el div del mensaje

            if (data.success) {
                messageDiv.style.color = 'green';
                messageDiv.innerHTML = `Inicio de sesión exitoso.<br>¡Bienvenido, ${data.nombre}!`; 
                
                // Aplica la opacidad inicial y muestra el mensaje
                messageDiv.style.opacity = '0'; // Inicialmente transparente
                setTimeout(() => {
                    messageDiv.style.opacity = '1'; // Cambia la opacidad a 1 para hacer el mensaje visible
                }, 10); // Pequeño retraso para permitir que se aplique el estilo

                // Redirigir después de 3 segundos
                setTimeout(() => {
                    window.location.href = './app/pages/leerQr/page/leerQr.php'; // Redirige a la siguiente página
                }, 3000); // Cambiado a 3000 ms para 3 segundos

            } else {
                messageDiv.style.color = 'red';
                messageDiv.textContent = data.message; // Mensaje de error
                
                // Aplica la opacidad inicial y muestra el mensaje
                messageDiv.style.opacity = '0'; // Inicialmente transparente
                setTimeout(() => {
                    messageDiv.style.opacity = '1'; // Cambia la opacidad a 1 para hacer el mensaje visible
                }, 10); // Pequeño retraso para permitir que se aplique el estilo
            }

            // Ocultar el mensaje después de 3 segundos
            setTimeout(() => {
                messageDiv.style.opacity = '0'; // Cambia la opacidad a 0 para ocultar el mensaje
                setTimeout(() => {
                    messageDiv.style.display = 'none'; // Oculta el div después de la transición
                }, 400); // Espera la duración de la transición antes de ocultar
            }, 5000); // Espera 3 segundos antes de empezar a ocultar
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});