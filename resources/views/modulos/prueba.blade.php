<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Configuración para la página en formato carta horizontal */
        @page {
            size: 11in 8.5in;
            /* Tamaño de hoja carta horizontal */
            margin: 0;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        .certificado {
            width: 100%;
            max-width: 1110px;
            height: 90%;

            padding: 30px;

            background-image: url('{{ $imagePath }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-sizing: border-box;
            border-radius: 12px;

            position: relative;
        }

        /* Botón de Imprimir dentro del certificado, parte superior derecha */
        .print-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .print-btn:hover {
            background-color: #0056b3;
        }

        .header {
            position: relative;
            top: 60px;
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 23px;
            margin: 0;
            color: #fff;
            font-weight: bold;
        }

        .header h2:last-child {
            font-size: 70px;
        }

        .body {
            position: relative;
            top: 50px;
            left: -15px;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .body h3 {
            margin: 10px 0;
            font-size: 22px;
            color: #007bff;
            font-weight: bold;
        }

        .body h4 {
            margin: 10px 0;
            font-size: 50px;
            color: #dc3545;
        }

        .body p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
            width: 93%;
            position: relative;
            left: 50px;
            text-align: center;
        }

        .firma {
            position: absolute;
            bottom: 50px;
            left: 35%;
            text-align: center;
        }

        .firma img {
            width: 150px;
        }

        .firma p {
            margin: 5px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            margin-top: 30px;
        }

        /* Ajuste del QR a la parte inferior izquierda dentro del certificado */
        .qr {
            position: absolute;
            bottom: 30px;
            left: 30px;
            width: 150px;
        }

        .qr img {
            width: 100%;
        }

        /* Cinta centrada en el lado izquierdo */
        .cinta {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: auto;
            max-width: 100%;
        }

        .logo {
            position: absolute;
            bottom: 25px;
            left: 85%;
            transform: translateX(-50%);
            width: auto;
            max-width: 100%;
        }
    </style>
</head>

<body>
    <div class="certificado">
        <!-- Botón de Imprimir dentro del certificado, parte superior derecha -->

        <!-- Header -->
        <div class="header">
            <h2>OTORGAMOS EL PRESENTE</h2>
            <h2>CERTIFICADO</h2>
        </div>

        <!-- Body -->
        <div class="body">
            <h3>Al Tecnonauta:</h3>
            <h3 style="font-size:50px">{{ $data['nombre_estudiante'] }}</h3>
            <p>Por haber aprobado el programa regular de:</p>
            <h4><strong>{{ $data['materia'] }}</strong></h4>
            <p>CON UNA DURACION DE <strong>{{ $data['tiempo_materia'] }}</strong>, CUMPLIENDO DE MANERA SATISFACTORIA
                CON TODOS LOS CONTENIDOS DEL PROGRAMA, APROBANDO CON UNA NOTA DE <strong>{{ $data['nota'] }}</strong>
            </p>
        </div>

        <!-- Firma -->
        <div class="firma">
            <img src="{{ $firma }}" alt="Firma del Coordinador">
            <p><strong>Hernán Zabala Naoumov</strong></p>
            <p>Coordinador del Programa Tecnonautas</p>
        </div>

        <!-- QR en la parte inferior izquierda dentro del certificado -->
        <div class="qr">
            <img src="{{ $qrImagePath }}" alt="Código QR">
        </div>

        <!-- Cinta centrada en el lado izquierdo -->
        <img src="{{ $cinta }}" alt="Cinta" class="cinta">

        <!-- Logo en la parte inferior centrado -->
        <img src="{{ $logo }}" alt="Logo" class="logo">
    </div>
</body>

</html>