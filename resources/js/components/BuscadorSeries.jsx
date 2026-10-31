import { useEffect, useState } from 'react';

export default function BuscadorSeries({ urlBusqueda, serieInicial, tieneError }) {
    const [busqueda, setBusqueda] = useState(serieInicial?.nombre ?? '');
    const [serieSeleccionada, setSerieSeleccionada] = useState(serieInicial);
    const [resultados, setResultados] = useState([]);
    const [cargando, setCargando] = useState(false);
    const [error, setError] = useState('');

    useEffect(() => {
        const termino = busqueda.trim();

        if (serieSeleccionada?.nombre === termino || termino.length < 2) {
            setResultados([]);
            setCargando(false);
            setError('');

            return undefined;
        }

        const controlador = new AbortController();
        const temporizador = window.setTimeout(async () => {
            setCargando(true);
            setError('');

            try {
                const parametros = new URLSearchParams({ q: termino });
                const respuesta = await fetch(`${urlBusqueda}?${parametros}`, {
                    headers: { Accept: 'application/json' },
                    signal: controlador.signal,
                });

                if (!respuesta.ok) {
                    throw new Error('No se pudieron cargar las series.');
                }

                setResultados(await respuesta.json());
            } catch (errorBusqueda) {
                if (errorBusqueda.name !== 'AbortError') {
                    setError('No se pudieron cargar las series. Intentá nuevamente.');
                }
            } finally {
                if (!controlador.signal.aborted) {
                    setCargando(false);
                }
            }
        }, 200);

        return () => {
            window.clearTimeout(temporizador);
            controlador.abort();
        };
    }, [busqueda, serieSeleccionada, urlBusqueda]);

    function actualizarBusqueda(evento) {
        setBusqueda(evento.target.value);
        setSerieSeleccionada(null);
    }

    function seleccionarSerie(serie) {
        setSerieSeleccionada(serie);
        setBusqueda(serie.nombre);
        setResultados([]);
        setError('');
    }

    function quitarSerie() {
        setSerieSeleccionada(null);
        setBusqueda('');
        setResultados([]);
        setError('');
    }

    const idEstado = 'busqueda-series-estado';
    const idResultados = 'busqueda-series-resultados';

    return (
        <div className="grid gap-2">
            <label htmlFor="busqueda-series" className="sr-only">Buscar serie por nombre</label>
            <input
                id="busqueda-series"
                type="search"
                value={busqueda}
                onChange={actualizarBusqueda}
                autoComplete="off"
                placeholder="Escribí al menos 2 caracteres"
                className="campo"
                role="combobox"
                aria-autocomplete="list"
                aria-expanded={resultados.length > 0}
                aria-controls={idResultados}
                aria-describedby={tieneError ? 'error-producto-serie' : idEstado}
                aria-invalid={tieneError}
            />
            <input
                type="hidden"
                name="producto[serie_id]"
                value={serieSeleccionada?.id ?? ''}
            />

            {serieSeleccionada && (
                <div className="flex flex-wrap items-center justify-between gap-2 text-sm">
                    <p className="text-texto-suave">Serie seleccionada: <span className="font-semibold text-texto">{serieSeleccionada.nombre}</span></p>
                    <button type="button" className="enlace" onClick={quitarSerie}>Quitar serie</button>
                </div>
            )}

            <p id={idEstado} role="status" aria-live="polite" className="text-sm text-texto-suave">
                {cargando
                    ? 'Buscando series…'
                    : error
                        ? error
                        : busqueda.trim().length < 2 && !serieSeleccionada
                            ? 'La serie es opcional. Escribí 2 caracteres o más para buscar.'
                            : resultados.length === 0 && !serieSeleccionada && busqueda.trim().length >= 2
                                ? 'No encontramos series con ese nombre.'
                                : ''}
            </p>

            {resultados.length > 0 && (
                <ul
                    id={idResultados}
                    role="listbox"
                    aria-label="Series encontradas"
                    className="max-h-60 overflow-y-auto rounded-lg border border-borde bg-superficie"
                >
                    {resultados.map((serie) => (
                        <li key={serie.id} role="presentation">
                            <button
                                type="button"
                                role="option"
                                aria-selected={serieSeleccionada?.id === serie.id}
                                className="min-h-11 w-full px-3 py-2 text-left hover:bg-superficie-2 hover:text-rosa"
                                onClick={() => seleccionarSerie(serie)}
                            >
                                {serie.nombre}
                            </button>
                        </li>
                    ))}
                </ul>
            )}
        </div>
    );
}
