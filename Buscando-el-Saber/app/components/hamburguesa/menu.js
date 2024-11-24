// Seleccionamos los elementos
const barsMenu = document.querySelector('.bars__menu');
const menu = document.querySelector('.container__menu');
const body = document.querySelector('body');

// Función para el menú hamburguesa
barsMenu.addEventListener('click', () => {
    menu.classList.toggle('menu__active');
    body.classList.toggle('menu__active');

    barsMenu.querySelector('.line1__bars-menu').classList.toggle('activeline1__bars-menu');
    barsMenu.querySelector('.line2__bars-menu').classList.toggle('activeline2__bars-menu');
    barsMenu.querySelector('.line3__bars-menu').classList.toggle('activeline3__bars-menu');
});

// Selección de los botones del menú
const comoJugarBtn = document.getElementById('comoJugarBtn');
const estrategiasBtn = document.getElementById('estrategiasBtn');
const sobreNosotrosBtn = document.getElementById('sobreNosotrosBtn');
const rankingBtn = document.getElementById('rankingBtn');
const perfilBtn = document.getElementById('perfilBtn');
const Cerrarsesión = document.getElementById('Cerrarsesión');

// Selección de las modales
const modalComoJugar = document.getElementById('modalComoJugar');
const modalEstrategias = document.getElementById('modalEstrategias');
const modalSobreNosotros = document.getElementById('modalSobreNosotros');
const modalRanking = document.getElementById('modalRanking');
const modalPerfil = document.getElementById('modalPerfil');
const modmodalcerrarsesion = document.getElementById('modalcerrarsesion');

// Selección de los botones para cerrar las modales
const closeButtons = document.querySelectorAll('.close');

// Funciones para abrir las modales
comoJugarBtn.addEventListener('click', () => {
    modalComoJugar.style.display = 'block';
});

estrategiasBtn.addEventListener('click', () => {
    modalEstrategias.style.display = 'block';
});

sobreNosotrosBtn.addEventListener('click', () => {
    modalSobreNosotros.style.display = 'block';
});
rankingBtn.addEventListener('click', () => {
    modalRanking.style.display = 'block';
});
perfilBtn.addEventListener('click', () => {
    modalPerfil.style.display = 'block';
});
Cerrarsesión.addEventListener('click', () => {
    modmodalcerrarsesion.style.display = 'block';
});



// Función para cerrar las modales cuando se hace clic en la "X"
closeButtons.forEach(button => {
    button.addEventListener('click', () => {
        button.closest('.modal').style.display = 'none';
    });
});

// Cerrar la modal si se hace clic fuera de la ventana modal
window.addEventListener('click', (event) => {
    if (event.target.classList && event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
});

