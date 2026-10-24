@extends('layouts.app')
@section ('contenido')
    <div class="form-container">
        <h1>¡CONTACTANOS!</h1>
        <div class="form-box" id="contacto-form">
            <form method="POST" action="#" autocomplete="off" >
                @csrf
                <div class="label-input">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre"  placeholder="Pepito" required>
                </div>
                <div class="label-input">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Pérez" required>
                </div>
                <div class="label-input">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo"  placeholder="pepito@example.com" required>
                </div>
                <div class="label-input">
                    <label for="asunto">Asunto:</label>
                    <input type="text" id="asunto" name="asunto" placeholder="Consulta" required>
                </div>
                <div class="label-input">
                    <label for="mensaje">Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" rows="4"  placeholder="Escribe tu mensaje aquí..." required></textarea>
                </div>
                <button type="submit" class="btn-submit">ENVIAR</button>
            </form>
        </div>
    </div>
@endsection