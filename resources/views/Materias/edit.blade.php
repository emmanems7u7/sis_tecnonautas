@extends('layouts.argon')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 8px;
        }

        .btn-agregar {
            border-radius: 0 5px 5px 0;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            display: none;
        }
    </style>

    <!-- Breadcrumb -->
    <div class="alert alert-info shadow-sm text-white" role="alert">
        <div class="row align-items-center">
            <!-- Columna de la imagen -->
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <img src="{{ asset('imagenes/tecnonautas.png') }}" alt="Imagen decorativa" class="img-fluid"
                    style="max-width: 150px;">
            </div>


            <!-- Columna del texto -->
            <div class="col-md-9 text-justify">
                <h4 class="alert-heading">
                    <i class="fas fa-chalkboard-teacher"></i> ¡Bienvenido a la sección para editar un Curso!
                </h4>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Editar Curso</div>
                    <div class="card-body">
                        <form id="courseForm" action="{{ route('asignacion.guardar', $asignacion) }}"
                            enctype="multipart/form-data" method="post">
                            @csrf

                            @include('Materias._form')

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary" onclick="validateForm()">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function validateForm() {
            let valid = true;

            // Validar nombre
            const nombre = document.getElementById('nombre');
            const nombreError = document.getElementById('nombreError');
            if (nombre.value.trim() === '') {
                nombreError.style.display = 'block';
                valid = false;
            } else {
                nombreError.style.display = 'none';
            }

            // Validar descripción
            const descripcion = document.getElementById('descripcion');
            const descripcionError = document.getElementById('descripcionError');
            if (descripcion.value.trim() === '') {
                descripcionError.style.display = 'block';
                valid = false;
            } else {
                descripcionError.style.display = 'none';
            }

            // Validar descripción corta
            const descripcionCorta = document.getElementById('descripcionCorta');
            const descripcionCortaError = document.getElementById('descripcionCortaError');
            if (descripcionCorta.value.trim() === '') {
                descripcionCortaError.style.display = 'block';
                valid = false;
            } else {
                descripcionCortaError.style.display = 'none';
            }

            // Validar tipo de curso
            const tipo = document.getElementsByName('tipo')[0];
            const tipoError = document.getElementById('tipoError');
            if (tipo.value === '') {
                tipoError.style.display = 'block';
                valid = false;
            } else {
                tipoError.style.display = 'none';
            }

            // Validar archivo de imagen
            const fileInput = document.getElementById('formFile');
            const fileError = document.getElementById('fileError');
            if (!fileInput.files.length || !/\.(jpe?g|png)$/i.test(fileInput.files[0].name)) {
                fileError.style.display = 'block';
                valid = false;
            } else {
                fileError.style.display = 'none';
            }

            if (valid) {
                document.getElementById('courseForm').submit();
            }
        }

        function mostrarCamposPago() {
            const select = document.getElementsByName('tipo')[0];
            const camposPago = document.getElementById('camposPago');

            if (select.value === 'pago') {
                camposPago.style.display = 'block';
            } else {
                camposPago.style.display = 'none';
            }
        }

        function agregarCampo(containerId) {
            const container = document.getElementById(containerId);
            const newInput = document.createElement('div');
            newInput.classList.add('input-group', 'mb-3');
            newInput.innerHTML = `
                                                                                                                        <input type="text" class="form-control" name="${containerId}[]" placeholder="${containerId.charAt(0).toUpperCase() + containerId.slice(1)}" required>
                                                                                                                        <button class="btn btn-outline-secondary" type="button" onclick="eliminarCampo(this)">-</button>
                                                                                                                    `;
            container.appendChild(newInput);
        }

        function eliminarCampo(button) {
            button.parentNode.remove();
        }

    </script>
@endsection