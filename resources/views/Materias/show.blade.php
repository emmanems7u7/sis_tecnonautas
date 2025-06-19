@extends('layouts.argon')


@section('content')
  <style>
    .welcome-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 10px;
    animation: fadeInUp 1s ease-in-out;
    }

    .welcome-text {
    font-size: 1rem;
    color: #333;
    line-height: 1.6;

    animation: fadeInUp 1s ease-in-out;
    }

    @keyframes fadeInUp {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }

    100% {
      opacity: 1;
      transform: translateY(0);
    }
    }
  </style>


  <div class="alert alert-info text-white shadow-sm" role="alert">
    <div class="row align-items-center">
    <!-- Columna de la imagen -->
    <div class="col-md-3 text-center mb-3 mb-md-0">
      <img src="{{ asset('imagenes/tecnonautas.png') }}" alt="Imagen decorativa" class="img-fluid"
      style="max-width: 150px;">
    </div>


    <!-- Columna del texto -->
    <div class="col-md-9 text-justify text-white">
      <h4 class="alert-heading">
      <i class="fas fa-chalkboard-teacher"></i> ¡Bienvenido a la sección de Cursos!
      </h4>

      @role('estudiante')
      <p>
      <strong>Para ti, estudiante:</strong> Aquí encontrarás una variedad de cursos a los que te inscribiste,
      diseñados para ayudarte a aprender y desarrollar nuevas habilidades. ¡Comienza tu viaje de aprendizaje hoy
      mismo!
      </p>
      @endrole

      @role('admin')
      <p>
      <strong>Para ti, administrador:</strong> Aquí encontrarás una amplia gama de cursos disponibles para tus
      estudiantes, cuidadosamente diseñados para enriquecer su aprendizaje y desarrollo de habilidades. Explora la
      selección de cursos y comienza a potenciar el crecimiento académico de tus alumnos hoy mismo. Asegúrate de que
      su viaje educativo marque una diferencia significativa en su futuro.
      </p>
      @endrole

      @role('profesor')
      <p>
      <strong>Para ti, profesor:</strong> Este es tu espacio dedicado para explorar una variedad de cursos diseñados
      para enriquecer tus métodos de enseñanza y expandir tus conocimientos. Descubre recursos educativos que te
      ayudarán a inspirar y guiar a tus estudiantes hacia el éxito académico. ¡Únete a nosotros mientras exploramos
      juntos el apasionante mundo del aprendizaje y la enseñanza!
      </p>
      @endrole
    </div>
    </div>
  </div>
  <div class="container">



    <div class="row">
    @foreach($e as $dat)
    <div class="col-md-5 mb-4">
      <div class="card shadow border-0 h-100">
      <img src="{{ asset($dat->imagen1) }}" class="card-img-top img-fluid" alt="Imagen del curso"
      style="height: 200px; object-fit: cover;">

      <div class="card-body d-flex flex-column justify-content-between">
      <h5 class="card-title text-primary fw-bold text-center">{{ $dat->nombre }}</h5>

      <div class="mt-3 d-flex flex-column align-items-center gap-2">
        @can('modulos.ver')
      <a href="{{ route('modulos.materia.show', ['id_a' => $dat->id]) }}"
        class="btn btn-info text-white btn-sm w-100">
        Ver Materia
      </a>

      @can('asignacion.editar')
      <a href="{{ route('asignacion.edit', ['id' => $dat->id]) }}"
      class="btn btn-warning text-white btn-sm w-100">
      Editar Materia
      </a>
      @endcan

      @can('asignacion.eliminar')
      <form action="{{ route('asignacion.delete', ['id' => $dat->id]) }}" method="POST" class="w-100">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger text-white btn-sm w-100">
      Eliminar Curso
      </button>
      </form>
      @endcan
      </div>
      </div>
      </div>
    </div>
    @endforeach
    </div>



    @can('asignacion.crear')
    <div class="cards">

    <div class="content">
      <a href="{{route('asignacion.create')}}">
      <img src="{{ asset('imagenes/mas.png') }}" alt="" />
      </a>
    </div>
    </div>
    @endcan
  </div>

@endsection