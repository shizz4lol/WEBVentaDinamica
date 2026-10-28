import { createRoot } from 'react-dom/client';
import ContadorCarrito from './components/ContadorCarrito.jsx';
import './menu.js';

// ---- Ejemplo de React en Laravel ----
// 1. Laravel dibuja la página con Blade (layouts/header.blade.php).
// 2. En el header hay un <span id="contador-carrito" data-cantidad="2">.
// 3. Acá lo buscamos y "montamos" adentro el componente ContadorCarrito,
//    pasándole como prop la cantidad que vino de Laravel en data-cantidad.
// Para montar otro componente, repetí la idea: un elemento con id en Blade + createRoot acá.
const contenedor = document.getElementById('contador-carrito');

if (contenedor) {
    const cantidad = Number(contenedor.dataset.cantidad) || 0;
    createRoot(contenedor).render(<ContadorCarrito cantidadInicial={cantidad} />);
}

import './carrusel.js';
