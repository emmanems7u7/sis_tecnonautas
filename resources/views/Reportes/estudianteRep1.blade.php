<style>
    /* Reset de estilos */
    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    blockquote,
    pre,
    dl,
    dd,
    ol,
    ul,
    figure,
    hr,
    fieldset,
    legend,
    button,
    input,
    textarea,
    th,
    td {
        margin: 0;

        border: 0;
    }

    /* Estilos para el cuerpo del documento */
    body {
        font-family: Arial, sans-serif;

        font-size: 14px;
        line-height: 1.5;
    }

    /* Estilos para los encabezados */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin-bottom: 0.1rem;
        font-weight: 500;
        line-height: 1.2;
    }

    /* Estilos para los párrafos */
    p {
        margin-bottom: 1rem;
    }

    /* Estilos para las tablas */
    table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }


    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: transparent;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
    }

    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0, 123, 255, 0.03);
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-body {
        flex: 1 1 auto;
        padding: 1.25rem;
    }

    .card-title {
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .card-text {
        margin-top: 0;
        margin-bottom: 1rem;
    }
</style>
<style>
    #evaluacionesTable {
        border-collapse: collapse;
        width: 100%;
    }

    #evaluacionesTable th,
    #evaluacionesTable td {
        border: 1px solid #dee2e6;
        /* Añade un borde suave a las celdas */
        padding: 8px;
        text-align: left;
    }

    #evaluacionesTable th {
        background-color: #f8f9fa;
        /* Color de fondo para los encabezados */
        font-weight: bold;
    }
</style>

<style>
    .table-bordered {
        border-collapse: collapse;
        width: 100%;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
        /* Bordes suaves para las celdas */
        padding: 8px;
        text-align: left;
    }

    .table-bordered th {
        background-color: #f8f9fa;
        /* Color de fondo para los encabezados */
        font-weight: bold;
    }

    .margin_b {}
</style>

<style>
    body {
        margin-top: 40px !important;
        margin-bottom: 50px !important;
        font-family: sans-serif;
    }

    footer {
        position: fixed;
        bottom: 70;
        width: 100%;
        text-align: center;
        background: transparent;
        padding: 0px;
    }

    .firma-contenedor {
        position: relative;
        /* Para que la imagen pueda posicionarse de manera absoluta dentro de este contenedor */
    }

    .firma {
        position: absolute;
        /* La imagen se coloca en una posición absoluta dentro del contenedor */
        top: 0;
        /* Ajusta la posición desde la parte superior */
        left: 0;
        /* Ajusta la posición desde la izquierda */
        width: 100px;
        /* Tamaño de la imagen */
        height: auto;
        /* Mantiene la relación de aspecto de la imagen */
    }
</style>

<body>

    <div id="background" style="position: fixed; top: -45; left: -45px; width: 816px; height: 1079px; z-index: -1;">
        <img src="{{ $data['src_body'] }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <div class="container-fluid mt-per">
        <table class="table margin_b">
            <thead>
                <tr>
                    <th colspan="2" class="text-center" style="background-color: rgba(240, 240, 240, 0.8);">
                        <h5 class="card-title" id="estudiante">Estudiante:
                            {{$data['usuario']->usuario_nombres}} {{$data['usuario']->usuario_app}}
                            {{$data['usuario']->usuario_apm}}
                        </h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center" style="width: 5%;">
                        <img src="{{ $data['img_src'] }}" class="img-fluid rounded-start border shadow-lg"
                            alt="Imagen del estudiante" id="fotoe" style="width: 200px;">
                    </td>
                    <td>
                        <p class="card-text" id="materia"><strong>Materia:</strong>
                            {{$data['materia']}}</p>

                        <p class="card-text" id="modulo"><strong>Módulo:</strong>
                            {{$data['nombreMod']->nombreM}}</p>
                        <p class="card-text" id="paralelo"><strong>Paralelo:</strong>
                            {{$data['paralelo']->nombre}}</p>
                        <p class="card-text" id="profesor"><strong>Profesor:</strong>
                            {{$data['profesor']->usuario_nombres}} {{$data['profesor']->usuario_app}}
                            {{$data['profesor']->usuario_apm}}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Encabezado Reporte -->
        <h4 style="font-size: 17px;">Reporte de evaluaciones y tareas</h4>
        <br>
        <!-- Segundo Card - Evaluaciones -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">Evaluaciones</h5>
            </div>
            <div class="card-body">

                <table class="table table-sm" id="evaluacionesTable">
                    <thead>
                        <tr>
                            <th scope="col">Evaluación</th>
                            <th scope="col">Nota</th>
                            <th scope="col">Creado</th>
                            <th scope="col">Límite</th>
                            <th scope="col">Completado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['evaluaciones'] as $evaluacion)
                            <tr>
                                <td>{{ $evaluacion->nombre }}</td>
                                <td>{{ $evaluacion->nota }}</td>
                                <td>{{ $evaluacion->creado }}</td>
                                <td>{{ $evaluacion->limite }}</td>
                                <td>{{ $evaluacion->completado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>




            </div>
        </div>
        <br>
        <!-- Tercer Card - Tareas Asignadas -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tareas</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Descripción</th>
                            <th>Nota</th>
                            <th>Entregado</th>
                            <th>Límite</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tareasEstudiantes as $tarea)
                            <tr>
                                <td>{{ $tarea->nombre }}</td>
                                <td>{{ $tarea->detalle }}</td>
                                <td>
                                    <span style="color: {{ $tarea->nota > 51 ? 'green' : 'red' }}">
                                        {{ $tarea->nota }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($tarea->entregado)->format('d/m/Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($tarea->limite)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        @if($comentario)
            <div
                style=" text-align: justify; margin-top: 1rem; padding: 1rem; background-color: #f8f9fa; border-left: 4px solid #0d6efd; border-radius: 0.25rem;">
                <h5 style="margin-bottom: 1rem; font-weight: bold; color: #0d6efd;">Comentario del Profesor</h5>
                <p>{{ $comentario }}</p>
            </div>
        @endif

        <footer>

            <img src="{{ $data['firma'] }}" alt="" style="width: 200px; position: absolute; top: -50px; left: 260px;">
            <br>
            {{$data['profesor']->usuario_nombres}} {{$data['profesor']->usuario_app}}
            {{$data['profesor']->usuario_apm}}
            <br>
            <strong>Profesor de {{$data['materia']}}</strong>
        </footer>
    </div> <!-- Fin del Container -->
</body>