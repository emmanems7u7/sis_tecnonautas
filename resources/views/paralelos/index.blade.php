@extends('layouts.argon')


@section('content')

  <div class="container">


    <div class="card ">
    <div class="card-header ">
      <h5 class="card-title mb-0">Administración de Paralelos</h5>
    </div>
    <div class="card-body ">

      <p class="card-text">¡Bienvenido a la seccion de administración de paralelos! Aquí puedes ver, editar y eliminar
      los paralelos existentes, así como agregar nuevos paralelos según sea necesario. Recuerda que los paralelos se
      podrán asignar independientemente a cada módulo existente según la necesidad, brindando flexibilidad y
      adaptabilidad a tu gestión académica.</p>
      <div class="row">
      <!-- Nuevo Paralelo -->
      <div class="col-md-6">
        <div class="card mb-3">

        <div class="card-header bg-success text-white">
          Crear un nuevo Paralelo
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('Paralelos.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="nombre" class="text-uppercase text-muted mb-1">Nombre del Paralelo:</label>
            <input type="text" class="form-control py-4" id="nombre" name="nombre" required
            style="border: 2px solid #ced4da; border-radius: 10px;">
          </div>
          <div class="form-group">
            <label for="cupo" class="text-uppercase text-muted mb-1">Cupo del Paralelo:</label>
            <input type="number" class="form-control py-4" id="cupo" name="cupo" required
            style="border: 2px solid #ced4da; border-radius: 10px;">
          </div>
          <button type="submit" class="btn btn-primary btn-block py-3" style="border-radius: 10px;">Crear
            Paralelo</button>
          </form>

        </div>

        </div>
      </div>
      <div class="col-md-6">
        <!-- Lista de Paralelos -->
        <div class="card">
        <div class="card-header bg-info text-white">
          Paralelos Existentes
        </div>
        <div class="card-body">
          <div class="table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Nombre</th>
              <th>Cupo</th>
              <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($paralelos as $paralelo)
        <!-- Aquí se generará dinámicamente la lista de paralelos -->
        <tr>
          <td>{{$paralelo->nombre}}</td>
          <td>{{$paralelo->cupo}}</td>
          <td>
          <a href="{{ route('Paralelos.edit', ['id' => $paralelo->id]) }}"
          class="btn btn-sm btn-primary">Editar</a>
          <a href="{{ route('Paralelos.delete', ['id' => $paralelo->id]) }}" class="btn btn-sm btn-danger"
          onclick="event.preventDefault(); if(confirm('¿Estás seguro de que quieres eliminar este paralelo?')) { document.getElementById('delete-form-{{ $paralelo->id }}').submit(); }">Eliminar</a>
          <form id="delete-form-{{ $paralelo->id }}"
          action="{{ route('Paralelos.delete', ['id' => $paralelo->id]) }}" method="POST"
          style="display: none;">
          @csrf
          @method('DELETE')
          </form>


          </td>
        </tr>
        @endforeach
            </tbody>
          </table>
          </div>
        </div>
        </div>
      </div>
      </div>

    </div>
    </div>
  </div>
@endsection