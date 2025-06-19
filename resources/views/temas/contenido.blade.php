@section('contenido')
    <style>
        .card-c {
            background-color: #007bff;
            color: white;
        }
    </style>
    <div class="card">
        <div class="card-header"><i class="fas fa-book check-icon"></i> Contenido del Módulo

        </div>
        <div class="card-body">

            <div class=" accordion" id="accordionExample">

                @if($temas !== null)
                    @foreach($temas as $index => $tema)
                        <div class="card">
                            <div class="card-header card-c" id="heading{{ $index }}"
                                style=" background-color: #3c6773; height: 72px;">
                                <h5 class="mb-0">
                                    <button class="text-white btn btn-link" data-toggle="collapse"
                                        data-target="#collapse{{ $index }}">
                                        {{ $tema['nombre'] }}

                                    </button>
                                </h5>

                            </div>

                            <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('profesor'))
                                        <form action="{{ route('eliminar.tema', ['id' => $tema->id]) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Eliminar {{ $tema['nombre'] }}
                                            </button>
                                        </form>
                                    @endif
                                    @foreach($contenidos as $contenido)
                                        @if($contenido->id_t == $tema->id)
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            @if($contenido->tipo === 'documento')
                                                                <i class="fas fa-file fondo-aleatorio-{{ rand(1, 3) }}"></i>
                                                                <a href="{{ route('descargar.documento', ['id' => $contenido->id]) }}"
                                                                    target="_blank">{{ $contenido->nombre }}</a>
                                                            @elseif($contenido->tipo === 'video')
                                                                <i class="fas fa-video fondo-aleatorio-{{ rand(1, 3) }}"></i>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#modalVideo{{ $contenido->id }}">
                                                                    {{ $contenido->nombre }}
                                                                </a>

                                                                <div class="modal fade" id="modalVideo{{ $contenido->id }}" tabindex="-1"
                                                                    aria-labelledby="modalVideoLabel{{ $contenido->id }}" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                        <div
                                                                            class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="modalVideoLabel{{ $contenido->id }}">
                                                                                    {{ $contenido->nombre }}
                                                                                </h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                    aria-label="Cerrar"></button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <video controls style="width: 100%; max-height: 500px;">
                                                                                    <source
                                                                                        src="{{ route('descargar.video', ['id' => $contenido->id]) }}"
                                                                                        type="video/mp4">
                                                                                    Tu navegador no admite la reproducción de videos.
                                                                                </video>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @elseif($contenido->tipo === 'enlace')
                                                                <i class="fas fa-link"></i>
                                                                <a href=" {{ $contenido->ruta }}" target="_blank">{{ $contenido->nombre }}</a>


                                                            @endif

                                                        </div>
                                                        <div class="col-md-4">
                                                            @can('contenido._tema_eliminar')
                                                                <form action="{{ route('eliminar.contenido', ['id' => $contenido->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm ml-2">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    <br>

                                    @can('contenido._tema_crear')
                                        <button type="button" class="btn btn-primary" onclick="openContenidoModal({{ $tema->id }})">
                                            {{ __('Subir Contenido') }}
                                        </button>
                                    @endcan

                                </div>
                            </div>
                        </div>

                    @endforeach
                @endif

                @can('modulos.temas_guardar')
                    <div class="card" style="height: 72px;">
                        <div class="card-header card-c  bg-info" id="">
                            <h5 class="mb-0">
                                <a class="text-white " data-toggle="collapse" onclick="openModal()">
                                    Crear Tema
                                </a>
                            </h5>
                        </div>

                    </div>
                @endcan


            </div>


        </div>



    </div>


@endsection