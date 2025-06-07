@extends('layouts.argon')


@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="display-6 mb-3 text-white">
                    <i class="bi bi-person-lines-fill "></i> Detalle de estudiante
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb"
                        style="background-color: #f8f9fa; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                style="color: #007bff; text-decoration: none;">Inicio</a>
                        </li>

                        <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Detalle
                        </li>
                    </ol>
                </nav>



                <div class="profile-header text-center p-4 bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="profile-picture mr-3">
                            <img src="{{ asset('/storage' . $usuario->fotoperfil) }}" alt="Foto de perfil"
                                class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                        </div>
                        <div>
                            <h2 class="mb-1">{{ $usuario->name }} {{ $usuario->apepat }} {{ $usuario->apemat }}</h2>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>

                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Tareas
                                </div>
                                <div class="card-body">
                                    @foreach ($tareasEstudiantes as $tarea)
                                        <div class="col-md-12 mb-4">
                                            <div class="card mt-4 shadow-sm">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <h5 class="card-title font-weight-bold">
                                                                Tarea: {{ $tarea->nombre }}
                                                            </h5>
                                                            <p class="card-text">Descripcion: {{ $tarea->detalle }}</p>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="text-right">
                                                                <p>
                                                                    <strong>Nota:</strong>
                                                                    <span
                                                                        style="color: {{ $tarea->nota > 51 ? 'green' : 'red' }}">
                                                                        {{ $tarea->nota }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            <p class="text-right "><small><strong>Entregado:</strong>
                                                                    {{ \Carbon\Carbon::parse($tarea->entregado)->format('d/m/Y H:i') }}</small>
                                                            </p>
                                                            <p class="text-right "><small><strong>Límite:</strong>
                                                                    {{ \Carbon\Carbon::parse($tarea->limite)->format('d/m/Y H:i') }}</small>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    Evaluaciones
                                </div>
                                <div class="card-body">
                                    @foreach($detalles as $detalle)
                                        <div class="card mt-4">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h5 class="card-title">{{$detalle->nombre}}
                                                            @if($detalle->completado == 'si')
                                                                <i class="fas fa-check text-success"></i>
                                                            @else
                                                                <i class="fas fa-times text-danger"></i>
                                                            @endif
                                                        </h5>
                                                        <p class="card-text">{{$detalle->detalle}}</p>
                                                        <a href="{{route('evaluacion.revision', ['id' => $detalle->id_u, 'id_e' => $detalle->id_e])}}"
                                                            class="btn btn-light">Revision</a>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="text-right">
                                                            <p>
                                                                <strong>Nota:</strong>
                                                                <span
                                                                    style="color: {{ $detalle->nota > 51 ? 'green' : 'red' }}">
                                                                    {{ $detalle->nota }}
                                                                </span>
                                                            </p>

                                                        </div>

                                                        <p class="text-right"><small>Fecha creado: {{$detalle->creado}}</small>
                                                        </p>
                                                        <p class="text-right"><small>Fecha limite: {{$detalle->limite}}</small>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <script src="https://kit.fontawesome.com/a076d05399.js"></script>



@endsection