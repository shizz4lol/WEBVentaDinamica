<header id="header">
    
    <div id="ham-cont">
        <button id="ham-btn" title="Abrir/cerrar menú">&#9776;</button>
    </div>
    <div class="nom">
        <a href="{{ route('inicio')}}"><p>Venta Dinamica</p></a>
    </div>
    <div class="buscador" >
        <form action="" method="post" >
            <input type="text"  placeholder="Buscar producto..."name="buscador" autocomplete="off" id="buscador">
            <button class="lupa" type="button">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </form>
        <div id="resultados-live"></div>
    </div>
    
    <div class="carrito-cont">
    <div id="usuario-cont">
        <a href=""><p>Crear Cuenta &#8203;</p></a>
        <p> | &#8203;</p>
        <a href="{{ route('login') }}"><p> Iniciar Sesión</p></a>
    </div><br>
        <button href="" id="carrito-btn" title="Ver carrito">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="carrito-count"></span>
        </button>
    </div>
</header>
