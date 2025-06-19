@section('examenes')
    <!-- Columna 2: Lista de exámenes y tareas para Niños -->

    <!-- Primera fila: Lista de exámenes -->

    <script>
        function actualizarEnlace(evaluacion_id, nota) {


            var enlace = document.getElementById('enlace_evaluacion_' + evaluacion_id);
            enlace.href = "{{ route('listarExamen', ['id_e' => 'id_e_', 'nota' => 'nota__']) }}".replace('id_e_', evaluacion_id).replace('nota__', nota);
        }

        function actualizarEnlaceN() {

            var enlace = document.getElementById('enlace_evaluacion_' + evaluacion_id);
            enlace.href = "{{ route('listarExamen', ['id_e' => 'id_e_', 'nota' => 'nota__']) }}".replace('id_e_', evaluacion_id).replace('nota__', nota);
        }

    </script>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-clipboard-check check-icon"></i> Exámenes
        </div>
        <div class="card-body">
            <div class="alert alert-info text-white shadow-sm" role="alert">
                @role('admin|profesor')

                <h5 class="alert-heading"><i class="fas fa-clipboard-list"></i> Estado de los Exámenes</h5>
                <p><i class="far fa-check-circle check-icon" style="color: green;"></i> <strong>Publicado</strong> </p>
                <p><i class="far fa-times-circle cross-icon" style="color: red;"></i> <strong>No publicado</strong></p>

                @endrole
                @role('estudiante')

                <h5 class="alert-heading"><i class="fas fa-clipboard-list"></i> Estado de los Exámenes</h5>
                <p><i class="fas fa-bell " style="color: #ffc107;"></i> <strong>Con tiempo para entregar</strong> </p>

                <p><i class="fas fa-clock" style="color: orange;"></i> <strong>Fecha de entrega cercana</strong></p>
                <p><i class="far fa-check-circle check-icon" style="color: green;"></i> <strong>Entregado</strong> </p>
                <p><i class="far fa-times-circle cross-icon" style="color: red;"></i> <strong>No Entregado</strong></p>

                @endrole
            </div>
            @if($evaluaciones !== null)
                @foreach($evaluaciones as $evaluacion)

                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('profesor'))
                        <a href="{{route('evaluacion.create', ['id_e' => $evaluacion->id, 'id_pm' => $id_pm, 'id_m' => $id_m])}}">
                            <div class="card">
                                <div class="card-body">
                                    <p>
                                        @if($evaluacion->publicado == 1)
                                            <i class="far fa-check-circle check-icon"></i>
                                        @else
                                            <i class="far fa-times-circle cross-icon"></i>

                                        @endif
                                        {{$evaluacion->nombre}}
                                    </p>
                                </div>
                            </div>
                        </a>

                    @else

                        @if($evaluacion->publicado == 1)
                            <a id="enlace_evaluacion_{{ $evaluacion->id }}"
                                href="{{route('evaluacion.estudiantes', ['id_pm' => $evaluacion->id, 'id_a' => $id_a])}}">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-0">

                                                @php
                                                    $evaluacionCompleta = $evaluacionE->get($evaluacion->id);
                                                @endphp

                                                @if($evaluacionCompleta)
                                                    @if($evaluacionCompleta->completado === "si")
                                                        <i class="far fa-check-circle check-icon"></i>
                                                        <script>
                                                            actualizarEnlace('{{ $evaluacion->id }}', '{{ $evaluacionCompleta->nota }}');
                                                        </script>
                                                    @elseif(now()->greaterThan($evaluacion->limite))
                                                        <i class="far fa-times-circle" style="color: red;" title="Fecha de entrega vencida"></i>
                                                        <script>
                                                            actualizarEnlace('{{ $evaluacion->id }}');
                                                        </script>
                                                    @elseif(now()->addHour()->greaterThanOrEqualTo($evaluacion->limite))
                                                        <i class="fas fa-clock" style="color: orange;" title="El tiempo se está agotando"></i>
                                                    @else
                                                        <i class="fas fa-bell" style="color: #ffc107;" title="Recuerda entregar a tiempo"></i>
                                                    @endif
                                                @else


                                                    @if(now()->greaterThan($evaluacion->limite))
                                                        <i class="far fa-times-circle" style="color: red;" title="Fecha de entrega vencida"></i>
                                                        <script>
                                                            actualizarEnlace('{{ $evaluacion->id }}');
                                                        </script>
                                                    @elseif(now()->addHour()->greaterThanOrEqualTo($evaluacion->limite))
                                                        <i class="fas fa-clock" style="color: orange;" title="El tiempo se está agotando"></i>
                                                    @else
                                                        <i class="fas fa-bell" style="color: #ffc107;" title="Recuerda entregar a tiempo"></i>
                                                    @endif


                                                @endif
                                                {{$evaluacion->nombre}}
                                            </p>
                                            @if($evaluacionCompleta && $evaluacionCompleta->completado === "si")
                                                <p class="mb-0">{{ $evaluacionCompleta->nota }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </a>
                        @endif
                    @endif
                @endforeach
            @endif


            @can('evaluacion.crear')
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearEvaluacionModal">
                    Crear Evaluación
                </button>


            @endcan

            <!-- Modal crear evaluacion -->
            <div class="modal fade" id="crearEvaluacionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div
                        class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                        <form action="{{ route('evaluacion.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Crear Nueva Evaluación</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <input type="hidden" class="form-control" id="id_pm" name="id_pm"
                                value="{{ old('id_pm', $id_pm) }}" required>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Evaluación</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre" value="{{ old('nombre') }}">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="detalle">Detalle de la Evaluación</label>
                                    <input type="text" class="form-control @error('detalle') is-invalid @enderror"
                                        id="detalle" name="detalle" value="{{ old('detalle') }}">
                                    @error('detalle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="limite">Fecha Límite</label>
                                    <input type="datetime-local" class="form-control @error('limite') is-invalid @enderror"
                                        id="limite" name="limite" value="{{ old('limite') }}">
                                    @error('limite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Crear Evaluación</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            @if(session('modal') == 'crearEvaluacionModal')
                @if(session(key: 'abrir') == true)

                    <script>
                        $(document).ready(function () {
                            $('#crearEvaluacionModal').modal('show');
                        });

                    </script>

                    {{ session(['modal' => 'crearEvaluacionModal', 'abrir' => false]) }}
                @endif
            @endif
        </div>
    </div>



@endsection