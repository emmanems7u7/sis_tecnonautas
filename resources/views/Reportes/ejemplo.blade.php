<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        h1 {
            color: #2e6da4;
        }

        .contenido {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <h1>{{ $titulo }}</h1>
    <div class="contenido">
        <p><strong>Nombre:</strong> {{ $nombre }}</p>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>
    </div>
</body>

</html>