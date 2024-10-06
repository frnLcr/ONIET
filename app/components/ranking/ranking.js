const Rank = document.getElementById('rank'); // Quitar el # al acceder al ID.
let usuario = [];
const UrlJson = "https://668d50f6099db4c579f28cd1.mockapi.io/api/final/productos";

async function buscarproductoJSON() {
    try {
        const response = await fetch(UrlJson);

        // Verificar si la respuesta fue exitosa (status 200)
        if (!response.ok) {
            throw new Error(`Error al cargar el archivo JSON: ${response.statusText}`);
        }

        // Parsear el contenido del archivo JSON
        const datos = await response.json();
        console.log("Datos cargados correctamente:", datos);  // Log para verificación

        // Actualizar el array global de usuarios
        usuario = datos;
        
        // Cargar los usuarios en el contenedor
        cargarUsuarios(usuario);
    } catch (error) {
        console.error('Error al cargar productos:', error);
        Rank.innerHTML = retornarCardError();
    }
}

// Función para mostrar cada usuario en una lista HTML
function retornarCardHTML(usuario) {
    return `
        <ul>
            <li class="top">${usuario.position}. ${usuario.name} - ${usuario.score} puntos</li>
        </ul>`;
}

// Retornar mensaje de error si no se pueden cargar los datos
function retornarCardError() {
    return `<div>
                <h2>Se ha producido un error</h2>
                <h3>Intenta nuevamente en unos instantes... 🤦🏻‍♂️</h3>
            </div>`;
}

// Función para cargar los usuarios en el contenedor de ranking
function cargarUsuarios(array) {
    try {
        if (array.length > 0) {
            Rank.innerHTML = "";  // Limpiar el contenedor antes de agregar nuevos elementos

            // Mostrar solo los primeros 5 puestos
            const top5 = array.slice(0, 5);
            top5.forEach((usuario) => {
                Rank.innerHTML += retornarCardHTML(usuario);
            });

            // Mostrar el último puesto (fuera del top 5)
            const ultimo = array[array.length - 1];
            Rank.innerHTML += retornarCardHTML(ultimo);
        }
    } catch (error) {
        console.error('Error al cargar usuarios:', error);
        Rank.innerHTML = retornarCardError();
    }   
}

// Llamada para cargar los productos cuando la página esté lista
document.addEventListener('DOMContentLoaded', buscarproductoJSON);
