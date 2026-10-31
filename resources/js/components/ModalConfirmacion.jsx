import { useRef } from 'react';

export default function ModalConfirmacion({ accion, tokenCsrf, identificador, titulo, mensaje, textoBoton }) {
    const dialogo = useRef(null);
    const idTitulo = `modal-confirmacion-titulo-${identificador}`;
    const idMensaje = `modal-confirmacion-mensaje-${identificador}`;

    function abrirDialogo() {
        dialogo.current?.showModal();
    }

    function cerrarAlTocarFondo(evento) {
        if (evento.target === dialogo.current) {
            dialogo.current.close();
        }
    }

    return (
        <>
            <button type="button" className="btn border border-error/60 text-error hover:bg-error/10" onClick={abrirDialogo}>
                Borrar
            </button>
            <dialog
                ref={dialogo}
                onClick={cerrarAlTocarFondo}
                aria-labelledby={idTitulo}
                aria-describedby={idMensaje}
                className="m-auto w-[calc(100%-2rem)] max-w-lg rounded-xl border border-borde bg-superficie p-0 text-texto backdrop:bg-fondo/80"
            >
                <div className="grid gap-5 p-6">
                    <div className="grid gap-2">
                        <h2 id={idTitulo} className="font-titulo text-xl tracking-wide">{titulo}</h2>
                        <p id={idMensaje} className="text-sm leading-relaxed text-texto-suave">{mensaje}</p>
                    </div>
                    <div className="flex flex-wrap justify-end gap-3">
                        <button type="button" className="btn btn-secundario" onClick={() => dialogo.current?.close()}>
                            Cancelar
                        </button>
                        <form action={accion} method="post">
                            <input type="hidden" name="_token" value={tokenCsrf} />
                            <input type="hidden" name="_method" value="DELETE" />
                            <button type="submit" className="btn bg-error text-fondo hover:bg-rosa">
                                {textoBoton}
                            </button>
                        </form>
                    </div>
                </div>
            </dialog>
        </>
    );
}
