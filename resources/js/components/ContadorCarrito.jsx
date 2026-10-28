import { useEffect, useState } from 'react';

// Globito rosa con la cantidad de productos del carrito.
//
// - props: recibe "cantidadInicial" desde app.jsx (que la lee de Laravel).
// - useState: guarda la cantidad en el estado del componente. Cuando el estado cambia,
//   React vuelve a dibujar el globito solo, sin recargar la página.
// - useEffect: al montarse, empieza a escuchar el evento "carrito:actualizado".
//   Cuando hagas el carrito con React, avisale al contador así:
//     window.dispatchEvent(new CustomEvent('carrito:actualizado', { detail: { cantidad: 3 } }));
export default function ContadorCarrito({ cantidadInicial }) {
    const [cantidad, setCantidad] = useState(cantidadInicial);

    useEffect(() => {
        const actualizar = (evento) => setCantidad(evento.detail.cantidad);
        window.addEventListener('carrito:actualizado', actualizar);

        // Limpieza: si el componente se desmonta, deja de escuchar el evento.
        return () => window.removeEventListener('carrito:actualizado', actualizar);
    }, []);

    if (cantidad === 0) {
        return null; // carrito vacío: no se muestra el globito
    }

    return (
        <span className="absolute -top-1.5 -right-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-rosa px-1 text-xs font-bold text-fondo">
            <span className="sr-only">Productos en el carrito: </span>
            {cantidad}
        </span>
    );
}
