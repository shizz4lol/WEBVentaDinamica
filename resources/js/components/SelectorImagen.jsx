import { useEffect, useState } from 'react';

export default function SelectorImagen({ nombreCampo, imagenActual, obligatoria, tieneError }) {
    const [archivo, setArchivo] = useState(null);
    const [vistaPrevia, setVistaPrevia] = useState('');

    useEffect(() => {
        if (!archivo) {
            setVistaPrevia('');

            return undefined;
        }

        const url = URL.createObjectURL(archivo);
        setVistaPrevia(url);

        return () => URL.revokeObjectURL(url);
    }, [archivo]);

    function seleccionarArchivo(evento) {
        setArchivo(evento.target.files?.[0] ?? null);
    }

    const idArchivo = 'producto-imagen';
    const idEstado = 'producto-imagen-estado';

    return (
        <div className="grid gap-3 sm:grid-cols-[minmax(0,1fr)_8rem] sm:items-center">
            <div className="grid gap-2">
                <input
                    id={idArchivo}
                    name={nombreCampo}
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    required={obligatoria}
                    onChange={seleccionarArchivo}
                    aria-describedby={tieneError ? 'error-producto-imagen' : idEstado}
                    aria-invalid={tieneError}
                    className="peer sr-only"
                />
                <label
                    htmlFor={idArchivo}
                    className="flex min-h-14 cursor-pointer flex-wrap items-center justify-between gap-3 rounded-lg border border-borde bg-superficie px-4 py-3 hover:border-rosa peer-focus-visible:outline-3 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-rosa"
                >
                    <span className="font-semibold">Elegir imagen</span>
                    <span aria-hidden="true" className="text-sm text-texto-suave">JPG, PNG o WebP</span>
                </label>
                <p id={idEstado} role="status" aria-live="polite" className="text-sm text-texto-suave">
                    {archivo
                        ? `Imagen seleccionada: ${archivo.name}`
                        : imagenActual
                            ? 'No seleccionaste un archivo nuevo; se conservará la imagen actual.'
                            : 'Todavía no seleccionaste un archivo.'}
                </p>
            </div>

            {(vistaPrevia || imagenActual) && (
                <img
                    src={vistaPrevia || imagenActual}
                    alt={archivo ? `Vista previa de ${archivo.name}` : 'Imagen actual del producto'}
                    className="aspect-[2/3] w-24 rounded-lg border border-borde object-cover"
                />
            )}
        </div>
    );
}
