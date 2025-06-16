@extends('layouts.argon')

@section('content')

  <link rel="stylesheet" href="{{asset('css/stylecarrusel.css')}}">
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

  <div class="alert alert-info shadow-sm" role="alert">
    <div class="row align-items-center">
    <!-- Columna de la imagen -->
    <div class="col-md-3 text-center mb-3 mb-md-0">
      <img src="{{ asset('imagenes/tecnonautas.png') }}" alt="Imagen inscripción" class="img-fluid"
      style="max-width: 150px;">
    </div>

    <!-- Columna del texto -->
    <div class="col-md-9 text-justify">
      <h4 class="alert-heading">
      <i class="fas fa-user-edit"></i> ¡Proceso de Inscripción Abierto!
      </h4>

      <p>
      Ya puedes inscribirte en las materias disponibles para este período académico. Explora nuestra oferta
      educativa y selecciona los cursos que más se adapten a tus intereses y objetivos.
      </p>

      <p>
      <strong>Importante:</strong> Algunas materias son completamente <span
        class="text-success fw-bold">gratuitas</span>, mientras que otras requieren un pequeño <span
        class="text-warning fw-bold">pago</span> para acceder a contenido exclusivo y recursos avanzados.
      </p>

      <p>
      Asegúrate de revisar los detalles de cada curso antes de confirmar tu inscripción. ¡Estamos aquí para
      ayudarte en cada paso de tu camino académico!
      </p>


    </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">

    <h5 class="text-dark text-center mb-4">Materias de Pago</h5>
    </div>
  </div>

  <section id="tranding" class="py-5 mt-2 ">
    <div class="container">


    <div class="swiper tranding-slider">
      <div class="swiper-wrapper">
      @foreach($pagos as $pago)
      <div class="swiper-slide">
      <div class="card bg-dark border-0 text-center">
      <div class="position-relative">
        <img src="{{ asset($pago->imagen1) }}" class="img-fluid rounded" style="height: 250px; object-fit: cover;"
        alt="Curso">
        <h5 class="text-white position-absolute top-0 start-0 m-2 bg-primary px-2 py-1 rounded">
        {{ $pago->nombre }}
        </h5>
      </div>
      <div class="card-body">
        <button onclick="crearModal({{ $pago->id }})" class="btn btn-success btn-sm w-100">
        Realizar inscripción
        </button>
      </div>
      </div>
      </div>
    @endforeach
      </div>

      <!-- Controles -->
      <div class="tranding-slider-control d-flex justify-content-between align-items-center mt-3">
      <div class="swiper-button-prev slider-arrow text-white"></div>
      <div class="swiper-button-next slider-arrow text-white"></div>
      </div>
      <div class="swiper-pagination mt-2"></div>
    </div>
    </div>
  </section>




  <div class="card">
    <div class="card-body">
    <h5 class="text-dark text-center section-subheading">Materias gratuitas</h5>
    </div>
  </div>
  <section id="tranding">
    <div class="container">

    </div>
    <div class="container">
    <div class="swiper tranding-slider">
      <div class="swiper-wrapper">
      <!-- Slide-start -->
      @foreach($gratuitos as $gratuito)

      <div class="swiper-slide tranding-slide">

      <div class="tranding-slide-img">

      <img src="{{asset('/storage' . $gratuito->imagen1) }}" alt="Tranding">

      </div>

      <div class="tranding-slide-content">

      <div class=" tranding-slide-content-bottom">

        <h2 class=" text-white food-name">
        {{$gratuito->nombre}}
        </h2>

        <h3 class="text-white food-rating">
        {{$gratuito->descripcion}}
        </h3>
        <a href="" class="btn btn-success"> realizar inscripción
        </a>
      </div>
      </div>
      </div>

    @endforeach
      <!-- Slide-end -->
      </div>
      <div class="tranding-slider-control">
      <div class="swiper-button-prev slider-arrow">
        <ion-icon name="arrow-back-outline"></ion-icon>
      </div>
      <div class="swiper-button-next slider-arrow">
        <ion-icon name="arrow-forward-outline"></ion-icon>
      </div>
      <div class="swiper-pagination"></div>
      </div>
    </div>
    </div>
  </section>
  <style>
    .modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 1;
    pointer-events: none;
    transition: opacity 0.3s ease;
    }

    .modal-contenido {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    }

    .custom-card-img {
    height: 150px;
    object-fit: contain;
    }
  </style>

  <style>
    .module {
    border: 1px solid #ccc;
    border-radius: 10px;
    margin-bottom: 30px;
    padding: 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .module-header {
    margin-bottom: 15px;
    }

    .module-header h5 {
    margin-bottom: 5px;
    font-size: 20px;
    }

    .module-header p {
    margin-bottom: 0;
    font-size: 16px;
    }

    .module-description {
    font-size: 16px;
    margin-bottom: 20px;
    }

    .module-details p {
    margin-bottom: 5px;
    font-size: 14px;
    }

    .parallel-select {
    margin-bottom: 5px;
    }
  </style>
  <script>


    function enviarBtn(idMateria) {

    var selectParalelo = document.getElementById('selectParalelo');

    if (!selectParalelo || !selectParalelo.value) {
      const modal = bootstrap.Modal.getInstance(document.getElementById('Modal_inscripcion'));
      if (modal) {
      modal.hide();
      }
      alertify.error('No puede realizar la inscripción');
      return;
    }

    var id_p = selectParalelo.value;
    const enlaceInscripcion = document.getElementById('inscripcion');

    enlaceInscripcion.href = "{{ route('inscripcionpago.store') }}/?id_a=" + idMateria + "&id_pm=" + id_p;
    }

    function crearModal(idMateria) {
    const idNumerico = parseInt(idMateria);
    if (isNaN(idNumerico)) return;

    // Elimina modal previo si existe
    const existingModal = document.getElementById('Modal_inscripcion');
    if (existingModal) existingModal.remove();

    const modalHtml = `
    <div id="Modal_inscripcion" class="modal fade" tabindex="-1" aria-labelledby="Modal_inscripcionLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content {{ auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
      <div class="modal-header">
      <h5 class="modal-title" id="Modal_inscripcionLabel">Inscripción de curso</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
      <div class="container-fluid">
      <div class="row">
      <div class="col-md-4">
      <img src="" alt="Imagen del curso" class="img-fluid" id="imagenMateria">
      <h4 id="nombreMateria" class="mt-2">Curso</h4>
      <h5 id="PrecioMateria">Precio del curso</h5>
      <p id="descripcionMateria" class="text-justify"></p>
      </div>
      <div class="col-md-8">
      <div class="row" id="datos_modulo"></div>
      <hr>
      <div class="d-flex justify-content-between">
      <a id="inscripcion" href="#" class="btn btn-success" onclick="enviarBtn(${idMateria})">¡Inscríbete Ahora!</a>
      <button type="button" id='boton_modal_inscripcion' class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
    </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);

    const myModal = new bootstrap.Modal(document.getElementById('Modal_inscripcion'));
    myModal.show();

    fetch("{{ route('inscripcionModal.show') }}/?id_a=" + idMateria)
      .then(response => response.json())
      .then(data => {

      if (data.status == 'success') {


        document.getElementById('imagenMateria').src = '{{ asset('') }}' + data.data.imagen;
        document.getElementById('nombreMateria').textContent = 'Curso: ' + data.data.nombre;
        document.getElementById('descripcionMateria').textContent = data.data.descripcion;
        document.getElementById('PrecioMateria').textContent = `Precio: ${data.data.precio} Bs`;

        const datosModuloDiv = document.getElementById('datos_modulo');
        datosModuloDiv.innerHTML = `
      <div class="module">
      <div class="module-header mb-2">
      <div class="row">
      <div class="col">
      <h5>${data.data.nombreM}</h5>
      </div>
      <div class="col text-end">
      <p><strong>Duración:</strong> ${data.data.duracion}</p>
      </div>
      </div>
      </div>
      <div class="module-description mb-2">
      <p><strong>Descripción:</strong> ${data.data.descripcionMod}</p>
      </div>
      <div class="module-details">
      <div class="mb-2">
      <p><strong>Paralelos disponibles:</strong></p>
      <select class="form-select" id="selectParalelo"></select>
      </div>
      <div class="mb-2">
      <p><strong>Profesor asignado:</strong></p>
      <p id="nombreProfesor"></p>
      </div>
      <p><strong>Horarios:</strong></p>
      <div id="datosHorario" class="row"></div>
      </div>
      </div>
      `;

        const selectParalelo = document.getElementById('selectParalelo');
        selectParalelo.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.textContent = 'Seleccione Paralelo';
        defaultOption.selected = true;
        defaultOption.disabled = true;
        selectParalelo.appendChild(defaultOption);

        Object.keys(data.data.datosParalelo).forEach(function (key) {
        const paralelo = data.data.datosParalelo[key];
        const option = document.createElement('option');
        option.value = paralelo.id_p;
        option.textContent = 'Paralelo ' + key;
        selectParalelo.appendChild(option);
        });

        handleSelectChange(data.data.nombreM, idMateria);
      }
      else if (data.status == 'error') {

        const modal = bootstrap.Modal.getInstance(document.getElementById('Modal_inscripcion'));
        modal.hide();
        alertify.error(data.message)

      }
      })
      .catch(error => {
      console.error('Error al obtener los datos de la materia:', error);
      });
    }

    function eliminarModal() {
    const Modal_inscripcion = document.getElementById('Modal_inscripcion');
    Modal_inscripcion.remove();
    }

    const openModalBtn = document.getElementById('openModalBtn');

    openModalBtn.addEventListener('click', () => {

    const Modal_inscripcion = new bootstrap.Modal(document.getElementById('Modal_inscripcion'));


    Modal_inscripcion.show();

    });
  </script>
  <script>
    var TrandingSlider = new Swiper('.tranding-slider', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    loop: true,
    slidesPerView: 3, // Mostrar solo 3 sliders a la vez
    spaceBetween: 15, // Ajustar el espacio entre los sliders
    coverflowEffect: {
      rotate: 0,
      stretch: 0,
      depth: 100,
      modifier: 2.5,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    }
    });

    function handleSelectChange(nombre, id_a) {

    selectParalelo.addEventListener('change', function () {
      var selectParalelo = document.getElementById('selectParalelo');
      var id_p = selectParalelo.value;

      var routeUrl = '{{ route("Paralelo.get", ["nombre" => ":nombre", "id_a" => ":id_a", "id_p" => ":id_p"]) }}';
      routeUrl = routeUrl.replace(':nombre', nombre);
      routeUrl = routeUrl.replace(':id_a', id_a);
      routeUrl = routeUrl.replace(':id_p', id_p);


      fetch(routeUrl)
      .then(response => {
        if (!response.ok) {
        throw new Error('Ocurrió un error al cargar los datos');
        }
        return response.json();
      })
      .then(data => {

        var nombreProfesor = document.getElementById('nombreProfesor');
        var datosHorario = document.getElementById('datosHorario');

        nombreProfesor.textContent = data.profesor; // Establecer el nombre del profesor

        // Limpiar el contenido existente en datosHorario
        datosHorario.innerHTML = '';


        Object.keys(data.horarios).forEach(function (key) {
        let horario = data.horarios[key];

        // Crear un elemento <p> para mostrar el día y la hora
        let horarioInfo = document.createElement('p');
        horarioInfo.textContent = `${key}: ${horario.hora_inicio} - ${horario.hora_fin}`;

        // Agregar el elemento <p> al elemento datosHorario
        datosHorario.appendChild(horarioInfo);
        });
      })

      .catch(error => {
        console.error('Error:', error);
      });

    });
    }

  </script>

  <script>
    const swiper = new Swiper('.tranding-slider', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 20,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      576: {
      slidesPerView: 1,
      },
      768: {
      slidesPerView: 2,
      },
      992: {
      slidesPerView: 3,
      },
    },
    });
  </script>

@endsection