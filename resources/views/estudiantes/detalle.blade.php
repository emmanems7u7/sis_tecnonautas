@extends('layouts.argon')


@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="display-6 mb-3 text-white">
                    <i class="bi bi-person-lines-fill "></i> Detalle de estudiante
                </h1>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-picture mr-3">


                                    @if ($usuario->fotoperfil)
                                        <img src="{{ asset($usuario->fotoperfil) }}" alt="Foto de perfil"
                                            class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                                    @else
                                        <img src="{{ asset('update/imagenes/user.jpg') }}" alt="Foto de perfil"
                                            class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">

                                <div>
                                    <h2 class="mb-1">{{ $usuario->usuario_nombres }} {{ $usuario->usuario_app }}
                                        {{ $usuario->usuario_apm }}
                                    </h2>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        @php
            $estudiante = $data['estudiante'];
            $tareas = $data['tareas'];
            $evaluaciones = $data['evaluaciones'];
        @endphp

        <div class="container-fluid mt-4">


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Tareas
                        </div>
                        <div class="card-body">
                            @foreach ($tareas as $tarea)
                                @php
                                    // Buscar si el estudiante tiene esa tarea y su nota
                                    $tareaEstudiante = collect($estudiante['tareas'])->firstWhere('tareas_id', $tarea->id);
                                    $nota = $tareaEstudiante ? $tareaEstudiante['nota'] : 0;
                                @endphp

                                <div class="col-md-12 mb-4">
                                    <div class="card mt-4 shadow-sm">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-8">
                                                    <h5 class="card-title font-weight-bold">
                                                        Tarea: {{ $tarea->nombre }}
                                                    </h5>
                                                    <p class="card-text">Descripción: {{ $tarea->detalle }}</p>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-right">
                                                        <p>
                                                            <strong>Nota:</strong>
                                                            <span style="color: {{ $nota >= 51 ? 'green' : 'red' }}">
                                                                {{ $nota }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <p class="text-right"><small><strong>Límite:</strong>
                                                            {{ \Carbon\Carbon::parse($tarea->limite)->format('d/m/Y H:i') }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if(count($tareas) === 0)
                                <p>No hay tareas registradas.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            Evaluaciones
                        </div>
                        <div class="card-body">
                            @foreach ($evaluaciones as $evaluacion)
                                @php
                                    // Buscar la nota del estudiante para esta evaluacion
                                    $evaluacionEstudiante = collect($estudiante['evaluaciones'])->firstWhere('id_e', $evaluacion->id);
                                    $notaEval = $evaluacionEstudiante ? $evaluacionEstudiante['nota'] : 0;
                                    $completado = ($notaEval > 0);
                                @endphp

                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="card-title">
                                                    {{ $evaluacion->nombre }}
                                                    @if ($completado)
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-danger"></i>
                                                    @endif
                                                </h5>
                                                <p class="card-text">{{ $evaluacion->detalle }}</p>
                                                <a href="{{ route('evaluacion.revision', ['id' => $estudiante['user_id'], 'id_e' => $evaluacion->id, 'id_a' => $id_a, 'id_m' => $id_m, 'id_p' => $id_p]) }}"
                                                    class="btn btn-light">Revisión</a>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-right">
                                                    <p>
                                                        <strong>Nota:</strong>
                                                        <span style="color: {{ $notaEval >= 51 ? 'green' : 'red' }}">
                                                            {{ $notaEval }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <p class="text-right"><small>Fecha creado:
                                                        {{ \Carbon\Carbon::parse($evaluacion->creado)->format('d/m/Y H:i') }}</small>
                                                </p>
                                                <p class="text-right"><small>Fecha límite:
                                                        {{ \Carbon\Carbon::parse($evaluacion->limite)->format('d/m/Y H:i') }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if(count($evaluaciones) === 0)
                                <p>No hay evaluaciones registradas.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>



@endsection