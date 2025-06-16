@extends('layouts.argon')


@section('content')


  <link rel="stylesheet" href="{{asset('css/estilocard.css')}}">

  <style>
    .jumbotron {
    background-color: #009688;
    color: #fff;
    text-align: center;
    border-radius: 0;
    }

    .description {
    font-size: 1.2rem;
    line-height: 1.6;
    }
  </style>
   <style>
    .card {
    overflow: visible;
    width: 304px;
    height: 320px;
    }

    .content {
    width: 100%;
    height: 85%;
    transform-style: preserve-3d;
    transition: transform 300ms;
    box-shadow: 0px 0px 10px 1px #000000ee;
    border-radius: 5px;
    }

    .front,
    .back {
    
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    border-radius: 5px;
    overflow: hidden;
    }

    .back {
    width: 100%;
    height: 100%;
    justify-content: center;
    display: flex;
    align-items: center;
    overflow: hidden;
    }

    .back::before {
    position: absolute;
    content: ' ';
    display: block;
    width: 160px;
    height: 160%;
    background: linear-gradient(90deg, transparent, #838b93, #70777e, #5c636a, #3d444a, transparent);
    animation: rotation_481 5000ms infinite linear;
    }

    .back-content {
    position: absolute;
    width: 99%;
    height: 99%;
    background-color: #151515;
    border-radius: 5px;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 30px;
    }

    .card:hover .content {
    transform: rotateY(180deg);
    }

    @keyframes rotation_481 {
    0% {
      transform: rotateZ(0deg);
    }

    0% {
      transform: rotateZ(360deg);
    }
    }

    .front {
    transform: rotateY(180deg);
    color: white;
    }

    .front .front-content {
    position: absolute;
    width: 100%;
    height: 100%;
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    }

    .front-content .badge {
    background-color: #00000055;
    padding: 2px 10px;
    border-radius: 10px;
    backdrop-filter: blur(2px);
    width: fit-content;
    }

    .description {
    box-shadow: 0px 0px 10px 5px #00000088;
    width: 100%;
    padding: 10px;
    background-color: #00000099;
    backdrop-filter: blur(5px);
    border-radius: 5px;
    }

    .title {
    font-size: 11px;
    max-width: 100%;
    display: flex;
    justify-content: space-between;
    }

    .title p {
    width: 50%;
    }

    .card-footer {
      color: #ffffff88;
    margin-top: -17px;
    margin-left: -12px;
    margin-bottom: 2px;
    font-size: 11px;
    }

    .front .img {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    }

    .circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background-color: #598acf;
    position: relative;
    filter: blur(15px);
    animation: floating 2600ms infinite linear;
    }

    #bottom {
    background-color: #086501;
    left: 50px;
    top: 0px;
    width: 150px;
    height: 150px;
    animation-delay: -800ms;
    }

    #right {
    background-color: #ff2233;
    left: 160px;
    top: -80px;
    width: 30px;
    height: 30px;
    animation-delay: -1800ms;
    }

    @keyframes floating {
    0% {
      transform: translateY(0px);
    }

    50% {
      transform: translateY(10px);
    }

    100% {
      transform: translateY(0px);
    }
    }

    .card {
    background-color: transparent !important;
    }
  </style>



  <div class="alert alert-info text-justify shadow-lg rounded py-4">
    <h5 class="fw-bold mb-3">
        ¡Bienvenido a la materia de <span class="text-warning">{{$e->nombre}}</span>!
    </h5>
    <p class="mb-3">
        Nos complace darte la bienvenida a este espacio de aprendizaje. Juntos exploraremos nuevos conocimientos y
        desarrollaremos habilidades clave para tu formación.
    </p>

    <div class="mt-4">
        <strong>Objetivos de la materia</strong>
        <ul class="list-unstyled">
            @foreach ($objetivos as $objetivo)
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{$objetivo->objetivo}}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

    <div class="row">
    @foreach($modulos as $dat)

    <div class="card mb-3 " style="margin-left: 13px;">
      <div class="content">
      <div class="back">
      <div class="back-content" 
     style="background-image: url('{{ asset($dat->imagen ?  $dat->imagen : 'imagenes/tecnonautas.png') }}'); 
            width: 98%; 
            height: 98%; 
            background-size: contain; 
            background-position: center;
            background-repeat: no-repeat;"
            
            >


      <svg stroke="#ffffff" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 50 50" height="50px" width="50px" fill="#ffffff">

        <g stroke-width="0" id="SVGRepo_bgCarrier"></g>

        <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>

       
      </svg>
  
      </div>
      </div>
      <div class="front" >


      <div class="img">
      <div class="circle">
      </div>
      <div class="circle" id="right">
      </div>
      <div class="circle" id="bottom">
      </div>
      </div>

      <div class="front-content">
      <small class="badge"> {{$dat->Duracion}}</small>
      <div class="d-flex flex-column align-items-center text-center my-3">

     
      <i class="fas fa-book fa-lg"></i>

        <strong>{{$dat->nombreM}}</strong>

        </div>
      <div class="description">
        
        <div class="title">
          
        <p class="title">

        <strong>Acciones </strong>
       
        </p>
        <svg fill-rule="nonzero" height="15px" width="15px" viewBox="0,0,256,256"
        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
        <g style="mix-blend-mode: normal" text-anchor="none" font-size="none" font-weight="none"
        font-family="none" stroke-dashoffset="0" stroke-dasharray="" stroke-miterlimit="10"
        stroke-linejoin="miter" stroke-linecap="butt" stroke-width="1" stroke="none" fill-rule="nonzero"
        fill="#20c997">
        <g transform="scale(8,8)">
          <path d="M25,27l-9,-6.75l-9,6.75v-23h18z"></path>
        </g>
        </g>
        </svg>
        </div>
        <p class="card-footer">

        @if($dat->ultimo_modulo == 1)
      <strong class="text-success fw-bold">Este es el último módulo de la materia</strong>


    @endif
        </p>

        @if($dat->habilitado == 1)
      @role('estudiante')
      <a href="{{ route('modulos.temas.show', ['id_pm' => $dat->paramodulo, 'id_m' => $dat->id]) }}"
      class="btn btn-primary btn-sm" title="Ver Contenido">
      <i class="fas fa-book-open"></i>
      </a>
      @endrole

      @role('admin|profesor')
      <a href="{{ route('Paralelos.modulos.show', ['id_m' => $dat->id, 'id_a' => $id_a]) }}"
      class="btn btn-info btn-sm" title="Ver Contenido">
      <i class="fas fa-eye"></i>
      </a>
      @endrole

      @can('modulos.materia.edit')
      <a href="{{ route('modulos.materia.edit', ['id' => $dat->id]) }}" class="btn btn-secondary btn-sm"
      title="Editar Módulo">
      <i class="fas fa-edit"></i>
      </a>
    @endcan

      @can('modulos.materia.delete')
      <a href="{{ route('modulos.materia.delete', ['id' => $dat->id]) }}" class="btn btn-danger btn-sm"
      title="Eliminar Módulo">
      <i class="fas fa-trash"></i>
      </a>
    @endcan
    @else
    <p class="card-footer">Aun no tienes acceso al contenido de este modulo</p>
    <button class="btn btn-secondary btn-sm" disabled title="No disponible">
    <i class="fas fa-lock"></i>
    
    </button>
   

  @endif

      </div>
      </div>
      </div>
      </div>
    </div>
    @endforeach
    @role('admin')

    <div class="card mb-3 " style="margin-left: 33px;">
      <div class="content">
      <div class="back">
      <div class="back-content">
      <svg stroke="#ffffff" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 50 50" height="50px" width="50px" fill="#ffffff">

        <g stroke-width="0" id="SVGRepo_bgCarrier"></g>

        <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50" height="50" fill="currentColor">
    <path d="M19 13H12V19H10V13H3V11H10V5H12V11H19V13Z" />
