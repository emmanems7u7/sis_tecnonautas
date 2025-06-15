@section('formParalelos')
  <div class="col-md-6 mt-3">
    <div class="card">
    <div class="card-body">
      <h5 class="card-title">Crear Nuevo Paralelo</h5>
      <form action="{{ route('paraleloModulo.store') }}" enctype="multipart/form-data" method="post">
      @csrf

      <div class="form-group">
        @if($paradisp != null)
        <label for="paralelo">Paralelos Disponibles</label>
        <select class="form-control @error('paralelo') is-invalid @enderror" id="paralelo" name="paralelo">
        @foreach($paradisp as $paralelo)
      <option value="{{ $paralelo->id }}" {{ old('paralelo') == $paralelo->id ? 'selected' : '' }}>
        {{ $paralelo->nombre }}
      </option>
      @endforeach
        </select>
        @error('paralelo')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      @else
      <label for="paralelo">No hay paralelos Disponibles, crear más desde configuración</label>
      @endif
      </div>

      @if($paradisp != null)
      <div class="form-group">
        <label for="mes">Fecha de inicio correspondiente al paralelo de este módulo</label>
        <input type="date" name="mes" id="mes" class="form-control @error('mes') is-invalid @enderror"
        value="{{ old('mes') }}">
        @error('mes')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <div id="horarios">
        <div class="form-row">
        <div class="col-md-4">
        <label for="dia">Día</label>
        <select class="form-control @error('dia.0') is-invalid @enderror" name="dia[]">
        @php
        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        @endphp
        @foreach($dias as $dia)
        <option value="{{ $dia }}" {{ old('dia.0') == $dia ? 'selected' : '' }}>{{ $dia }}</option>
      @endforeach
        </select>
        @error('dia.0')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="col-md-4">
        <label for="horaInicio">Inicio</label>
        <input type="time" class="form-control @error('horaInicio.0') is-invalid @enderror" name="horaInicio[]"
        value="{{ old('horaInicio.0') }}">
        @error('horaInicio.0')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="col-md-4">
        <label for="horaFin">Fin</label>
        <input type="time" class="form-control @error('horaFin.0') is-invalid @enderror" name="horaFin[]"
        value="{{ old('horaFin.0') }}">
        @error('horaFin.0')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        </div>
      </div>

      <button type="button" class="btn btn-primary mt-3" id="agregarHorario">Agregar Horario</button>

      <div class="form-group mt-3">
        <label for="profesor">Asignar Profesor</label>
        <select class="form-control @error('profesor') is-invalid @enderror" id="profesor" name="profesor">
        @foreach($profesores as $profesor)
      <option value="{{ $profesor->id }}" {{ old('profesor') == $profesor->id ? 'selected' : '' }}>
        {{ $profesor->usuario_nombres }} {{ $profesor->usuario_app }} {{ $profesor->usuario_apm }}
      </option>
      @endforeach
        </select>
        @error('profesor')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <input type="hidden" name="id_a" value="{{ $id_a }}">
      <input type="hidden" name="id_m" value="{{ $id_m }}">

      <button type="submit" class="btn btn-primary btn-block">Crear Paralelo</button>

      </form>

      @endif
    </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Agregar campo de entrada de horario
    document.getElementById('agregarHorario').addEventListener('click', function () {
      const horariosDiv = document.getElementById('horarios');
      const nuevoHorario = document.createElement('div');
      nuevoHorario.className = 'form-row';
      nuevoHorario.innerHTML = `
      <div class="col-md-4">
      <label for="dia">Día</label>
      <select class="form-control" name="dia[]">
      <option value="Lunes">Lunes</option>
      <option value="Martes">Martes</option>
      <option value="Miercoles">Miércoles</option>
      <option value="Jueves">Jueves</option>
      <option value="Viernes">Viernes</option>
      <option value="Sabado">Sábado</option>
      <option value="Domingo">Domingo</option>
      </select>
      </div>
      <div class="col-md-4">
      <i id="iconoInicio" class="fas fa-clock"></i>  <label id="hi" for="horaInicio">Hora de Inicio</label>
      <input type="time" class="form-control" name="horaInicio[]" id="horaInicio">
      </div>
      <div class="col-md-4">
      <i id="iconoFin" class="fas fa-clock"></i><label id="hf" for="horaFin">Hora de Fin</label>
      <input type="time" class="form-control" name="horaFin[]" id="horaFin">  
      </div>
      `;
      horariosDiv.appendChild(nuevoHorario);
    });


    });
  </script>
@endsection