@extends('layouts.argon')


@section('content')

  <div class="container-fluid">
    <h4 class="text-white">Crear Tema</h4>
    <form action="{{route('temas.store')}}" method="post">
    @csrf
    <input type="hidden" value="{{$id_pm}}" name="id_m">
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese el nombre">
    </div>

  </div>
  </div>

  <button type="submit" class="btn btn-primary">Crear</button>
  </form>
  </div>


@endsection