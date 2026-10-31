export default function TarjetaProducto({ producto, mostrarTipo, tokenCsrf, urlCarrito }) {
    return (
        <article className="tarjeta producto group flex min-w-0 flex-col hover:border-rosa">
            <a
                href={producto.urlDetalle}
                className="relative block aspect-[2/3] overflow-hidden rounded-lg bg-superficie-2"
                aria-label={`Ver ${producto.nombre}`}
            >
                {producto.imagen ? (
                    <img
                        src={producto.imagen}
                        alt={`Portada de ${producto.nombre}`}
                        width="320"
                        height="480"
                        loading="lazy"
                        className={`h-full w-full object-cover${producto.sinStock ? ' opacity-50 grayscale' : ''}`}
                    />
                ) : (
                    <span className="flex h-full items-center justify-center p-4 text-center text-texto-suave">
                        {producto.nombre}
                    </span>
                )}
                {producto.pocasUnidades ? (
                    <span className="badge absolute top-2 left-2 w-auto bg-rosa text-fondo">Últimas unidades</span>
                ) : producto.esNuevo ? (
                    <span className="badge absolute top-2 left-2 w-auto bg-rosa text-fondo">Nuevo</span>
                ) : null}
            </a>

            <div className="mt-3 flex flex-1 flex-col">
                {mostrarTipo && (
                    <div className="mb-2 flex flex-wrap gap-1.5">
                        <span className="badge border border-borde text-texto-suave">
                            {producto.tipo === 'manga' ? 'Manga' : 'Cómic'}
                        </span>
                    </div>
                )}

                <h3 className="font-semibold leading-snug -my-1">
                    <a
                        href={producto.urlDetalle}
                        className="flex min-h-11 items-center hover:text-rosa"
                    >
                        {producto.nombre}
                    </a>
                </h3>
                {producto.serie && <p className="mt-0.5 text-sm text-texto-suave">{producto.serie}</p>}
                {producto.numero && <p className="mt-1 text-sm text-texto-suave">Tomo {producto.numero}</p>}

                <p className="mt-auto pt-3 text-lg font-bold">{producto.precio}</p>

                {!producto.sinStock ? (
                    <form action={urlCarrito} method="post" className="mt-2">
                        <input type="hidden" name="_token" value={tokenCsrf} />
                        <input type="hidden" name="producto_id" value={producto.id} />
                        <button type="submit" className="btn btn-primario w-full">
                            Añadir al carrito
                            <span className="sr-only">: {producto.nombre}</span>
                        </button>
                    </form>
                ) : (
                    <button type="button" className="btn mt-2 w-full" disabled>
                        Sin stock
                    </button>
                )}
            </div>
        </article>
    );
}
