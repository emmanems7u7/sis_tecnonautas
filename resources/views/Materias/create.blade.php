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
    <div class="alert alert-info shadow-sm" role="alert">
        <div class="row align-items-center">
            <!-- Columna de la imagen -->
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <img src="{{ asset('imagenes/tecnonautas.png') }}" alt="Imagen decorativa" class="img-fluid"
                    style="max-width: 150px;">
            </div>


            <!-- Columna del texto -->
            <div class="col-md-9 text-justify">
                <h4 class="alert-heading">
                    <i class="fas fa-chalkboard-teacher"></i> ¡Bienvenido a la sección para crear Cursos!
                </h4>

                <p>
                    Aquí podrás crear nuevos cursos para que los estudiantes
                    puedan inscribirse y aprender nuevas habilidades. Completa el formulario a continuación para agregar
                    un nuevo curso a nuestra plataforma.
                </p>
                <p>Es importante que registres los objetivos, caracteristicas, beneficios, estos mismos se mostraran en la
                    pagina publicitaria</p>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Nuevo Curso</div>
                    <div class="card-body">
                        <form id="courseForm" action="{{ route('asignacion.store') }}" enctype="multipart/form-data"
                            method="post">
                            @csrf
                            <div class="row">
                                <!-- Columna Izquierda -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        <span class="error-message" id="nombreError">Este campo es obligatorio.</span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" rows="5" name="descripcion"
                                            required></textarea>
                                        <span class="error-message" id="descripcionError">Este campo es obligatorio.</span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcionCorta" class="form-label">Descripción Corta</label>
                                        <textarea class="form-control" id="descripcionCorta" rows="3"
                                            name="descripcionCorta" required></textarea>
                                        <span class="error-message" id="descripcionCortaError">Este campo es
                                            obligatorio.</span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Características</label>
                                        <div id="caracteristicas">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="caracteristicas[]"
                                                    placeholder="Característica" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="agregarCampo('caracteristicas')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Objetivos</label>
                                        <div id="objetivos">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="objetivos[]"
                                                    placeholder="Objetivo" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="agregarCampo('objetivos')">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Beneficios</label>
                                        <div id="beneficios">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="beneficios[]"
                                                    placeholder="Beneficio" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="agregarCampo('beneficios')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipo" class="form-label">Tipo de Curso</label>
                                        <select name="tipo" class="form-select form-select-lg mb-3"
                                            onchange="mostrarCamposPago()" required>
                                            <option selected disabled>Seleccione tipo de curso</option>
                                            <option value="gratuito">Gratuito</option>
                                            <option value="pago">Pago</option>
                                        </select>
                                        <span class="error-message" id="tipoError">Debe seleccionar un tipo de curso.</span>
                                    </div>

                                    <div id="camposPago" style="display: none;">
                                        <div class="mb-3 animate__animated animate__fadeInDown">
                                            <label for="costo" class="form-label">Costo del Curso</label>
                                            <input type="text" name="costo" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Imagen</label>
                                        <input class="form-control" type="file" id="formFile" name="img1"
                                            onchange="previewFile('#formFile', '#previewPhoto', '#photoHiddenInput1')"
                                            required>
                                        <div id="previewPhoto"></div>

                                        <span class="error-message" id="fileError">Debe seleccionar una imagen válida (jpeg,
                                            jpg, png).</span>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input id="portadaInput" class="form-control" type="file" accept=".jpg,.jpeg,.png"
                                            onchange="previewFile('portadaPreview', 'portadaHiddenInput')"
                                            name="portada_imagen">
                                        <label for="portadaInput">Portada</label>
                                        <div id="portadaPreview" class="mt-2"></div>
                                        <input type="hidden" id="portadaHiddenInput" name="portada" value="">
                                    </div>
                                </div>
                            </div>

                            <!-- Botón Guardar -->
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