@section('subirT')

<div class="modal fade" id="tareaModal{{ $tarea->id }}" tabindex="-1" aria-labelledby="tareaModalLabel{{ $tarea->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tareaModalLabel{{ $tarea->id }}">Detalles de Tarea: {{ $tarea->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('tareas.store.estudiante')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="hidden" name="tarea_id" value="{{$tarea->id}}">
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
@endsection