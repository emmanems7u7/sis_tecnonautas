<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<style>
    /* Tabla general */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        color: #212529;
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    /* Cabecera de tabla */
    .table thead th {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: center;
        font-weight: bold;
    }

    /* Celdas */
    .table td,
    .table th {

        border: 1px solid #dee2e6;
        padding: 1px;
        vertical-align: middle;
        text-align: center;
    }

    /* Texto blanco y bordes redondeados para bloques */
    .text-white {
        color: #fff;
    }

    .p-1 {
        padding: 4px;
    }

    .rounded {
        border-radius: 0.25rem;
    }

    /* Colores de fondo personalizados */
    .bg-dark {
        background-color: #343a40 !important;
    }

    /* Estilos para bloques de materias */
    .bloque-materia {
        color: #fff;
        padding: 4px;
        border-radius: 0.25rem;
    }

    /* Opcional: Control de tamaño del texto en bloques */
    .bloque-materia small {
        font-size: 9px;
    }

    body {
        margin-top: 40px !important;
        margin-bottom: 50px !important;
        font-family: sans-serif;
    }

    .card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        background-color: #ffffff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1rem;
        padding: 0;
        overflow: hidden;
    }

    .card-body {
        padding: 1rem;
        font-size: 1rem;
        color: #212529;
        background-color: rgba(248, 249, 250, 0.85);
        /* fondo suave ligeramente transparente */
        font-weight: 500;
    }


    .contenido-reporte {
        padding: 1.25rem;
        color: #212529;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .titulo-reporte {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 1rem;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 0.25rem;
        color: #0d6efd;
    }

    .dato-reporte {
        margin: 0.5rem 0;
        font-size: 1rem;
        line-height: 1.6;
    }

    .etiqueta {
        font-weight: 600;
        color: #495057;
    }
</style>

<body>

    <div id="background" style="position: fixed; top: -45; left: -45px; width: 816px; height: 1079px; z-index: -1;">
        <img src="{{ $src_body }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <div class="card">
        <div class="card-body contenido-reporte">
            <h5 class="titulo-reporte">Reporte de Horarios</h5>
            <p class="dato-reporte"><span class="etiqueta">Profesor:</span> {{ $usuario->name }}
                {{  $usuario->apepat  }} {{  $usuario->apemat  }}
            </p>
            <p class="dato-reporte"><span class="etiqueta">Fecha de generación de reporte:</span> {{ $fecha }}</p>
        </div>
    </div>
    @include('Personal.tabla_horarios')
</body>

</html>