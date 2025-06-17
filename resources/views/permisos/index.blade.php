@extends('layouts.argon')

@section('content')
    @include('permisos.create')
    @include('permisos.edit')




    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <p>Permisos</p>
            <div class="row mt-3">
                <div class="col">


                    <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearPermiso">Crear
                        Nuevo Permiso</a>

                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('permissions.index') }}" class="mb-3 d-flex justify-content-end">
                        <input type="text" name="search" class="form-control  me-2" placeholder="Buscar permiso..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i>
                            Buscar</button>
                    </form>
                </div>
            </div>

        </div>

    </div>


    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <p>Permisos Disponibles</p>
            <div class="row mt-3">
                @foreach ($cat_permisos as $modulo)
                    <div class="col-md-2">
                        <form method="GET" action="{{ route('permissions.index') }}">
                            <input type="hidden" name="search" value="{{ $modulo }}.">
                            <button type="submit" class="btn btn-sm bg-gradient-info w-100">
                                {{ ucfirst($modulo) }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row mt-3">
        @forelse($permissions as $permiso)
            <div class="col-md-4">
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-key text-primary"></i> {{ $permiso->name }}
                        </h5>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-info" onclick="editarPermiso({{ $permiso->id }})">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <form action="{{ route('permissions.destroy', $permiso->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('¿Seguro que deseas eliminar este permiso?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">No hay permisos que coincidan.</div>
            </div>
        @endforelse
    </div>
    <style>
        @media (max-width: 576px) {
            .pagination .page-item {
                margin: 2px 3px;
            }

            .pagination .page-link {
                padding: 4px 8px;
                font-size: 13px;
            }
        }
    </style>
    {{-- Paginación --}}
    @php
        $current = $permissions->currentPage();
        $last = $permissions->lastPage();
    @endphp

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center flex-wrap">

            {{-- Botón Anterior --}}
            <li class="page-item {{ $permissions->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $permissions->previousPageUrl() }}" aria-label="Anterior">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>

            {{-- Página 1 --}}
            @if($current > 2)
                <li class="page-item"><a class="page-link" href="{{ $permissions->url(1) }}">1</a></li>
            @endif

            {{-- ... anterior --}}
            @if($current > 3)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            {{-- Página actual y vecinas --}}
            @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $permissions->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- ... siguiente --}}
            @if($current < $last - 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            {{-- Última página --}}
            @if($current < $last - 1)
                <li class="page-item"><a class="page-link" href="{{ $permissions->url($last) }}">{{ $last }}</a></li>
            @endif

            {{-- Botón Siguiente --}}
            <li class="page-item {{ $permissions->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $permissions->nextPageUrl() }}" aria-label="Siguiente">
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </nav>







@endsection