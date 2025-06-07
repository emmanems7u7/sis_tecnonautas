@extends('layouts.argon')


@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb"
                        style="background-color: #f8f9fa; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                style="color: #007bff; text-decoration: none;">Inicio</a>
                        </li>

                        <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Lista de
                            {{ $texto }}
                        </li>
                    </ol>
                </nav>
                <h1 class="display-6 mb-3 text-white">
                    <i class="bi bi-person-lines-fill "></i> Lista de {{ $texto }}
                </h1>

                <div class="bg-white border shadow-sm p-3 mt-4">

                    <form method="GET" action="{{ route('studiantes.index', ['tipo' => $tipo]) }}"
                        class="mb-3 d-flex justify-content-end">
                        <input type="text" name="buscar" class="form-control w-25 me-2" placeholder="Buscar..."
                            value="{{ request('buscar') }}">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>


                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabla-estudiantes">

                            @if($tipo == 1)
                                <thead class="table-primary">
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>Email</th>
                                        <th>Materia y módulo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($e as $usuario)
                                        @foreach($usuario->asignacionesEstudiante as $asig)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <img src="{{ $usuario->fotoperfil ? asset('storage/' . $usuario->fotoperfil) : asset('imagenes/tecnonautas.png') }}"
                                                        alt="Foto de perfil" class="rounded-circle mx-auto d-block"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                </td>
                                                <td>{{ $usuario->name }}</td>
                                                <td>{{ $usuario->apepat }} {{ $usuario->apemat }}</td>
                                                <td>{{ $usuario->email }}</td>
                                                <td><small>{{ $asig->materia }} | {{ $asig->modulo }}</small></td>
                                                <td>
                                                    @if($asig->activo === 'activo')
                                                        <span class="badge bg-success">Activo</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactivo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning">Reestablecer Contraseña</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            @else
                                <thead class="table-primary">
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>Email</th>

                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($e as $usuario)

                                        <tr>
                                            <td class="text-center align-middle">
                                                <img src="{{ $usuario->fotoperfil ? asset('storage/' . $usuario->fotoperfil) : asset('imagenes/tecnonautas.png') }}"
                                                    alt="Foto de perfil" class="rounded-circle mx-auto d-block"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td>{{ $usuario->name }}</td>
                                            <td>{{ $usuario->apepat }} {{ $usuario->apemat }}</td>
                                            <td>{{ $usuario->email }}</td>

                                            <td>
                                                @role('admin')
                                                <a href="{{ route('reestablecer.contraseña', ['id' => $usuario->id]) }}"
                                                    class="btn btn-sm btn-warning">Reestablecer Contraseña</a>
                                                <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                                                @endrole
                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                        {{-- Paginador --}}
                        <div class="d-flex justify-content-end">
                            {{ $e->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.getElementById('buscador').addEventListener('keyup', function () {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tabla-estudiantes tbody tr');

            filas.forEach(fila => {
                const textoFila = fila.innerText.toLowerCase();
                fila.style.display = textoFila.includes(filtro) ? '' : 'none';
            });
        });
    </script>

@endsection