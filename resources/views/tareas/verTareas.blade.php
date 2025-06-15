<div class="container-fluid my-5">


    <!-- Contenedor para el título y la descripción -->
    <div class="alert alert-info mb-5" role="alert">
        <h5 class="alert-heading text-center">📘 Tareas Entregadas</h5>
        <p class="mb-2 text-center">
            Aquí puedes ver una lista de todas las tareas que has entregado, junto con sus notas, comentarios y archivos
            subidos.
        </p>
        <p class="mb-0 text-center">
            Revisa cada tarea para obtener detalles completos y asegurarte de que todo esté correctamente evaluado.
        </p>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">{{ $estudiante->usuario_nombres }} {{ $estudiante->usuario_app }}
                {{ $estudiante->usuario_apm }}</h3>
        </div>
        <div class="card-body">
            @forelse ($estudiante->tareasEstudiantes as $tarea)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title">{{ $tarea->tarea->nombre }}</h5>
                            </div>
                            <div class="col-6 text-end">
                                <p class="text-muted mb-0">Fecha límite de entrega:
                                    {{ \Carbon\Carbon::parse($tarea->tarea->limite)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>



                        <p><strong>Nota:</strong> {{ $tarea->nota }}</p>
                        <p><strong>Comentario:</strong> {{ $tarea->comentario }}</p>
                        <p><strong>Fecha de Entrega:</strong> {{ $tarea->created_at }}</p>
                        <p><strong>Archivo:</strong>
                            @if($tarea->archivo)
                                <a href="{{ asset('storage/' . $tarea->archivo) }}" target="_blank" class="btn btn-info">Ver
                                    archivo</a>
                            @else
                                No hay archivo subido.
                            @endif
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-muted">No tienes tareas entregadas.</p>
            @endforelse
        </div>
    </div>
</div>