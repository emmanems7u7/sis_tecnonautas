@extends('layouts.argon')

@section('content')

    <div class="alert alert-info text-justify shadow-lg rounded py-4">
        <h5 class="fw-bold mb-3">
            ¡Bienvenido a la administración de <span class="text-warning">apoderados o tutores</span>!
        </h5>

        <p class="mb-3">
            ¡Hola! Bienvenido(a) a tu portal educativo. Aquí encontrarás todo lo que necesitas para participar en
            tus clases.
        </p>

        <p class="mb-3">
            Es muy importante que completes la información que aparece aquí para que podamos mantenernos en contacto
            contigo y tus papás o tutores.
        </p>

        <div class="mt-4 text-center">
            <button type="button" class="btn btn-success btn-lg shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalAgregarApoderado">
                <i class="fas fa-user-plus"></i> Agregar Apoderado
            </button>
        </div>
    </div>

    @include('apoderados.create')
    @yield('create')
    <div class="container-fluid">

        <div class="card">
            <h1 class="text-black text-center py-4">Lista de Apoderados</h1>

        </div>




        @include('apoderados.cardA')
        @yield('cardA')



    </div>

@endsection