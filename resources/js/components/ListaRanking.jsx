export default function ListaRanking({ productos, mensajeVacio }) {
    if (productos.length === 0) {
        return <li className="py-4 text-sm text-texto-suave">{mensajeVacio}</li>;
    }

    return productos.map((producto, indice) => (
        <li
            key={producto.id}
            className="grid grid-cols-[1.5rem_3rem_minmax(0,1fr)] items-center gap-3 py-4"
        >
            <span className="font-titulo text-3xl text-rosa">
                <span className="sr-only">Puesto </span>
                {indice + 1}
            </span>
            {producto.imagen ? (
                <img
                    src={producto.imagen}
                    alt=""
                    width="48"
                    height="72"
                    loading="lazy"
                    className="aspect-[2/3] w-12 object-cover"
                />
            ) : (
                <span className="aspect-[2/3] bg-superficie-2" aria-hidden="true" />
            )}
            <div className="min-w-0">
                <a
                    href={producto.urlDetalle}
                    className="flex min-h-11 items-center text-sm font-bold hover:text-rosa"
                >
                    {producto.nombre}
                </a>
                <p className="text-xs text-texto-suave">{producto.serie}</p>
            </div>
        </li>
    ));
}
