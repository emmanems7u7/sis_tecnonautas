@section('adminConf')

    <!-- Pagos de Estudiantes -->
    <div class="row">
        <!-- Columna 1: Métodos de pago y opciones -->
        <div class="col-12 col-md-12 mb-4">
            <div class="card border rounded-3 shadow-sm">
                <div class="card-header text-center fw-semibold">
                    <i class="fas fa-credit-card"></i> Pagos de Estudiantes
                </div>
                <div class="card-body rounded-3 shadow-sm">
                    <div class="row">
                        <div class="col-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item" data-bs-toggle="modal" data-bs-target="#modalTipoPago"
                                    style="cursor: pointer;">
                                    <i class="fas fa-plus-circle"></i> Nuevo Tipo de Pago
                                </li>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('profesor'))
                                    <li class="list-group-item">
                                        <a href="javascript:void(0);" onclick="openModal()"
                                            class="d-block text-decoration-none">
                                            <i class="fas fa-cogs"></i> Métodos de pago
                                        </a>
                                    </li>
                                @endif


                                <li class="list-group-item">
                                    <a href="{{ route('Pago.index') }}" class="d-block text-decoration-none">
                                        <i class="fas fa-chart-bar"></i> Reportes de pagos
                                    </a>
                                </li>


                            </ul>
                        </div>
                        <div class="col-8 text-center">
                            <h4>Tipos de pagos</h4>
                            <table class="table">
                                <thead class="table-dark">
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

    <div class="row">
        <!-- Paralelos -->
        <div class="col-md-6 mb-4">
            <div class="card border rounded-3 shadow-sm">
                <div class="card-header">
                    Paralelos
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-users"></i><a class="text-black"
                                href="{{route('Paralelos.index')}}">administrar
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



        <div class="col-md-6 mb-4">
            <div class="card border rounded-3 shadow-sm">
                <div class="card-header">
                    Administración Registros en pagina principal
                </div>
                <div class="card-body">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item {{ $registro->activo === 1 ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <a href="{{ route('cambioEstudiantes') }}"
                                class="{{ $registro->activo === 1 ? 'text-white' : 'text-black' }}">
                                Estudiantes
                            </a>
                        </li>
                        <li class="list-group-item {{ $registro->activo === 2 ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap"></i>
                            <a href="{{ route('cambioProfesores') }}"
                                class="{{ $registro->activo === 2 ? 'text-white' : 'text-black' }}">
                                Profesores
                            </a>
                        </li>




                    </ul>
                </div>
            </div>
        </div>
    </div>









    <!-- Modal para mostrar detalles de pago y añadir método de pago -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Detalles de Pago</h5>
                    <button type="button" class="btn-close text-black" data-bs-dismiss="modal" aria-label="Cerrar"></button>
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
            <div class="modal-content">
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
            alertify.confirm(
                '¿Estás seguro?',
                'Esta acción no se puede deshacer.',
                function () {
                    document.getElementById('formEliminar' + tipoId).submit();
                },
                function () {
                    // Cancelado, no se hace nada
                }
            ).set('labels', { ok: 'Sí, eliminar', cancel: 'Cancelar' }).set('closable', false);
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
                let row = $('#myModalContent .row');
                if (row.length === 0) {
                    row = $('<div class="row g-3"></div>'); // g-3 para espacio entre columnas
                    $('#myModalContent').append(row);
                }

                // Crear la tarjeta
                let cardCol = $('<div class="col-12 col-sm-6 col-md-4"></div>');
                let card = $('<div class="card h-100 shadow-sm border rounded-3"></div>');

                let cardImage = $('<img>', {
                    src: '{{ asset("") }}' + imagen,
                    class: 'card-img-top',
                    alt: 'Imagen de pago'
                });

                let cardBody = $('<div class="card-body"></div>');
                cardBody.append('<h5 class="card-title">' + detalle + '</h5>');
                cardBody.append('<p class="card-text mb-1"><strong>Banco:</strong> ' + banco + '</p>');
                cardBody.append('<p class="card-text mb-1"><strong>Cuenta:</strong> ' + num_cuenta + '</p>');
                cardBody.append('<p class="card-text"><strong>Email:</strong> ' + email + '</p>');

                let cardFooter = $('<div class="card-footer bg-transparent border-top-0 d-flex justify-content-between"></div>');
                cardFooter.append('<button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Editar</button>');
                cardFooter.append('<button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>');

                card.append(cardImage, cardBody, cardFooter);
                cardCol.append(card);

                // Agregar la tarjeta a la fila
                row.append(cardCol);
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
            const inputFile = document.getElementById('formFile');

            const email = document.getElementById('email').value;
            const num_cuenta = document.getElementById('num_cuenta').value;

            const archivo = inputFile.files[0];



            if (!detalle || !banco || !archivo || !email || !tipoPagoSeleccionado || !num_cuenta) {

                alertify.warning('Por favor, complete todos los campos');
                return;
            }

            const formData = new FormData();
            formData.append('detalle', detalle);
            formData.append('banco', banco);
            if (archivo) {
                formData.append('imagen', archivo);
            }

            formData.append('email', email);
            formData.append('num_cuenta', num_cuenta);
            formData.append('tipoPagoSeleccionado', tipoPagoSeleccionado);

            fetch('/metodos/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        $('#myModal').modal('hide');

                        alertify.success(data.message);
                        location.reload()
                    } else {
                        alertify.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alertify.error('Error al enviar el formulario. Por favor, inténtelo de nuevo.');
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