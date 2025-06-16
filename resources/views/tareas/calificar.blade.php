<div class="container">



    <div class="alert alert-info text-white text-center my-4" role="alert">
        <h5 class="alert-heading">📚 Tareas Realizadas por Estudiantes</h5>
        <p class="mb-0">
            En esta sección puedes ver todas las tareas que han sido completadas por los estudiantes. Revisa los
            detalles de cada entrega para evaluar su progreso, comentarios y archivos adjuntos.
        </p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach ($tareas as $tarea)
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title">Tarea: {{ $tarea->nombre }}</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-subtitle mb-3 text-muted">Detalle de la tarea:</h5>
                        <p>{{ $tarea->detalle }}</p>

                        @if ($tarea->tareasEstudiantes->isEmpty())
                            <p class="text-muted">No hay tareas realizadas.</p>
                        @else
                            <div class="accordion" id="accordionTareas{{ $tarea->id }}">
                                @foreach ($tarea->tareasEstudiantes as $index => $tareaEstudiante)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $tareaEstudiante->id }}">
                                            <button class="accordion-button{{ $index > 0 ? ' collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $tareaEstudiante->id }}"
                                                aria-expanded="true" aria-controls="collapse{{ $tareaEstudiante->id }}">
                                                <strong>Estudiante: </strong> {{ $tareaEstudiante->estudiantes->usuario_nombres }}
                                                {{ $tareaEstudiante->estudiantes->usuario_app }}
                                                {{ $tareaEstudiante->estudiantes->usuario_apm }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $tareaEstudiante->id }}"
                                            class="accordion-collapse collapse{{ $index === 0 ? ' show' : '' }}"
                                            aria-labelledby="heading{{ $tareaEstudiante->id }}"
                                            data-bs-parent="#accordionTareas{{ $tarea->id }}">
                                            <div class="accordion-body">
                                                <p><strong>Entregado:</strong>
                                                    <span
                                                        style="color: {{ $tareaEstudiante->tarea->created_at->greaterThanOrEqualTo($tarea->limite) ? 'red' : 'inherit' }}">
                                                        {{ $tareaEstudiante->tarea->created_at->format('Y-m-d') }}
                                                    </span>
                                                </p>
                                                <p><strong>Hora:</strong> {{ $tareaEstudiante->tarea->created_at->format('H:i:s') }}
                                                </p>
                                                <p><strong>Nota:</strong>
                                                    @if($tareaEstudiante->nota == 0)
                                                        <form action="{{ route('calificar', ['id' => $tareaEstudiante->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="number" class="form-control mb-2"
                                                                value="{{ $tareaEstudiante->nota }}" name="nota">

                                                            <button type="submit" class="btn btn-warning">Calificar</button>
                                                        </form>
                                                    @else
                                                    {{ $tareaEstudiante->nota }}
                                                @endif
                                                </p>
                                                <p><strong>Archivo Subido:</strong>
                                                    @if($tareaEstudiante->archivo)
                                                        <a href="{{ asset($tareaEstudiante->archivo) }}" target="_blank"
                                                            class="btn btn-info">Ver archivo</a>
                                                    @else
                                                        No hay archivo subido.
                                                    @endif
                                                </p>
                                                @if($tareaEstudiante->nota != 0)
                                                    <form action="{{ route('calificar', ['id' => $tareaEstudiante->id]) }}"
                                                        method="POST" class="mt-2">
                                                        @csrf
                                                        <input type="hidden" name="nota" value="{{ $tareaEstudiante->nota }}">
                                                        <button type="submit" class="btn btn-success" disabled>Calificado</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>