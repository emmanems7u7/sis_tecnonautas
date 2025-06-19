@extends('layouts.argon')

@section('content')


    <script src="{{ asset('js/app.js') }}"></script>

    <div class="card card-frame card-profile-bottom">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    Acciones
                </div>

            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="col">

                        @can('usuarios.crear')
                            <a class="btn btn-primary mb-3" href="{{ route('users.create') }}">Crear Usuario</a>
                        @endcan

                        @can('usuarios.exportar_excel')
                            <a href="{{ route('usuarios.exportar_excel') }}" class="btn btn-success mb-3">Exportar a Excel</a>
                        @endcan
                        @can('usuarios.exportar_pdf')
                            <a href="{{ route('usuarios.exportar_pdf') }}" class="btn btn-success mb-3" target="_blank">Exportar
                                a PDF</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="table-responsive">
            @include('usuarios.tabla_usuarios', ['usuarios' => $users])
        </div>
        <div class="d-flex justify-content-center">
            <nav>
                <ul class="pagination">
                    <!-- Página Anterior -->
                    @if ($users->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-label="Previous">
                                <i class="fa fa-angle-left"></i>
                                <span class="sr-only">Previous</span>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                <i class="fa fa-angle-left"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                    @endif

                    <!-- Páginas Numeradas -->
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Página Siguiente -->
                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                                <i class="fa fa-angle-right"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link" aria-label="Next">
                                <i class="fa fa-angle-right"></i>
                                <span class="sr-only">Next</span>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>

    </div>


@endsection