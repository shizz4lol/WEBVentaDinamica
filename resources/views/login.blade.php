@extends ('layouts.app')
@section ('contenido')
    <div class="form-container">
        <h1>INICIAR SESION</h1>
        <div class="form-box">
            <form method="POST" action="#" autocomplete="off" >
                @csrf
                <div class="label-input">
                    <label for="nombre_usuario">Usuario:</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" required >
                </div>
                <div class="label-input">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" required>
                </div>
                <button type="submit" class="btn-submit">INGRESAR</button>
            </form>
            
        </div>
        <p>¿No tienes cuenta? <a href="#" id="registrate"><b>Regístrate</b></a></p>  
    </div>
@endsection 