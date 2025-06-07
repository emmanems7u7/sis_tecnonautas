@extends('layouts.argon')


@section('content')


    <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
        <div class="row pt-2">
            <div class="col ps-4">
                <h1 class="display-6 mb-3 text-white">
                    <i class="bi bi-person-lines-fill "></i> Lista de estudiantes inactivos
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lista de estudiantes inactivos</li>
                    </ol>
                    <div class="bg-white border shadow-sm p-3 mt-4">
                        <table class="table table-responsive">
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
                                        <th scope="row">{{$student->ci}}</th>
                                        <td>
                                            @if (isset($student->fotoperfil))
                                                <img src="{{asset('/storage' . $student->fotoperfil)}}" class="rounded"
                                                    alt="Profile picture" height="30" width="30">
                                            @else
                                                <i class="bi bi-person-square"></i>
                                            @endif
                                        </td>
                                        <td>{{$student->name}}</td>
                                        <td>{{$student->apepat}}</td>
                                        <td>{{$student->apemat}}</td>
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


@endsection