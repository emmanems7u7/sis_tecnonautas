@section('formularios')
  <!-- Mensaje Personal -->
  <div class="modal fade" id="mensajePersonalModal" tabindex="-1" aria-labelledby="mensajePersonalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
    <div
      class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
      <div class="modal-header bg-primary">
      <h5 class="modal-title text-white" id="mensajePersonalModalLabel">Mensaje Personal</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('profesores.mensaje.store') }}" method="post">
        @csrf
        <div class="form-group mb-3">
        <label for="cargo">Cargo en la empresa</label>
        <textarea id="cargo" name="cargo" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group mb-3">
        <label for="mensaje">Mensaje Personal</label>
        <textarea id="mensaje" name="mensaje" class="form-control" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </form>
      </div>
    </div>
    </div>
  </div>

  <!-- Experiencia Profesional -->
  <div class="modal fade" id="experienciaProfesionalModal" tabindex="-1"
    aria-labelledby="experienciaProfesionalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div
      class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
      <div class="modal-header bg-primary">
      <h5 class="modal-title text-white" id="experienciaProfesionalModalLabel">Experiencia Profesional</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('profesores.experiencia.store') }}" method="post">
        @csrf
        <div class="form-group mb-3">
        <label for="lugar">Lugar</label>
        <input type="text" id="lugar" name="lugar" class="form-control">
        </div>
        <div class="form-group mb-3">
        <label for="actividad">Actividad</label>
        <input type="text" id="actividad" name="actividad" class="form-control">
        </div>
        <div class="form-group mb-3">
        <label for="duracion">Duración</label>
        <input type="text" id="duracion" name="duracion" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </form>
      </div>
    </div>
    </div>
  </div>

  <!-- Educación -->
  <div class="modal fade" id="educacionModal" tabindex="-1" aria-labelledby="educacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div
      class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
      <div class="modal-header bg-primary">
      <h5 class="modal-title text-white" id="educacionModalLabel">Educación</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('profesores.profesion.store') }}" method="post">
        @csrf
        <div class="form-group mb-3">
        <label for="institucion">Institución</label>
        <input type="text" id="institucion" name="institucion" class="form-control">
        </div>
        <div class="form-group mb-3">
        <label for="carrera">Carrera</label>
        <input type="text" id="carrera" name="carrera" class="form-control">
        </div>
        <div class="form-group mb-3">
        <label for="semestre">Semestre</label>
        <input type="text" id="semestre" name="semestre" class="form-control">
        </div>
        <div class="form-group mb-3">
        <label for="concluido">¿Concluido? (Sí/No)</label>
        <input type="text" id="concluido" name="concluido" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </form>
      </div>
    </div>
    </div>
  </div>

@endsection