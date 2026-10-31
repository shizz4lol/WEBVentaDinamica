import TarjetaProducto from './TarjetaProducto.jsx';

export default function ListaProductos({
    productos,
    tokenCsrf,
    urlCarrito,
    mostrarTipo,
    mensajeVacio,
}) {
    if (productos.length === 0) {
        return <p className="col-span-full text-texto-suave">{mensajeVacio}</p>;
    }

    return productos.map((producto) => (
        <TarjetaProducto
            key={producto.id}
            producto={producto}
            tokenCsrf={tokenCsrf}
            urlCarrito={urlCarrito}
            mostrarTipo={mostrarTipo}
        />
    ));
}