</svg>


      </svg>
      <strong>Nuevo Modulo</strong>
      </div>
      </div>
      <div class="front" >

      <div class="img">
      <div class="circle">
      </div>
      <div class="circle" id="right">
      </div>
      <div class="circle" id="bottom">
      </div>
      </div>

      <div class="front-content">
      <small class="badge"> Acciones disponibles</small>
      <div class="description">
        <div class="title">
        <p class="title">

        <strong>Acciones </strong>
        </p>
        <svg fill-rule="nonzero" height="15px" width="15px" viewBox="0,0,256,256"
        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
        <g style="mix-blend-mode: normal" text-anchor="none" font-size="none" font-weight="none"
        font-family="none" stroke-dashoffset="0" stroke-dasharray="" stroke-miterlimit="10"
        stroke-linejoin="miter" stroke-linecap="butt" stroke-width="1" stroke="none" fill-rule="nonzero"
        fill="#20c997">
        <g transform="scale(8,8)">
          <path d="M25,27l-9,-6.75l-9,6.75v-23h18z"></path>
        </g>
        </g>
        </svg>
        </div>


     

        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarModulo">
        Crear nuevo Módulo
        </button>

      </div>
      </div>
      </div>
      </div>
    </div>
    <div class="modal fade" id="modalAgregarModulo" tabindex="-1" aria-labelledby="modalAgregarModuloLabel"
    aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content {{ auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}" >
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarModuloLabel">Agregar Módulo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('modulo.store') }}" enctype="multipart/form-data" method="POST" id="moduloForm">
          @csrf
          <input type="hidden" name="cursoid" value="{{$id_a}}">

          <!-- Selección de módulo -->
          <div class="mb-3">
            <label for="moduloSelect" class="form-label">Selecciona módulo</label>
            <select id="moduloSelect" name="nombre" class="form-select" aria-label="Selecciona el módulo">
              <option value="" selected>Selecciona</option>
              <option value="Modulo 1" {{ old('nombre') == 'Modulo 1' ? 'selected' : '' }}>Modulo 1</option>
              <option value="Modulo 2" {{ old('nombre') == 'Modulo 2' ? 'selected' : '' }}>Modulo 2</option>
              <option value="Modulo 3" {{ old('nombre') == 'Modulo 3' ? 'selected' : '' }}>Modulo 3</option>
              <option value="Modulo 4" {{ old('nombre') == 'Modulo 4' ? 'selected' : '' }}>Modulo 4</option>
              <option value="Modulo 5" {{ old('nombre') == 'Modulo 5' ? 'selected' : '' }}>Modulo 5</option>
              <option value="Modulo 6" {{ old('nombre') == 'Modulo 6' ? 'selected' : '' }}>Modulo 6</option>
            </select>
            @error('nombre')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <!-- Descripción -->
          <div class="mb-3">
            <label for="descripcionTextarea" class="form-label">Descripción</label>
            <textarea id="descripcionTextarea" name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
            @error('descripcion')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <!-- Duración -->
          <div class="mb-3">
            <label for="duracionSelect" class="form-label">Duración</label>
            <select id="duracionSelect" name="duracion" class="form-select" aria-label="Selecciona la duración">
              <option value="" selected>Selecciona</option>
              <option value="1 mes" {{ old('duracion') == '1 mes' ? 'selected' : '' }}>1 mes</option>
              <option value="2 meses" {{ old('duracion') == '2 meses' ? 'selected' : '' }}>2 meses</option>
              <option value="3 meses" {{ old('duracion') == '3 meses' ? 'selected' : '' }}>3 meses</option>
              <option value="4 meses" {{ old('duracion') == '4 meses' ? 'selected' : '' }}>4 meses</option>
              <option value="5 meses" {{ old('duracion') == '5 meses' ? 'selected' : '' }}>5 meses</option>
              <option value="6 meses" {{ old('duracion') == '6 meses' ? 'selected' : '' }}>6 meses</option>
            </select>
            @error('duracion')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <!-- Subida de foto -->
          <div class="mb-3">
            <label for="fotoInput" class="form-label">Foto</label>
            <input id="fotoInput" class="form-control" type="file" accept=".jpg,.jpeg,.png" name="imagen" 
              onchange="previewFile('fotoPreview', 'fotoHiddenInput')">
            <div id="fotoPreview" class="mt-2"></div>

          
            @error('imagen')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

         
         
          <!-- Checkbox para último módulo -->
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="ultimoModulo" name="ultimo_modulo"
              {{ old('ultimo_modulo') ? 'checked' : '' }}>
            <label class="form-check-label" for="ultimoModulo">
              Este es el último módulo de la materia
            </label>
            @error('ultimo_modulo')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <!-- Botón de guardar -->
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
   </div>
   </div>
    @endrole
  

<script>

   document.addEventListener('DOMContentLoaded', function () {
            // Verifica si hay errores de validación
            let hasErrors = @json($errors->any());
          
            if (hasErrors) {
              $('#modalAgregarModulo').modal('show');
            

            }
        });
</script>
@endsection