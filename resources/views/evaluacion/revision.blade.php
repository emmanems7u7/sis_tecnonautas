@extends('layouts.argon')

@section('content')

    <div class="container-fluid">
        <h5>Evaluación: {{ $evaluacion->nombre }}</h5>

        @foreach ($evaluacion->preguntas as $pregunta)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Pregunta: {{ $pregunta->texto }}</h5>
                    <ul class="list-group">
                        @foreach ($pregunta->opciones as $opcion)
                            <li class="list-group-item">Opción: {{ $opcion->texto }}
                                @if ($opcion->correcta === 1)
                                    <span class="text-success">(Correcta) </span>
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
                                <a href="{{route('respuestaparrafo.correcta', ['id_u' => $id_u, 'id_p' => $pregunta->id, 'id_e' => $id_e])}}"
                                    class="btn btn-primary">correcta</a>
                                <a href="{{route('respuestaparrafo.incorrecta', ['id_u' => $id_u, 'id_p' => $pregunta->id])}}"
                                    class="btn btn-danger">incorrecta</a>

                                <p>Respuesta del estudiante: {{ $respuesta->contenido }}</p>
                            @elseif ($pregunta->tipo === 'casillas')
                                @if ($respuesta->correcta == 0)
                                    <p>Respuesta incorrecta</p>
                                @elseif ($respuesta->correcta == 1)
                                    <p>Respuesta correcta</p>
                                @elseif ($respuesta->correcta == 2)
                                    <p>Escogió solo una respuesta correcta</p>
                                    <p> Respuesta seleccionada {{$respuesta->contenido}}</p>
                                @endif
                            @elseif ($pregunta->tipo === 'opciones')
                                @if ($respuesta->correcta == 0)
                                    <p>Respuesta incorrecta</p>
                                @elseif ($respuesta->correcta == 1)
                                    <p>Respuesta correcta</p>
                                @endif
                                <p>Respuesta seleccionada: {{ $respuesta->opcion->texto }}
                                    @if ($respuesta->correcta == 1)
                                        (Correcta)
                                    @else
                                        (Incorrecta)
                                    @endif
                                </p>
                            @endif
                        </div>
                    @else
                        <p>No respondida</p>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
@endsection