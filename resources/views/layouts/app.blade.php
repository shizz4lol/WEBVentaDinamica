<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta Dinamica</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
</head>
<body>
    @include('layouts.header')
    <div id="contenido">
    @yield ('contenido')
    </div>
    @include('layouts.footer')
</body>
</html>