<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $detalle['titulo'] }}</title>
    <!-- Incluyendo el CDN de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #4CAF50;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 32px;
            color: #333;
            font-weight: bold;
            margin: 0;
        }

        .content {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
            padding: 0 15px;
        }

        .content p {
            margin-bottom: 20px;
        }

        .cta-button {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            padding: 12px 25px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #45a049;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 25px;
            font-size: 14px;
            color: #888;
            border-top: 2px solid #ddd;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer strong {
            color: #333;
        }

        .footer img {
            max-width: 150px;
            margin-top: 20px;
            border-radius: 5px;
        }

        /* Estilo adicional para la nota */
        .nota {
            font-size: 20px;
            font-weight: bold;
            color: #4CAF50;
            background-color: #e8f5e9;
            border-left: 5px solid #388e3c;
            padding: 10px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $detalle['titulo'] }}</h1>
        </div>

        <div class="content">
            <p>{{ $detalle['cuerpo'] }}</p>

            <p>Estamos muy contentos de que hayas completado el curso. ¡Tu esfuerzo ha dado frutos!</p>
            <!-- Nota destacada -->
            <div class="nota">
                <p><strong>¡Felicidades! Has aprobado el curso con una nota de:
                        <span>{{ $detalle['nota'] }}</span></strong></p>
            </div>

            <p>Para acceder a tu certificado de finalización, haz clic en el siguiente enlace:</p>

            <!-- Enlace estilizado como botón -->
            <a href="{{ $detalle['enlace'] }}" class="cta-button" target="_blank">Ver tu Certificado</a>
        </div>

        <div class="footer">
            <p>Si tienes alguna pregunta, no dudes en ponerte en contacto con nosotros.</p>
            <p>Gracias por ser parte de nuestra comunidad.</p>
            <p><strong>TECNONAUTAS</strong></p>

            <!-- Logo en el footer -->
            <img src="{{ asset('storage/logo_tecnonautas.png') }}" alt="Logo TECNONAUTAS">
        </div>
    </div>
</body>

</html>