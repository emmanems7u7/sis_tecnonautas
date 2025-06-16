@extends('layouts.argon')


@section('content')



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .custom-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .custom-card:hover {
            transform: scale(1.05);
        }
    </style>
    <style>
        .custom-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            position: relative;
        }

        .custom-card:hover {
            transform: scale(1.05);
        }

        .card-actions {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
    </style>



    <div class="alert alert-info shadow-sm text-white" role="alert">
        <div class="row align-items-center">
            <!-- Columna de imagen -->
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <img src="{{ asset('imagenes/tecnonautas.png') }}" alt="Revisión de examen" class="img-fluid"
                    style="max-width: 150px;">
            </div>

            <!-- Columna de contenido -->
            <div class="col-md-9 text-justify">
                <h4 class="alert-heading">
                    <i class="fas fa-file-alt"></i> Examen Finalizado
                </h4>

                <p>
                    Ya completaste este examen. A continuación puedes ver tu <strong>calificación final</strong>, junto con
                    un resumen de todas las preguntas, tus respuestas seleccionadas y cuáles eran las correctas.
                </p>

                <p>
                    Esta revisión te ayudará a identificar tus fortalezas y las áreas que puedes mejorar. ¡Felicitaciones
                    por llegar hasta aquí!
                </p>



            </div>
        </div>
    </div>



    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white py-3">
                    <div class="row">
                        <div class="col">
                            <h1 class="mb-0">{{ $evaluacion->nombre }}</h1>
                            <p class="lead">Resultado de la evaluación</p>
                        </div>
                        <div class="col text-right">
                            <h3 class="mb-0">Nota: {{$nota}}</h3>
                            <p class="font-italic">Puntuación obtenida</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @foreach ($evaluacion->preguntas as $pregunta)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Pregunta: {{ $pregunta->texto }}</h5>
                                <ul class="list-group">
                                    @foreach ($pregunta->opciones as $opcion)
                                        <li class="list-group-item">
                                            <span class="mr-2">Opción: {{ $opcion->texto }}</span>
                                            @if ($opcion->correcta === 1)
                                                <span class="badge badge-success">Correcta</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @php
                                    $respuesta = $respuestas->where('pregunta_id', $pregunta->id)->first();
                                @endphp
                                @if ($respuesta)
                                    <div class="mt-3">
                                        @if ($pregunta->tipo === 'parrafo')
                                            <p class="font-weight-bold">Respuesta del estudiante:</p>
                                            <p>{{ $respuesta->contenido }}</p>
                                        @elseif ($pregunta->tipo === 'casillas')
                                            @if ($respuesta->correcta == 0)
                                                <p>Respuesta incorrecta</p>
                                            @elseif ($respuesta->correcta == 1)
                                                <p>Respuestas correctas</p>
                                            @elseif ($respuesta->correcta == 2)
                                                <p>Escogió solo una respuesta correcta</p>
                                                <p>Respuesta seleccionada: {{$respuesta->contenido}}</p>
                                            @endif
                                        @elseif ($pregunta->tipo === 'opciones')
                                            @if ($respuesta->correcta == 0)
                                                <p>Respuesta incorrecta</p>
                                            @elseif ($respuesta->correcta == 1)
                                                <p>Respuesta correcta</p>
                                            @endif
                                            <p>Respuesta seleccionada: {{ $respuesta->opcion->texto }}
                                                @if ($respuesta->correcta == 1)
                                                    <span class="badge badge-success">Correcta</span>
                                                @else
                                                    <span class="badge badge-danger">Incorrecta</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <p class="font-weight-bold">No respondida</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>






@endsection