@section('tareas')


    <div class="card">
        <div class="card-header">
            <i class="far fa-clipboard cross-icon"></i> Tareas
        </div>
        <div class="card-body">
            <div class="alert alert-info text-white shadow-sm" role="alert">
                <h5 class="alert-heading"><i class="fas fa-clipboard-list"></i> Estado de las tareas</h5>
                @role('admin|profesor')


                <p><i class="far fa-check-circle check-icon" style="color: green;"></i> <strong>Publicado</strong> </p>
                <p><i class="far fa-times-circle cross-icon" style="color: red;"></i> <strong>No publicado</strong></p>

                @endrole
                @role('estudiante')

                <p><i class="fas fa-bell " style="color: #ffc107;"></i> <strong>Con tiempo para entregar</strong> </p>
                <p><i class="fas fa-clock" style="color: orange;"></i> <strong>Fecha de entrega cercana</strong></p>
                <p><i class="far fa-check-circle check-icon" style="color: green;"></i> <strong>Entregado</strong> </p>
                <p><i class="far fa-times-circle cross-icon" style="color: red;"></i> <strong>No Entregado</strong></p>

                @endrole
            </div>
            @forelse ($tareas as $tarea)
                @role('admin|profesor|Demo')

                <div class="card">
                    <div class="card-body">
                        <p><i class="far fa-check-circle check-icon"></i> {{$tarea->nombre}}</p>
                    </div>
                </div>

                @endrole

                @role('estudiante')

                @php
                    $tareaEntregada = $tareasE->firstWhere('tareas_id', $tarea->id);
                @endphp

                @if ($tareaEntregada && $tareaEntregada->nota >= 0)
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <p><i class="far fa-check-circle check-icon"></i> {{ $tarea->nombre }}</p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTarea{{ $tarea->id }}"
                                        aria-expanded="false" aria-controls="collapseTarea{{ $tarea->id }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="collapseTarea{{ $tarea->id }}" class="collapse">
                                <div class="mt-3">

                                    <p><strong>Límite de entrega:</strong>
                                        {{ \Carbon\Carbon::parse($tarea->limite)->translatedFormat('d \d\e F \d\e Y \a \l\a\s H:i') }}
                                    </p>

                                    <p><strong>Detalle:</strong> {{ $tarea->detalle }}</p>

                                </div>
                            </div>
                        </div>
                    </div>


                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">

                                <div class="col-md-8">
                                    <p>
                                        @if (now()->greaterThan($tarea->limite))
                                            <i class="far fa-times-circle cross-icon" style="color: red;"></i>
                                        @elseif (now()->addHour()->greaterThanOrEqualTo($tarea->limite))
                                            <i class="fas fa-clock" style="color: orange;"></i>
                                        @else
                                            <i class="fas fa-bell" style="color: #ffc107;"></i>
                                        @endif
                                        {{ $tarea->nombre }}
                                    </p>
                                </div>

                                <div class="col-md-4 text-right">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTarea{{ $tarea->id }}"
                                        aria-expanded="false" aria-controls="collapseTarea{{ $tarea->id }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                                <!-- Columna del botón "Entregar" -->
                                <div class="col-md-6 text-right">
                                    @if (!now()->addHour()->greaterThanOrEqualTo($tarea->limite))
                                        <button type="button" class="btn btn-info"
                                            onclick="mostrarModal('{{ $tarea->id }}', '{{ $tarea->nombre }}')">Entregar</button>
                                    @else
                                        <button type="button" class="btn btn-dark" disabled>No Entregado</button>

                                    @endif
                                </div>
                            </div>

                            <div id="collapseTarea{{ $tarea->id }}" class="collapse">
                                <div class="mt-3">
                                    <p><strong>Límite de entrega:</strong>
                                        {{ \Carbon\Carbon::parse($tarea->limite)->translatedFormat('d \d\e F \d\e Y \a \l\a\s H:i') }}
                                    </p>

                                    <p><strong>Detalle:</strong> {{ $tarea->detalle }}</p>

                                </div>
                            </div>
                        </div>

                    </div>

                @endif
                @endrole

            @empty

                <div class="card-body">
                    <p>No hay tareas disponibles.</p>

                </div>

            @endforelse

            <div class="conten d-flex justify-content-center">
                {{ $tareas->links('pagination::bootstrap-4') }}
            </div>
            @can('tarea.crear')
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTareaModal">
                    Crear Tarea
                </button>
            @endcan


            @can('tarea.revisar')

                <a href="{{route('tareas.showP', $id_pm)}}" class="btn btn btn-info">revisar tareas</a>

            @endcan

            @can('tarea.revisar_estudiantes')
                <a href="{{route('tareas.showE', $id_pm)}}" class="btn btn btn-info">Ver tareas enviadas</a>
            @endcan
        </div>
    </div>

    <div id="modalContainer"></div>
    <script>
        function mostrarModal(tareaId, nombre) {
            // Obtener el contenedor modal
            var modalContainer = document.getElementById('modalContainer');

            // Verificar si el contenedor existe
            if (!modalContainer) {
                console.error('El contenedor modalContainer no se encontró en el DOM.');
                return;
            }

            // Crear el HTML del modal
            var modalHTML = `
                                                                                                                                                                                                                                                                                                <div class="modal fade" id="tareaModal${tareaId}" tabindex="-1" aria-labelledby="tareaModalLabel${tareaId}" aria-hidden="true">
                                                                                                                                                                                                                                                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                                                                                                                                                                                                                                                        <div class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                                                                                                                                                                                                                                                                                                            <div class="modal-header">
                                                                                                                                                                                                                                                                                                            <h5 class="modal-title" id="tareaModalLabel${tareaId}">${nombre}</h5>


                                                                                                                                                                                                                                                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                            <form action="{{ route('tareas.store.estudiante') }}" method="POST" enctype="multipart/form-data">
                                                                                                                                                                                                                                                                                                                @csrf
                                                                                                                                                                                                                                                                                                                <div class="modal-body">
                                                                                                                                                                                                                                                                                                                    <div class="mb-3">
                                                                                                                                                                                                                                                                                                                        <input type="hidden" name="tarea_id" value="${tareaId}">
                                                                                                                                                                                                                                                                                                                        <label for="archivo" class="form-label">Subir Archivo</label>
                                                                                                                                                                                                                                                                                                                        <input type="file" class="form-control" id="archivo" name="archivo" required>
                                                                                                                                                                                                                                                                                                                    </div>

                                                                                                                                                                                                                                                                                                                    <div class="mb-3">
                                                                                                                                                                                                                                                                                                                        <label for="comentario" class="form-label">Comentario</label>
                                                                                                                                                                                                                                                                                                                        <input type="text" class="form-control" id="comentario" name="comentario" placeholder="Ingresa un comentario (Opcional)">
                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                <div class="modal-footer">
                                                                                                                                                                                                                                                                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                                                                                                                                                                                                                                                                    <button type="submit" class="btn btn-primary">Subir Archivo</button>
                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                            </form>
                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            `;

            // Asignar el HTML al contenedor modal
            modalContainer.innerHTML = modalHTML;

            // Mostrar el modal usando Bootstrap
            var modal = new bootstrap.Modal(document.getElementById(`tareaModal${tareaId}`));
            modal.show();
        }

    </script>
    <!-- Modal crear tareaa-->
    <div class="modal fade" id="createTareaModal" tabindex="-1" aria-labelledby="createTareaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div
                class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTareaModalLabel">Crear Tarea</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" maxlength="200" required>
                        </div>
                        <div class="form-group">
                            <label for="detalle">Detalle</label>
                            <small class="form-text text-muted">Añade una descripcion sobre esta tarea</small>
                            <textarea class="form-control" id="detalle" name="detalle" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ruta_archivo">Archivo</label>
                            <input type="file" class="form-control" id="ruta_archivo" name="ruta_archivo">
                            <small class="form-text text-muted">Acá puedes subir un archivo de ejemplo para que los
                                estudiantes tengan una idea. No es obligatorio que lo subas</small>
                        </div>
                        <div class="form-group">
                            <label for="limite">Fecha Límite de entrega</label>
                            <input type="datetime-local" class="form-control" id="limite" name="limite" required>
                        </div>


                        <input type="hidden" class="form-control" id="id_pm" name="id_pm" value="{{$id_pm}}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection