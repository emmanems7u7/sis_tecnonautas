@extends('layouts.argon')


@section('content')

    <style>
        h1 {
            text-align: center;
            color: #FF5722;
            margin-top: 20px;
            text-shadow: 2px 2px 4px #FFC107;
        }


        .card {
            border: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px !important;
            overflow: hidden;
        }

        .card-header {
            background-color: #3c6773;
            color: #FFF;
            text-align: center;
        }

        .card-header i {
            margin-right: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .check-icon {
            color: #4CAF50;
            margin-right: 10px;
        }

        .cross-icon {
            color: #F44336;
            margin-right: 10px;
        }

        .card-c {
            background-color: #237899;
        }

        /* Define una lista de colores de fondo aleatorios */
        .fondo-aleatorio-1 {
            color: #3498db;
        }

        .fondo-aleatorio-2 {
            color: #e74c3c;
        }

        .fondo-aleatorio-3 {
            color: #2ecc71;
        }

        .mensaje-animado {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            display: none;
            opacity: 0;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            0% {
                transform: translateY(10px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    @if($estado == 'inactivo')
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong> Estudiante inactivo:</strong> Revise el módulo de pagos y verifique si el estado del pago de este modulo es
            correcto.
        </div>


    @endif


    <img src="{{ asset($portada->portada) }}" alt="Foto de portada" class="img-fluid w-100 h-auto">


    <div class="row mt-3">

        <div class="col-md-5">
            @include('temas.contenido')
            @yield('contenido')
        </div>

        <div class="col-md-7">
            @include('temas.examenes')
            @yield('examenes')

            @include('temas.tareas')
            @yield('tareas')
        </div>

    </div>





    <!-- Modal temas -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div
                class="modal-content {{ auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="myModalLabel"></h5>
                </div>

                <div class="modal-body" id="myModalContent">
                    <form id="nombreForm">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombreT" name="nombreT">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="enviarFormulario()">Enviar</button>
                    </form>
                </div>

                <button class="btn btn-success mt-2" onclick="obtenerTemas({{ $id_m }})">
                    Seleccionar tema existente en el módulo
                </button>

                <div id="contenidoTemas"></div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="contenidoModal" tabindex="-1" role="dialog" aria-labelledby="contenidoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div
                class="modal-content {{ auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="contenidoModalLabel">{{ __('Subir Contenido') }}</h5>
                    <button type="button" class="close text-reset" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="contenidoForm">
                        @csrf
                        <input type="hidden" id="contenidoIdT" name="id_t" value="">

                        <div class="form-group">
                            <label for="nombre">{{ __('Nombre') }}</label>
                            <input type="text" id="nombrecontenido" name="nombrecontenido" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="documento">{{ __('Documento') }}</label>
                            <input type="file" id="documento" name="documento" class="form-control-file">
                        </div>

                        <div class="form-group">
                            <label for="video">{{ __('Video') }}</label>
                            <input type="file" id="video" name="video" class="form-control-file">
                        </div>

                        <div class="form-group">
                            <label for="enlace">{{ __('Enlace') }}</label>
                            <input type="text" id="enlace" name="enlace" class="form-control">
                        </div>

                        <button type="button" class="btn btn-primary" onclick="enviar()">
                            {{ __('Subir Contenido') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Agrega el enlace al archivo de jQuery antes de los enlaces a los archivos de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Fin del Modal -->
    <script>
        /** 
         function abrirModal(idT) {
                document.getElementById('modalIdT').value = idT;
                $('#subirArchivoModal').modal('show');
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('btnAbrirModal').addEventListener('click', function () {
                    var idT = this.getAttribute('data-id_t');
                    abrirModal(idT);
                });
            });
        */


        function openModal(index) {

            document.getElementById('myModalLabel').textContent = "Crear Tema";


            $('#myModal').modal('show');


        }
        function enviarFormulario() {
            var nombre = document.getElementById('nombreT').value;

            var id_pm = {!! json_encode($id_pm) !!};


            fetch('/tema/guardar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nombre: nombre,
                    id_pm: id_pm
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status == 'success') {
                        alertify.success(data.message);
                        $('#myModal').modal('hide');
                        location.reload();
                    }
                    const firstError = Object.values(data.errors)[0][0];
                    alertify.error(firstError);

                })
                .catch(error => {
                    console.log(error);
                });
        }


    </script>




    <!-- contenido js -->
    <script>
        function openContenidoModal(id_tema) {
            document.getElementById('contenidoIdT').value = id_tema;
            $('#contenidoModal').modal('show');
        }



        function enviar() {
            var nombrecont = document.getElementById('nombrecontenido').value;
            var id_t = document.getElementById('contenidoIdT').value;
            var documento = document.getElementById('documento').files[0];
            var video = document.getElementById('video').files[0];
            var enlace = document.getElementById('enlace').value;

            var formData = new FormData();
            formData.append('nombrecontenido', nombrecont);
            formData.append('id_t', id_t);
            formData.append('documento', documento);
            formData.append('video', video);
            formData.append('enlace', enlace);

            const loader = document.getElementById('loader');
            loader.style.display = '';

            fetch('/contenido/archivos', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData

            })
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';
                    if (data.status === 'success') {
                        alertify.success(data.message);
                        $('#contenidoModal').modal('hide');
                        location.reload();

                    } else {
                        const firstError = Object.values(data.errors)[0][0];
                        alertify.error(firstError);
                    }
                })

                .catch(error => {
                    loader.style.display = 'none';
                    console.log(error);
                });

        }

        function obtenerTemas(id_m) {
            const url = "{{ route('temas.obtener', ['id_m' => 'ID_REPLACE']) }}"
                .replace('ID_REPLACE', id_m);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    mostrarTemas(data);
                })
                .catch(error => console.error('Error:', error));
        }

        function mostrarTemas(response) {
            const contenidoDiv = document.getElementById('contenidoTemas');
            contenidoDiv.innerHTML = ''; // Limpiar el div antes de añadir nuevo contenido

            // Crear la tabla con estilo Bootstrap
            let tablaHtml = `
                                                                                                                                                                                                                                                                                                                    <div class="container mt-4">
                                                                                                                                                                                                                                                                                                                        <table class="table table-striped table-bordered">
                                                                                                                                                                                                                                                                                                                            <thead>
                                                                                                                                                                                                                                                                                                                                <tr>
                                                                                                                                                                                                                                                                                                                                    <th>Nombre del Tema</th>
                                                                                                                                                                                                                                                                                                                                    <th>Contenidos</th>
                                                                                                                                                                                                                                                                                                                                    <th>Acción</th>
                                                                                                                                                                                                                                                                                                                                </tr>
                                                                                                                                                                                                                                                                                                                            </thead>
                                                                                                                                                                                                                                                                                                                            <tbody>
                                                                                                                                                                                                                                                                                                                `;

            // Agregar filas a la tabla para cada tema
            response.forEach(tema => {
                let opcionesContenido = tema.contenidos.map(contenido => `<option value="${contenido}">${contenido}</option>`).join('');

                tablaHtml += `
                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                            <td>${tema.nombre_tema}</td>
                                                                                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                                                                                <select class="form-select">
                                                                                                                                                                                                                                                                                                                                    ${opcionesContenido}
                                                                                                                                                                                                                                                                                                                                </select>
                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                                                                                <button class="btn btn-primary" onclick="añadirTema('${tema.nombre_tema}','${tema.id_tema}')">Añadir</button>
                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                    `;
            });

            tablaHtml += `
                                                                                                                                                                                                                                                                                                                            </tbody>
                                                                                                                                                                                                                                                                                                                        </table>
                                                                                                                                                                                                                                                                                                                `;

            // Agregar paginación si hay páginas
            if (response.links) {
                tablaHtml += `
                                                                                                                                                                                                                                                                                                                        <nav aria-label="Page navigation">
                                                                                                                                                                                                                                                                                                                            <ul class="pagination">
                                                                                                                                                                                                                                                                                                                                ${response.links.map(link => {
                    let isActive = link.active ? 'active' : '';
                    let isDisabled = link.url ? '' : 'disabled';

                    return `
                                                                                                                                                                                                                                                                                                                                        <li class="page-item ${isActive} ${isDisabled}">
                                                                                                                                                                                                                                                                                                                                            <a class="page-link" href="${link.url || '#'}" ${link.url ? `onclick="event.preventDefault(); obtenerTemas(${link.label});"` : ''}>
                                                                                                                                                                                                                                                                                                                                                ${link.label}
                                                                                                                                                                                                                                                                                                                                            </a>
                                                                                                                                                                                                                                                                                                                                        </li>
                                                                                                                                                                                                                                                                                                                                    `;
                }).join('')}
                                                                                                                                                                                                                                                                                                                            </ul>
                                                                                                                                                                                                                                                                                                                        </nav>
                                                                                                                                                                                                                                                                                                                    `;
            }

            // Añadir el HTML al div
            contenidoDiv.innerHTML = tablaHtml;
        }

        function añadirTema(nombreTema, id_tema) {
            var id_pm = {!! json_encode($id_pm) !!};
            // Mostrar un mensaje de alerta

            alertify.confirm(
                '¿Estás seguro?',
                '¿Quieres añadir todo el contenido seleccionado?',

                function () {
                    const url = "{{ route('temas.contenidos.store', ['id_t' => 'ID_REPLACE', 'id_pm' => 'PM_REPLACE']) }}"
                        .replace('ID_REPLACE', id_tema)
                        .replace('PM_REPLACE', id_pm);

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            alertify.success(data.message);
                            $('#myModal').modal('hide');
                            location.reload();
                        })
                        .catch(error => console.error('Error:', error));
                },

                function () {
                    alertify.error('Operación cancelada');
                }
            ).set('labels', { ok: 'Sí', cancel: 'Cancelar' });




        }
    </script>
    <!-- eliminar -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successMessage = "{{ session('success') }}";
            var errorMessage = "{{ session('error') }}";
            var mensajeAnimado = document.getElementById('mensaje-animado');

            if (successMessage) {
                mensajeAnimado.textContent = successMessage;
                mensajeAnimado.style.display = 'block';
                mensajeAnimado.classList.add('fade', 'show'); // Agregar clases de animación de Bootstrap
            } else if (errorMessage) {
                mensajeAnimado.textContent = errorMessage;
                mensajeAnimado.style.display = 'block';
                mensajeAnimado.classList.add('fade', 'show', 'alert-danger'); // Agregar clases de animación de Bootstrap y estilo de alerta roja
            }
        });



    </script>

@endsection