@extends('layouts.argon')


@section('content')

    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            background-color: #fff;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            height: 300px;
            object-fit: cover;
        }

        .card-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .card-text-p {
            font-size: 20px;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .container {
            max-width: 800px;
        }

        .student-img {
            width: 150px;
            /* Tamaño deseado de la imagen */
            height: 150px;
            /* Tamaño deseado de la imagen */
            border-radius: 50%;
            /* Hace que la imagen sea circular */
            object-fit: cover;
            /* Para asegurar que la imagen se ajuste bien en el círculo */
        }
    </style>



    <nav aria-label="breadcrumb">
        <ol class="breadcrumb"
            style="background-color: #f8f9fa; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"
                    style="color: #007bff; text-decoration: none;">Inicio</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Perfil</li>
        </ol>
    </nav>


    <div class="container-fluid mt-5">
        <div class="row">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <img src="{{ asset($estudiante->fotoperfil) }}"
                            class="card-img-top mx-auto d-block student-img mt-3" alt="Foto del Estudiante">
                        <div class="card-body">
                            <h5 class="card-title">Datos del Estudiante</h5>
                            <div class=" justify-content-between">
                                <p class="card-text-p"><strong>Nombre:</strong> {{$estudiante->usuario_nombres}}
                                    {{$estudiante->usuario_app}} {{$estudiante->usuario_apm}}
                                </p>
                                <p class="card-text-p"><strong>fecha de Nacimiento:</strong> {{$estudiante->fechanac}}</p>

                                <p class="card-text-p"><strong>Edad:</strong> {{$edad}}</p>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('editar.perfil') }}" class="btn btn-info">Editar</a>
                            </div>
                            <!-- Otros datos del estudiante -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Otros Datos del Estudiante</h5>
                            <dl class="row">
                                <dt class="col-sm-5">Correo Electrónico:</dt>
                                <dd class="col-sm-7">{{$estudiante->email}}</dd>

                                <dt class="col-sm-4">Dirección:</dt>
                                <dd class="col-sm-8">{{$estudiante->direccion}}</dd>

                                <!-- Agrega más campos de datos según sea necesario -->
                            </dl>
                        </div>
                    </div>


                </div>
            </div>



            <div class="row">

                @include('apoderados.cardA')
                @yield('cardA')
            </div>



        </div>
    </div>

@endsection