@extends('layouts.argon')


@section('content')


    <div class="card">
        <div class="card-body">

            <i class="fas fa-students"></i> Lista de Estudiantes
        </div>
        <form method="GET" action="{{ route('estudiantes.index') }}" class="mb-3 d-flex justify-content-end">
            <input type="text" name="buscar" class="form-control w-25 me-2" placeholder="Buscar..."
                value="{{ request('buscar') }}">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                @include('usuarios.tabla_usuarios')

                <div class="d-flex justify-content-end">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

@endsection