<!DOCTYPE html>
<html>

<head>
    <title>Resultados de Filtrado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .email-container {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .email-header {
            margin-bottom: 10px;
        }

        .email-body {
            max-height: 300px;
            overflow-y: auto;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Resultados del Filtro de Correos</h1>
        @if(count($emails) > 0)
        @foreach ($emails as $email)
        <div class="email-container">
            <div class="email-header">
                <h3>{{ $email['subject'] }}</h3>
                <p><strong>Desde:</strong> {{ $email['from'] }}</p>
                <p><strong>Fecha:</strong> {{ $email['date'] }}</p>
            </div>
            <div class="email-body">
                {!! $email['body'] !!}
            </div>
        </div>
        @endforeach
        @else
        <p>No se encontraron correos en el rango de fechas especificado.</p>
        @endif
    </div>
</body>

</html>