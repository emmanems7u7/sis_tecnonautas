<style>
  .container-p {

    margin: auto;
    padding: 50px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  }

  .profile-img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 50%;
    margin: 0 auto 20px;
    display: block;
    border: 5px solid #fff;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  }

  .section-title {
    font-size: 24px;
    color: #333;
    border-bottom: 2px solid #333;
    padding-bottom: 10px;
    margin-bottom: 30px;
  }

  .section-d {
    font-size: 24px;
    color: #3f3f3f;

    border-bottom: 2px solid #333;
    padding-bottom: 10px;

    width: 80%;
    margin-bottom: 30px;
  }

  .info-list {
    padding-left: 0;
    list-style-type: none;
  }

  .info-list li {
    margin-bottom: 10px;
  }

  .info-list strong {
    font-weight: bold;
  }

  hr {
    border-top: 1px solid #ccc;
    margin: 30px 0;
  }

  .modal-content {
    border-radius: 10px;
  }

  .modal-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  .modal-body {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }

  .form-control {
    border-radius: 5px;
    border: 1px solid #ced4da;
  }
</style>
</style>







@include('Personal.formularios')
@yield('formularios')

<!-- Mostrar todos los datos registrados -->



<div class="card">
  <div class="card-body">
    <div class="row">

      <div class="col-md-5">
        <!-- Información personal -->
        <div class="text-center">
          @if ($user->foto_perfil)
        <img src="{{ asset($user->foto_perfil) }}" alt="profile_image" class="profile-img">
      @else
        <img src="{{ asset('update/imagenes/user.jpg') }}" alt="profile_image" class="profile-img">
      @endif



          <h3 class="text-dark">{{ Auth::user()->usuario_nombres }} {{ Auth::user()->usuario_app }}
            {{ Auth::user()->usuario_apm }}
          </h3>
          @if($datosP !== null)
        <p class="lead text-secondary">{{$datosP->cargo}}</p>
      @endif

        </div>

        <div class="section-title">
          <h2>Mensaje
            @if($datosP !== null)
        <a href="" class="btn btn-primary">
          <i class="fas fa-edit"></i>

        </a>
      @else

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mensajePersonalModal">
          <i class="fas fa-plus"></i>
        </button>
      @endif
          </h2>
        </div>
        @if($datosP !== null)
      <p class="text-justify">{{$datosP->mensaje}}</p>
    @endif
      </div>
      <div class="col-md-7">

        <div class="section-title">
          <h2>Educación

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#educacionModal">
              <i class="fas fa-plus"></i>

            </button>
          </h2>
        </div>
        <!-- Educación -->
        <ul class="info-list">
          @if($eduP !== null)
          @foreach ($eduP as $edu)
        <li><strong>Institución:</strong> {{$edu->institucion}}</li>
        <li><strong>Carrera:</strong> {{$edu->carrera}}</li>
        <li><strong>Semestre:</strong> {{$edu->semestre}}</li>
        <li><strong>Concluido:</strong> {{$edu->concluido}}</li>
        @endforeach
      @endif
        </ul>
        <!-- Experiencia Profesional -->
        <div class="section-title">
          <h2>Experiencia Profesional
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
              data-bs-target="#experienciaProfesionalModal">
              <i class="fas fa-plus"></i>


          </h2>
          </button>


        </div>
        <ul class="info-list">
          @if($expP !== null)
          @foreach ($expP as $exp)
        <li><strong>Lugar:</strong> {{$exp->lugar}}
        <a href="#" class="btn btn-warning float-right mr-2"><i class="fas fa-edit"></i></a>
        <a href="#" class="btn btn-danger float-right"><i class="fas fa-trash-alt"></i></a>
        </li>
        <li><strong>Cargo:</strong> {{$exp->actividad}}</li>
        <li><strong>Duración:</strong> {{$exp->duracion}}</li>
        <div class="section-d mx-auto">
        </div>
        @endforeach
      @endif
        </ul>
        <hr>


      </div>
    </div>

  </div>
</div>