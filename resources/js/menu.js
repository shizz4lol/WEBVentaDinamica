// Menú hamburguesa del header: en el celular muestra u oculta los links.
// En pantallas grandes (1024 px o más) el menú siempre se ve (clase lg:flex en el header).
const boton = document.getElementById('menu-boton');
const menu = document.getElementById('menu-principal');

if (boton && menu) {
    boton.addEventListener('click', () => {
        const abierto = boton.getAttribute('aria-expanded') === 'true';
        boton.setAttribute('aria-expanded', String(!abierto));
        // Abierto: "flex" (links en columna). Cerrado: "hidden".
        menu.classList.toggle('hidden', abierto);
        menu.classList.toggle('flex', !abierto);
    });
}
