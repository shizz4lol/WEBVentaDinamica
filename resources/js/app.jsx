import { createRoot } from 'react-dom/client';
import ContadorCarrito from './components/ContadorCarrito.jsx';
import BuscadorSeries from './components/BuscadorSeries.jsx';
import ModalConfirmacion from './components/ModalConfirmacion.jsx';
import SelectorImagen from './components/SelectorImagen.jsx';
import ListaProductos from './components/ListaProductos.jsx';
import ListaRanking from './components/ListaRanking.jsx';
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

document.querySelectorAll('[data-lista-productos]').forEach((contenedor) => {
    const productos = JSON.parse(contenedor.dataset.productos);

    createRoot(contenedor).render(
        <ListaProductos
            productos={productos}
            tokenCsrf={contenedor.dataset.tokenCsrf}
            urlCarrito={contenedor.dataset.urlCarrito}
            mostrarTipo={contenedor.dataset.mostrarTipo === 'true'}
            mensajeVacio={contenedor.dataset.mensajeVacio}
        />,
    );
});

document.querySelectorAll('[data-lista-ranking]').forEach((contenedor) => {
    const productos = JSON.parse(contenedor.dataset.productos);

    createRoot(contenedor).render(
        <ListaRanking productos={productos} mensajeVacio={contenedor.dataset.mensajeVacio} />,
    );
});

document.querySelectorAll('[data-buscador-series]').forEach((contenedor) => {
    createRoot(contenedor).render(
        <BuscadorSeries
            urlBusqueda={contenedor.dataset.urlBusqueda}
            serieInicial={contenedor.dataset.serieId ? {
                id: Number(contenedor.dataset.serieId),
                nombre: contenedor.dataset.serieNombre,
            } : null}
            tieneError={contenedor.dataset.tieneError === 'true'}
        />,
    );
});

document.querySelectorAll('[data-selector-imagen]').forEach((contenedor) => {
    createRoot(contenedor).render(
        <SelectorImagen
            nombreCampo={contenedor.dataset.nombreCampo}
            imagenActual={contenedor.dataset.imagenActual}
            obligatoria={contenedor.dataset.obligatoria === 'true'}
            tieneError={contenedor.dataset.tieneError === 'true'}
        />,
    );
});

document.querySelectorAll('[data-modal-confirmacion]').forEach((contenedor) => {
    createRoot(contenedor).render(
        <ModalConfirmacion
            accion={contenedor.dataset.accion}
            tokenCsrf={contenedor.dataset.tokenCsrf}
            identificador={contenedor.dataset.identificador}
            titulo={contenedor.dataset.titulo}
            mensaje={contenedor.dataset.mensaje}
            textoBoton={contenedor.dataset.textoBoton}
        />,
    );
});

import './carrusel.js';
