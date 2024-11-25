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
                    const dni = data.dni; // Supongamos que el backend devuelve el DNI del usuario
                    messageDiv.style.color = 'green';
                    messageDiv.innerHTML = `Inicio de sesión exitoso.<br>¡Bienvenido, ${data.nombre}!`;

                    // Aplica la opacidad inicial y muestra el mensaje
                    messageDiv.style.opacity = '0'; // Inicialmente transparente
                    setTimeout(() => {
                        messageDiv.style.opacity = '1'; // Cambia la opacidad a 1 para hacer el mensaje visible
                    }, 10); // Pequeño retraso para permitir que se aplique el estilo

                    // Determinar la URL de redirección según el DNI
                    let redirectUrl; // Declaración de la variable

                    if (dni === '2025') {
                        redirectUrl = './app/pages/admin/page/admin.php'; // Página de administración
                    } else {
                        redirectUrl = './app/pages/pregunta/page/pregunta.php'; // Página por defecto
                    }

                    // Redirigir después de 3 segundos
                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 3000); // 3 segundos
                } else {
                    messageDiv.style.color = 'red';
                    messageDiv.textContent = data.message; // Mensaje de error

                    // Aplica la opacidad inicial y muestra el mensaje
                    messageDiv.style.opacity = '0'; // Inicialmente transparente
                    setTimeout(() => {
                        messageDiv.style.opacity = '1'; // Cambia la opacidad a 1 para hacer el mensaje visible
                    }, 10); // Pequeño retraso para permitir que se aplique el estilo
                }

                // Ocultar el mensaje después de 5 segundos
                setTimeout(() => {
                    messageDiv.style.opacity = '0'; // Cambia la opacidad a 0 para ocultar el mensaje
                    setTimeout(() => {
                        messageDiv.style.display = 'none'; // Oculta el div después de la transición
                    }, 400); // Espera la duración de la transición antes de ocultar
                }, 5000); // Espera 5 segundos antes de empezar a ocultar
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
