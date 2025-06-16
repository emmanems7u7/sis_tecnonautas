@section('adminConf')

    <!-- Pagos de Estudiantes -->
    <div class="row">
        <!-- Columna 1: Métodos de pago y opciones -->
        <div class="col-12 col-md-12 mb-4">
            <div class="card border rounded-3 shadow-sm">
                <div class="card-header text-center fw-semibold">
                    <i class="fas fa-credit-card"></i> Pagos de Estudiantes
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <ul class="list-group list-group-flush">
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('profesor'))
                                    <li class="list-group-item">
                                        <a href="javascript:void(0);" onclick="openModal()"
                                            class="d-block text-decoration-none">
                                            <i class="fas fa-cogs"></i> Métodos de pago
                                        </a>
                                    </li>
                                @endif

                                <li class="list-group-item" data-bs-toggle="modal" data-bs-target="#modalTipoPago"
                                    style="cursor: pointer;">
                                    <i class="fas fa-plus-circle"></i> Nuevo Tipo de Pago
                                </li>

                                <li class="list-group-item">
                                    <a href="{{ route('Pago.index') }}" class="d-block text-decoration-none">
                                        <i class="fas fa-chart-bar"></i> Reportes de pagos
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-8 text-center">
                            <h4>Tipos de pagos</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo de Pago</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tiposDePago as $tipo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $tipo->nombre }}</td>
                                            <td>{{ $tipo->activo == 1 ? 'Activo' : 'No Activo'}}</td>
                                            <td>
                                                <form id="formEliminar{{ $tipo->id }}"
                                                    action="{{ route('tipo_pago.destroy', $tipo->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmarEliminacion({{ $tipo->id }})">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                                <a href="{{ route('tipo_pago.estado', ['id' => $tipo->id]) }}"
                                                    class="btn btn-sm btn-warning mt-1">
                                                    <i class="fas fa-edit"></i> Cambiar estado
                                                </a>

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



    <!-- Paralelos -->
    <div class="col mb-4">
        <div class="card">
            <div class="card-header">
                Paralelos
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-users"></i><a class="text-black" href="{{route('Paralelos.index')}}">administrar
                            Paralelos</a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-calendar-alt"></i><a class="text-black" href="{{route('Paralelos.horarios')}}">
                            Horarios de clases</a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-bell"></i> Notificaciones de paralelos
                    </li>
                </ul>
            </div>
        </div>
    </div>



    <!-- Administración de Roles -->
    <div class="col mb-4">
        <div class="card">
            <div class="card-header">
                Administración de Roles
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-user-cog"></i> Administrar roles de usuarios
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-user-lock"></i> Permisos y accesos
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-history"></i> Historial de cambios
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @role('admin')
    <div class="col mb-4">
        <div class="card">
            <div class="card-header">
                Administración Registros
            </div>
            <div class="card-body">

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                    <li class="list-group-item">
                        <i class="fas fa-book"></i> <a href="{{route('cambioEstudiantes')}}"
                            class="text-black">Estudiantes</a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-graduation-cap"></i> <a href="{{route('cambioProfesores')}}"
                            class="text-black">Profesores</a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-cogs"></i> <a href="{{route('cambioAdmin')}}" class="text-black">Administrador</a>
                    </li>


                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endrole






    <!-- Modal para mostrar detalles de pago y añadir método de pago -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div
                class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Detalles de Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="myModalContent">
                    <!-- Aquí se mostrarán los detalles de los pagos -->
                    <p>No hay métodos de pago registrados.</p>
                </div>
                <!-- Formulario para añadir nuevo método de pago -->
                <div class="modal-body" id="addMetodoPagoForm" style="display: none;">
                    <h5>Añadir Método de Pago</h5>
                    <form id="addMetodoPagoForm">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <!-- Tipo de Pago -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select" name="tipo_pago" id="tipo_pago">
                                            <option value="" disabled selected>Seleccione un tipo de pago</option>
                                            @foreach($tiposDePago as $tipoPago)
                                                <option value="{{ $tipoPago->id }}">{{ $tipoPago->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label for="tipo_pago">Tipo de Pago</label>
                                    </div>
                                </div>

                                <!-- Detalle -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="detalle" name="detalle"
                                            placeholder="Detalle">
                                        <label for="detalle">Detalle</label>
                                    </div>
                                </div>

                                <!-- Banco -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="banco" name="banco" placeholder="Banco">
                                        <label for="banco">Banco</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="numeric" class="form-control" id="num_cuenta" name="num_cuenta"
                                            placeholder="num_cuenta">
                                        <label for="num_cuenta">Numero de Cuenta</label>
                                    </div>
                                </div>

                                <!-- Imagen -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input class="form-control" type="file" id="formFile"
                                            onchange="previewFile('#formFile', '#previewPhoto', '#photoHiddenInput1')"
                                            placeholder="Imagen">
                                        <label for="formFile">Imagen</label>
                                    </div>
                                </div>

                                <!-- Campo de correo electrónico -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Correo Electrónico">
                                        <label for="email">Email</label>
                                    </div>
                                </div>

                                <div id="previewPhoto" class="mb-3"></div>
                                <input type="hidden" id="photoHiddenInput1" name="imagen" value="">
                                <!-- Botón de Guardar -->
                                <div class="col-12 text-center">
                                    <button type="button" class="btn btn-primary"
                                        onclick="guardarMetodoPago()">Guardar</button>
                                </div>
                            </div>

                            <!-- Vista previa de la imagen -->


                        </div>
                    </form>

                </div>
                <!-- Botón para cambiar entre mostrar detalles y añadir método de pago -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="toggleMetodoPagoForm">Añadir Método de Pago</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTipoPago" tabindex="-1" aria-labelledby="modalTipoPagoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div
                class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTipoPagoLabel">Registrar Tipo de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tipos_pagos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del tipo de pago</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" checked>
                            <label class="form-check-label" for="activo">Activo</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <style>
        .custom-img-size {
            max-width: 150px;
            /* Ajusta el ancho según tus necesidades */
            height: auto;
            /* Mantiene la proporción de la imagen */
            object-fit: cover;
            /* Opcional: Cubre el área del contenedor sin distorsionar */
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>

        function confirmarEliminacion(tipoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar' + tipoId).submit();
                }
            });
        }
        function openModal() {
            $('#myModal').modal('show');
            // Realizar una solicitud fetch para obtener los datos de la tabla "pagos"
            fetch('/metodos-pago') // Ajusta la ruta según tu aplicación
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar los datos de los pagos');
                    }
                    return response.json();
                })
                .then(data => {

                    mostrarDatosPago(data);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function enviarFormulario() {
            var metodoPago = document.getElementById('metodoPago').value;

            fetch('/metodos-pago', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    metodoPago: metodoPago
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);



                    $('#myModal').modal('hide');
                })
                .catch(error => {
                    console.log(error);
                });
        }

        function mostrarDatosPago(data) {

            $('#myModalContent').empty();

            data.forEach(function (pago) {
                var detalle = pago.detalle;
                var banco = pago.banco;
                var imagen = pago.imagen;
                var email = pago.email;
                var num_cuenta = pago.numero_cuenta;


                // Crear una fila si no existe
                var row = $('#myModalContent .row');
                if (row.length === 0) {
                    row = $('<div class="row"></div>');
                    $('#myModalContent').append(row);
                }

                // Crear la tarjeta
                var card = $('<div class="col-12 col-sm-6 col-md-4 mb-4"></div>'); // 3 tarjetas por fila
                var cardBody = $('<div class="card border rounded-3 shadow-sm"></div>');

                cardBody.append('<h5 class="card-title">' + detalle + '</h5>');
                cardBody.append('<img src="{{ asset("") }}' + imagen + '" alt="Imagen de pago" class="card-img-top custom-img-size">');
                cardBody.append('<p class="card-text">Banco: ' + banco + '</p>');
                cardBody.append('<p class="card-text">Cuenta: ' + num_cuenta + '</p>');
                cardBody.append('<p class="card-text">Email: ' + email + '</p>');

                var cardButtons = $('<div class="card-buttons"></div>');
                cardButtons.append('<button type="button" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</button>');
                cardButtons.append('<button type="button" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</button>');

                card.append(cardBody);
                card.append(cardButtons);

                // Añadir la tarjeta a la fila
                row.append(card);
            });

        }

        // Función para mostrar el formulario de añadir método de pago y ocultar los detalles de pago
        function mostrarFormularioMetodoPago() {
            document.getElementById('myModalContent').style.display = 'none';
            document.getElementById('addMetodoPagoForm').style.display = 'block';
        }

        // Función para mostrar los detalles de pago y ocultar el formulario de añadir método de pago
        function mostrarDetallesPago() {
            document.getElementById('myModalContent').style.display = 'block';
            document.getElementById('addMetodoPagoForm').style.display = 'none';
        }

        // Evento para cambiar entre mostrar detalles y mostrar formulario al hacer clic en el botón correspondiente
        document.getElementById('toggleMetodoPagoForm').addEventListener('click', function () {
            if (this.textContent === 'Añadir Método de Pago') {
                mostrarFormularioMetodoPago();
                this.textContent = 'Mostrar Detalles de Pago';
            } else {
                mostrarDetallesPago();
                this.textContent = 'Añadir Método de Pago';
            }
        });

        // Función para procesar el formulario de añadir método de pago
        function guardarMetodoPago() {
            // Obtener los valores del formulario
            const tipoPagoSeleccionado = document.getElementById('tipo_pago').value;
            const detalle = document.getElementById('detalle').value;
            const banco = document.getElementById('banco').value;
            const imagen = document.getElementById('photoHiddenInput1').value;
            const email = document.getElementById('email').value;
            const num_cuenta = document.getElementById('num_cuenta').value;

            if (!detalle || !banco || !imagen || !email || !tipoPagoSeleccionado || !num_cuenta) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Error de datos',
                    text: 'Por favor, complete todos los campos',
                });

                return;
            }

            const formData = new FormData();
            formData.append('detalle', detalle);
            formData.append('banco', banco);
            formData.append('imagen', imagen);
            formData.append('email', email);
            formData.append('num_cuenta', num_cuenta);
            formData.append('tipoPagoSeleccionado', tipoPagoSeleccionado);

            fetch('/metodos/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',

                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al enviar el formulario');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {

                        $('#myModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Registro Exitoso',
                            text: data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en el registro',
                            text: data.message,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar el formulario. Por favor, inténtelo de nuevo.');
                });
        }

        function previewFile(inputId, previewId, hiddenInputId) {
            const preview = document.querySelector(previewId);
            preview.innerHTML = '';

            const photoHiddenInput = document.querySelector(hiddenInputId);
            const fileInput = document.querySelector(inputId).files[0];

            if (/\.(jpe?g|png)$/i.test(fileInput.name)) {
                const sizeInKB = Math.round(parseInt(fileInput.size) / 1024);
                const sizeLimit = 5000; // 500 KB

                if (sizeInKB > sizeLimit) {
                    alert(`Allowed file size: ${sizeLimit} KB.\nYour file size: ${sizeInKB} KB`);
                } else {
                    const reader = new FileReader();

                    reader.addEventListener("load", function () {
                        const image = new Image();
                        image.height = 100;
                        image.title = fileInput.name;
                        // convert image file to base64 string
                        image.src = this.result;
                        preview.appendChild(image);
                        photoHiddenInput.value = this.result;
                    }, false);

                    if (fileInput) {
                        reader.readAsDataURL(fileInput);
                    }
                }
            } else {
                alert('Allowed file types: jpeg, jpg, png');
            }
        }
    </script>
@endsection