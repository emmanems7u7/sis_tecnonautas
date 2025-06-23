@section('reporteModal')
  <!-- Modal -->
  <style>
    #reportModal .modal-content {
    background-image: url('{{ asset('/imagenes/imagenreporte.jpg') }}');
    background-size: cover;

    }

    .colorcafe {
    color: #974705;
    }

    .mt-per {
    margin-top: 12%;

    }
  </style>

  <style>
    /* Asegura que la tabla sea responsiva */
    .table-responsive {
    overflow-x: auto;
    }

    /* Ajustes adicionales para pantallas pequeñas */
    @media (max-width: 768px) {

    .table th,
    .table td {
      padding: 8px;
      /* Reduce el espaciado */
    }

    .card-body {
      padding: 10px;
      /* Reduce el espaciado dentro de la tarjeta */
    }
    }

    #evaluacionesTable td,
    #tareasTable td {
    font-size: 0.85rem;
    padding: 4px 6px;
    vertical-align: middle;
    }

    #evaluacionesTable tr,
    #tareasTable tr {
    line-height: 1.2;
    }

    #evaluacionesTable td,
    #tareasTable td {
    white-space: nowrap;
    }
  </style>
  <script>


    function abrirModalComentario() {

    const modalElement = document.getElementById('commentModal');
    const bootstrapModal = new bootstrap.Modal(modalElement);


    const btnCerrar = document.getElementById('btn_cerrar_report');
    if (btnCerrar) {
      btnCerrar.click();
      bootstrapModal.show();
    }
    }

  </script>
  <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
    <div
      class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
      <div class="modal-header">

      <button type="button" class="btn-close" id="btn_cerrar_report" data-bs-dismiss="modal"
        aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <!-- Container -->
      <h5 class="modal-title mx-auto colorcafe font-weight-bold text-3rem" id="reportModalLabel">REPORTE ESTUDIANTE
      </h5>
      <div class="container-fluid">
        <!-- Primer Card -->
        <div class="card mb-3">
        <div class="row g-0">
          <div class="col-md-4">
          <div class="position-relative">
            <img src="" class="img-fluid rounded-start border shadow-lg" alt="Imagen del estudiante" id="fotoe">
          </div>
          </div>
          <div class="col-md-8">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title" id="estudiante">Nombre del Estudiante</h5>
            <span class="badge bg-success" id="destacado" style="display: none;">Estudiante Destacado</span>

            <button type="button" class="btn btn-info" id="openCommentModal" onclick="abrirModalComentario()">
              Generar PDF
            </button>

            </div>
            <p class="card-text" id="materia">Materia: Nombre de la Materia</p>
            <p class="card-text" id="paralelo">Paralelo: Nombre del Paralelo</p>
            <p class="card-text" id="modulo">Módulo: Nombre del Módulo</p>
            <p class="card-text" id="profesor">Profesor: Nombre del Profesor</p>
          </div>
          </div>
        </div>
        </div>

        <!-- Encabezado Reporte -->
        <h4 class="text-dark">Reporte</h4>

        <!-- Segundo Card - Evaluaciones -->
        <div class="card mb-3">

        <div class="card-body">
          <h5 class="card-title">Evaluaciones</h5>
          <div class="table-responsive">
          <table class="table table-sm table-bordered" id="evaluacionesTable">
            <thead>
            <tr>
              <th scope="col">Evaluación</th>
              <th scope="col">Nota</th>
              <th scope="col">Creado</th>
              <th scope="col">Límite</th>
              <th scope="col">Entregado</th>
            </tr>
            </thead>
            <tbody>
            <!-- Aquí puedes agregar más filas con datos dinámicos si lo necesitas -->
            </tbody>
          </table>
          </div>
        </div>
        </div>

        <!-- Tercer Card - Tareas Asignadas -->
        <div class="card">

        <div class="card-body">
          <h5 class="card-title">Tareas Asignadas</h5>

          <div class="table-responsive">
          <table class="table table-sm table-bordered" id="tareasTable">
            <thead>
            <tr>
              <th scope="col">Nombre</th>
              <th scope="col">Detalle</th>
              <th scope="col">Entregado</th>
              <th scope="col">Límite</th>
              <th scope="col">Nota</th>
            </tr>
            </thead>
            <tbody>
            <!-- Aquí puedes agregar más filas con datos dinámicos si lo necesitas -->
            </tbody>
          </table>
          </div>
        </div>
        </div>

      </div> <!-- Fin del Container -->
      </div> <!-- Fin del Modal Body -->
    </div> <!-- Fin del Modal Content -->
    </div> <!-- Fin del Modal Dialog -->
  </div> <!-- Fin del Modal -->



  <div class="modal fade" id="modalAsigna" tabindex="-1" aria-labelledby="modalAsignaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
    <div
      class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">

      <div class="modal-header">
      <h5 class="modal-title" id="modalAsignaLabel">Asignación</h5>
      <button type="button"
        class="btn-close {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'btn-close-white' : '' }}"
        data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
      <!-- Container -->
      <div class="container-fluid">
        <div id="cardsContainer"></div>
      </div>
      <!-- Fin del Container -->
      </div>
      <!-- Fin del Modal Body -->

    </div> <!-- Fin del Modal Content -->
    </div> <!-- Fin del Modal Dialog -->
  </div>




  <!-- Modal -->
  <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div
      class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
      <div class="modal-header">
      <h5 class="modal-title" id="commentModalLabel">Deja un comentario Para generar el reporte</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="EnviarDatos" method="POST" target="_blank" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
        <label for="commentText" class="form-label">Comentario</label>
        <textarea class="form-control" id="commentText" name="comentario" rows="4"
          placeholder="Escribe tu comentario aquí..."></textarea>
        </div>

        <!-- Descripción sobre el formato PNG -->
        <div class="mb-3">
        <p><strong>Nota:</strong> La firma debe ser cargada en formato <strong>PNG</strong>. Asegúrate de que el
          archivo sea una imagen clara y legible.</p>
        </div>

        <!-- Pasos y enlace a video tutorial -->
        <div class="mb-3">
        <p><strong>Pasos para subir una firma:</strong></p>
        <ol>
          <li>Escanea o toma una foto de tu firma en un papel blanco.</li>
          <li>Convierte la imagen a formato <strong>PNG</strong>. Puedes usar herramientas en línea como <a
            href="https://www.iloveimg.com/es/eliminar-fondo" target="_blank">iLoveIMG</a>.</li>
          <li>Selecciona la imagen en el campo de "Subir imagen".</li>
        </ol>
        <p>Para más detalles, mira este video tutorial sobre cómo preparar tu firma: <a
          href="https://www.youtube.com/watch?v=VIDEO_ID" target="_blank">Ver tutorial</a>.</p>
        </div>

        <!-- Campo para subir imagen PNG -->
        <div class="mb-3">
        <label for="imageUpload" class="form-label">Subir imagen (PNG)</label>
        <input class="form-control" type="file" id="imageUpload" name="imagen" accept="image/png">
        </div>

      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
      </form>
    </div>
    </div>
  </div>




@endsection