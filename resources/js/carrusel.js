document.querySelectorAll('[data-carrusel]').forEach((carrusel) => {
    const pista = carrusel.querySelector('[data-pista]');
    const originales = [...pista.querySelectorAll('[data-diapositiva]')];
    const movimiento = window.matchMedia('(prefers-reduced-motion: reduce)');
    const escritorio = window.matchMedia('(min-width: 768px)');
    const total = originales.length;
    if (!total) return;
    let actual = 0, fisico = 1, hover = false;
    let timer, finTransicion, desplazando = false;

    // Los duplicados permiten cruzar los extremos sin un salto visible.
    pista.prepend(originales.at(-1).cloneNode(true));
    originales.slice(0, 2).forEach((slide) => pista.append(slide.cloneNode(true)));
    const slides = [...pista.children];
    function programar() {
        clearInterval(timer);
        if (total > 1 && !hover && !document.hidden && !movimiento.matches) {
            timer = setInterval(() => mostrar(actual + 1), 5000);
        }
    }
    function colocar(animar) {
        pista.classList.toggle('sin-transicion', !animar);
        const paso = slides[1].offsetLeft - slides[0].offsetLeft;
        pista.style.transform = `translateX(${-fisico * paso}px)`;
        slides.forEach((slide, i) => {
            const visible = i >= fisico && i < fisico + (escritorio.matches ? 2 : 1);
            slide.setAttribute('aria-hidden', String(!visible));
            slide.querySelector('a').tabIndex = visible ? 0 : -1;
            slide.classList.toggle('es-visible', visible);
        });
    }
    function terminar() {
        clearTimeout(finTransicion);
        desplazando = false;
        fisico = actual + 1;
        colocar(false);
    }
    function mostrar(indice) {
        if (desplazando) return;
        actual = (indice + total) % total;
        fisico = indice < 0 ? 0 : indice >= total ? total + 1 : actual + 1;
        const animar = !movimiento.matches;
        desplazando = animar;
        colocar(animar);
        if (animar) finTransicion = setTimeout(terminar, 650);
        else terminar();
        programar();
    }
    pista.addEventListener('transitionend', (event) => {
        if (event.target === pista && event.propertyName === 'transform') terminar();
    });
    carrusel.addEventListener('mouseenter', () => { hover = true; programar(); });
    carrusel.addEventListener('mouseleave', () => { hover = false; programar(); });
    movimiento.addEventListener('change', () => { terminar(); programar(); });
    new ResizeObserver(() => {
        terminar();
        const activo = document.activeElement;
        if (activo?.closest('[data-diapositiva][aria-hidden="true"]')) carrusel.focus();
    }).observe(carrusel);
    document.addEventListener('visibilitychange', programar);
    terminar();
    programar();
});
