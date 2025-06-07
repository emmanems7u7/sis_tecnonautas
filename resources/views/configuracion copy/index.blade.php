@extends('layouts.argon')


@section('content')

    <style>
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 20px 20px 0 0;
            font-size: 1.5rem;
            padding: 20px;
        }

        .section-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .list-group-item {
            background-color: transparent !important;
            border: none;
            border-radius: 10px;
            color: #495057;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .list-group-item:hover {
            background-color: #f1f1f1 !important;
        }

        .list-group-item i {
            font-size: 1.25rem;
            margin-right: 10px;
        }
    </style>




    <nav aria-label="breadcrumb">
        <ol class="breadcrumb"
            style="background-color: #f8f9fa; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">

            <li class="breadcrumb-item"><a href="{{ route('home') }}">inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Configuracion</li>
        </ol>
    </nav>
    <h1 class="text-center  mb-4"
        style="background: linear-gradient(to right, #4e73df, #224abe); color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 100%; margin-left: auto; margin-right: auto;">
        Configuración
    </h1>


    @role('admin')
    @include('configuracion.vistaAdmin')
    @yield('adminConf')
    @endrole

@endsection