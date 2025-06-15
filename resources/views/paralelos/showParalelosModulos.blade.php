@extends('layouts.argon')

@section('content')

  <style>
    .card {
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-body {
    padding: 1.25rem;
    }

    .card-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 1rem;
    }

    .details-item {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    }

    .details-label {
    font-weight: bold;


    }

    #iconoInicio,
    #iconoFin {
    color: black;
    }

    #alertaInicio,
    #alertaFin {
    position: absolute;
    color: red;
    font-size: 12px;
    top: 100%;
    left: 0;
    transform: translateY(5px);
    display: none;
    }

    #snackbar {
    visibility: hidden;
    min-width: 250px;
    margin-left: -125px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index: 9999;
    left: 50%;
    bottom: 30px;
    font-size: 17px;
    }

    #snackbar.show {
    visibility: visible;
    }

    #cerrarSnackbar {
    position: absolute;
    top: 8px;
    right: 8px;
    color: #fff;
    background-color: transparent;
    border: none;
    cursor: pointer;
    }
  </style>


  <div id="snackbar" class="hidden">
    <span id="mensaje"></span>
    <button id="cerrarSnackbar">&times;</button>
  </div>
  <!-- Tarjeta para mostrar detalles de un paralelo existente -->
  <div class="row">
    @if(!empty($datosParalelos))
    @foreach ($datosParalelos as $nombreParalelo => $detalleParalelo)
    <div class="col-md-6 mt-2">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="fas fa-chalkboard-teacher me-2"></i>
      <h5 class="mb-0 text-white">Paralelo {{ $nombreParalelo }}</h5>

      @if ($detalleParalelo['activo'] == 1)
      <a onclick="finalizar('{{ $detalleParalelo['id_p'] }}', '{{ $id_a }}')" class="btn btn-warning btn-sm ms-auto"
      title="Finalizar Paralelo">
      <i class="fas fa-flag-checkered"></i> Finalizar Paralelo
      </a>
    @endif
      </div>


      <div class="card-body">
      <p class="text-muted">
      <i
      class="fas {{ $detalleParalelo['activo'] == 1 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
      {{ $detalleParalelo['activo'] == 1 ? 'Activo' : 'Inactivo' }}
      </p>

      <hr class="my-1">

      <div class="details-info">
      <p class="details-item mb-2">
      <i class="fas fa-user-tie"></i> <strong>Profesor:</strong> {{ $detalleParalelo['profesor'] }}
      </p>

      <p class="details-item mb-1"><i class="far fa-clock"></i> <strong>Horarios:</strong></p>
      <div class="row gx-2 gy-1">
      @foreach ($detalleParalelo['horarios'] as $dia => $horario)
      <div class="col-6 col-md-3">
      <p class="details-item mb-0">
      <i class="fas fa-calendar"></i> <strong>{{ $dia }}:</strong> {{ $horario['hora_inicio'] }} -
      {{ $horario['hora_fin'] }}
      </p>
      </div>
      @endforeach
      </div>

      @role('admin')
      <p class="details-item mb-1">
      <i class="fas fa-users"></i> <strong>Cupo Máximo:</strong> {{ $detalleParalelo['cupo'] }}
      </p>
      @endrole

      <p class="details-item mb-0">
      <i class="fas fa-user-graduate"></i> <strong>Estudiantes Inscritos:</strong>
      {{ $detalleParalelo['inscritos'] }}
      </p>
      </div>

      <!-- Botones -->
      <div class="d-flex flex-wrap gap-2 mt-3">
      <a href="{{ route('modulos.temas.show', ['id_pm' => $detalleParalelo['id_p'], 'id_m' => $id_m]) }}"
      class="btn btn-primary btn-sm" title="Ver Contenido">
      <i class="fas fa-book-open"></i> Ver Contenido
      </a>

      @role('admin')
      <a href="{{ route('modulos.temas.admin', ['id_a' => $id_a, 'id_m' => $id_m, 'id_p' => $detalleParalelo['id_p']]) }}"
      class="btn btn-info btn-sm" title="Administrar">
      <i class="fas fa-cogs"></i> Administrar
      </a>

      <a href="{{ route('ParaleloHorario.edit', ['id' => $detalleParalelo['id_p'], 'id_a' => $id_a, 'id_m' => $id_m]) }}"
      class="btn btn-warning btn-sm" title="Editar">
      <i class="fas fa-edit"></i> Editar
      </a>

      <a type="button" class="btn btn-danger btn-sm" id="modal_edit_usuario_button"
      onclick="confirmarEliminacion('eliminarParaleloForm', '¿Estás seguro de que deseas eliminar este paralelo?')">
      <i class="fas fa-trash"></i>Eliminar
      </a>

      <form id="eliminarParaleloForm" method="POST"
      action="{{ route('Paralelo_modulo.delete', ['id' => $detalleParalelo['id_p'], 'id_a' => $id_a, 'id_m' => $id_m]) }}"
      style="display: none;">
      @csrf
      @method('DELETE')
      </form>
      @endrole



      @role('profesor|admin')
      <a class="btn btn-success btn-sm" onclick="genera_asistencia('{{ $detalleParalelo['id_p'] }}')"
      title="Generar Asistencia">
      <i class="fas fa-user-check"></i> Generar Asistencia
      </a>

      <a class="btn btn-info btn-sm" onclick="mostrarModal('{{ $detalleParalelo['id_p'] }}')"
      title="Ver Asistencia">
      <i class="fas fa-clipboard-list"></i> Ver Asistencia
      </a>
      @endrole
      </div>
      </div>
    </div>
    </div>
    @endforeach
    @endif

    <!-- Modal genera asistencia -->

    <!-- Modal asistencia -->
    <div class="modal fade" id="asistenciasModal_ver" tabindex="-1" aria-labelledby="asistenciasModal_verLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div
      class="modal-content {{ auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
      <div class="modal-header">
        <h5 class="modal-title" id="asistenciasModal_verLabel">Asistencia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div id="contenedor" class="leyenda-asistencia">
        <p><span class="cuadro-falta"></span> Falta</p>
        <p><span class="cuadro-atraso"></span> Atraso</p>
        <p><span class="cuadro-asistencia"></span> Asistencia</p>
        </div>
        <div id="contenedorTabla"></div> <!-- Contenedor para la tabla -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
      </div>
    </div>
    </div>

    <!-- Tarjeta para crear un nuevo paralelo -->
    @role('admin')
    @include('paralelos.formNuevosParalelos')
    @yield('formParalelos')
    @endrole

  </div>

  <style>
    .leyenda-asistencia p {
    font-size: 14px;
    margin: 5px 0;
    }

    .leyenda-asistencia span {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 10px;
    }

    .cuadro-falta {
    background-color: red;
    }

    .cuadro-atraso {
    background-color: yellow;
    }

    .cuadro-asistencia {
    background-color: green;
    }

    td.color-falta {
    background-color: red;
    }

    td.color-atraso {
    background-color: yellow;
    }

    td.color-asistencia {
    background-color: green;
    }
  </style>
  <script>


    function generarTabla(data) {
    const fechasUnicas = [...new Set(data.asistencias.map(a => a.fecha))];

    const tabla = document.createElement("table");
    tabla.className = "table table-bordered";


    tabla.innerHTML += `
    <thead>
      <tr>
      <th>Nombre Completo</th>
      ${fechasUnicas.map(fecha => `<th>${fecha}</th>`).join("")}
      </tr>
    </thead>
    <tbody>
      ${data.usuarios.map(usuario => {
      const asistenciasUsuario = data.asistencias.filter(a => a.user_id === usuario.id);
      return `
      <tr>
      <td>${usuario.nombre_completo}</td>
      ${fechasUnicas.map(fecha => {
      const asistencia = asistenciasUsuario.find(a => a.fecha === fecha)?.asistencia || "-";
      let color = '';
      switch (asistencia) {
        case 'falta':
        color = 'background-color: red;';
        break;
        case 'atraso':
        color = 'background-color: yellow;';
        break;
        case 'asistencia':
        color = 'background-color: green;';
        break;
        default:
        color = ''; // Sin color si no es ninguno de los tres valores
      }
      return `<td style="${color}"></td>`; // Solo pinta la celda sin texto
      }).join("")}
      </tr>
      `;
    }).join("")}
    </tbody>
    `;

    return tabla;
    }

    function mostrarModal(idPm) {
    let url = '/lista_asistencia/{id_pm}';
    url = url.replace('{id_pm}', idPm);
    fetch(url) //Reemplaza con tu endpoint
      .then(response => response.json())
      .then(data => {
      // Generar la tabla con los datos recibidos
      const tabla = generarTabla(data);
      const contenedorTabla = document.getElementById("contenedorTabla");
      contenedorTabla.innerHTML = ""; // Limpiar contenido previo
      contenedorTabla.appendChild(tabla); // Agregar tabla al contenedor

      // Mostrar el modal
      const modal = new bootstrap.Modal(document.getElementById("asistenciasModal_ver"));
      modal.show();
      })
      .catch(error => {
      console.error("Error al obtener los datos:", error);
      });
    }
    function abrirModal(id_pm) {
    // Rellenamos el campo oculto con el id_pm del estudiante
    document.getElementById('id_pm').value = id_pm;

    // Abre el modal (si estás usando Bootstrap, el modal se abrirá automáticamente)
    $('#asistenciaModal').modal('show');
    }

    // document.getElementById('formAsistencia').addEventListener('submit', function (event) {
    function genera_asistencia(id_pm) {
    event.preventDefault(); // Evita el envío normal del formulario
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var formData = new FormData();
    formData.append('id_pm', id_pm);
    // Envío de la solicitud fetch
    fetch("/crear/asistencia", {

      method: 'POST',
      headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',

      },
      body: formData
    })
      .then(response => response.json())
      .then(data => {
      // Aquí se manejan las respuestas de la solicitud
      if (data.success) {
        // Si la respuesta es exitosa, mostramos el mensaje con SweetAlert
        Swal.fire({
        icon: 'success',
        title: 'Asistencia Generada',
        text: 'La asistencia se ha registrado correctamente.',
        });
        // Cerramos el modal después de un segundo
        setTimeout(() => $('#asistenciaModal').modal('hide'), 1000);
      } else {
        // Si hay algún error
        Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
        });
      }
      })
      .catch(error => {
      // Si ocurre un error en la solicitud fetch
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al generar la asistencia.',
      });
      });
    }
    //  });

    function finalizar(id_pm, id_a) {

    const url = `{{ route('modulos.temas.finalizar', ['id_pm' => '__id_pm__']) }}`
      .replace('__id_pm__', id_pm);

    // Confirmación con SweetAlert2
    Swal.fire({
      title: '¿Estás seguro de realizar esta accion?',
      text: "Al finalizar el paralelo se generará las notas de tus estudiantes y su promedio final. Por lo que esta acciòn es importante hacerlo al finalizar el modulo",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, finalizar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
      // Ejecutar fetch si el usuario confirma
      fetch(url)
        .then(response => response.json())

        .then(data => {
        Swal.fire({
          icon: data.status,
          title: data.title,
          text: data.message,
          showCancelButton: data.button === 1, // Mostrar botón de cancelación solo si `data.button` es 1
          confirmButtonText: data.button === 1 ? 'Sí, asignar' : 'Cerrar', // Cambia el texto según el valor de `data.button`
          cancelButtonText: data.button === 1 ? 'No, lo haré después' : undefined, // Solo se usa si hay botón de cancelación
          confirmButtonColor: '#007bff',
          cancelButtonColor: '#d33'
        }).then((result) => {
          if (result.isConfirmed) {

          //if (data.button == 1) {
          const urlAprobados = `{{ route('asignar.aprobados', ['id_pm' => '__id_pm__', 'id_m' => '__id_m__']) }}`
            .replace('__id_pm__', id_pm)
            .replace('__id_m__', id_a);


          fetch(urlAprobados)

            .then(response => response.json())
            .then(data => {
            Swal.fire({
              icon: data.status,
              title: data.title,
              text: data.message
            }).then((result) => {
              // Verificar si se cierra la notificación (ya sea con el botón de confirmación o de cerrar)
              if (result.isConfirmed || result.isDismissed) {
              // Recargar la página
              window.location.reload();
              }
            });
            })
            .catch(error => {
            // Maneja errores en la solicitud
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: error.message
            });
            });
          // }


          } else {
          Swal.fire({
            icon: 'success',
            title: 'Cancelo la asignacion automatica',
            text: 'Puede realizarlo manualmente en la administracion de este modulo'
          });
          }
        });
        })
        .catch(error => {
        // Mostrar mensaje de error con SweetAlert2
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al finalizar.'
        });
        });
      }
    });
    }
  </script>



  <script>
    function validarHoras() {
    var horaInicio = document.getElementsByName('horaInicio[]')[0].value;
    var horaFin = document.getElementsByName('horaFin[]')[0].value;
    var iconoInicio = document.getElementById('iconoInicio');
    var iconoFin = document.getElementById('iconoFin');
    var hi = document.getElementById('hi');
    var hf = document.getElementById('hf');

    if (horaInicio === '' || horaFin === '') {
      iconoInicio.style.display = 'block';
      hi.style.display = 'block';
      hf.style.display = 'block';
      hi.style.color = 'red';
      hf.style.color = 'red';

      iconoInicio.style.color = 'red'; // Cambia el color del icono
      iconoFin.style.display = 'block';
      iconoFin.style.color = 'red'; // Cambia el color del icono

      mostrarSnackbar('Por favor, ingresa la hora de inicio y la hora de fin.');
      return false; // Evita que se envíe el formulario si falta la hora
    } else {
      iconoInicio.style.display = 'none';
      iconoInicio.style.color = 'black'; // Restaura el color del icono
      iconoFin.style.display = 'none';
      iconoFin.style.color = 'black';
      hi.style.color = 'black';
      hf.style.color = 'black';
    }

    // Si se proporciona la hora, permite enviar el formulario
    return true;
    }

    function mostrarSnackbar(mensaje) {
    var snackbar = document.getElementById("snackbar");
    var mensajeElemento = document.getElementById("mensaje");
    mensajeElemento.innerHTML = mensaje;
    snackbar.className = "show";
    setTimeout(function () {
      snackbar.className = snackbar.className.replace("show", "");
    }, 3000); // Oculta el snackbar después de 3 segundos
    }

    document.getElementById("cerrarSnackbar").addEventListener("click", function () {
    var snackbar = document.getElementById("snackbar");
    snackbar.className = snackbar.className.replace("show", "");
    });
  </script>

@endsection