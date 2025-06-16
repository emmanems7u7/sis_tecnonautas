@extends('layouts.argon')


@section('content')


    <div class="card">
        <div class="card-body">
            <h1 class="display-6 mb-3 text-dark">
                <i class="fas fa-users"></i> Lista de estudiantes inactivos
            </h1>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">CI</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido paterno</th>
                            <th scope="col">Apellido materno</th>
                            <th scope="col">Email</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($e as $student)
                            <tr>
                                <th>{{$student->ci ?? 'No registrado'}}</th>
                                <td>
                                    @if (isset($student->fotoperfil))
                                        <img src="{{asset($student->fotoperfil)}}" class="rounded" alt="Profile picture"
                                            height="30" width="30">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </td>
                                <td>{{$student->usuario_nombres}}</td>
                                <td>{{$student->usuario_app}}</td>
                                <td>{{$student->usuario_apm}}</td>
                                <td>{{$student->email}}</td>
                                <td class="bg-danger text-white">{{$student->activo}}</td>

                                <td>

                                    <div class="btn-group" role="group">
                                        <a href="{{route('cambiarestado', ['id' => $student->id, 'id_noti' => $id_noti])}}"
                                            role="button" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i>
                                            Cambiar estado</a>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection