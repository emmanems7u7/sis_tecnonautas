@extends('layouts.argon')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb"
            style="background-color: #f8f9fa; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"
                    style="color: #007bff; text-decoration: none;">Inicio</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('asignacion.index') }}"
                    style="color: #007bff; text-decoration: none;">Materias</a></li>

            <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Crear Modulo</li>
        </ol>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-center">
                        <h4 class="text-white">Agregar Módulo</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('modulo.store') }}" enctype="multipart/form-data" method="POST"
                            id="moduloForm">
                            @csrf
                            <input type="hidden" name="cursoid" value="{{$idcurso}}">

                            <!-- Selección de módulo -->
                            <div class="form-floating mb-3">
                                <select id="moduloSelect" name="nombre" class="form-select"
                                    aria-label="Selecciona el módulo">
                                    <option value="" selected>Selecciona</option>
                                    <option>Modulo 1</option>
                                    <option>Modulo 2</option>
                                    <option>Modulo 3</option>
                                    <option>Modulo 4</option>
                                    <option>Modulo 5</option>
                                    <option>Modulo 6</option>
                                </select>
                                <label for="moduloSelect">Selecciona módulo</label>
                            </div>

                            <!-- Descripción -->
                            <div class="form-floating mb-3">
                                <textarea id="descripcionTextarea" name="descripcion" class="form-control"
                                    rows="3"></textarea>
                                <label for="descripcionTextarea">Descripción</label>
                            </div>

                            <!-- Duración -->
                            <div class="form-floating mb-3">
                                <select id="duracionSelect" name="duracion" class="form-select"
                                    aria-label="Selecciona la duración">
                                    <option value="" selected>Selecciona</option>
                                    <option>1 mes</option>
                                    <option>2 meses</option>
                                    <option>3 meses</option>
                                    <option>4 meses</option>
                                    <option>5 meses</option>
                                    <option>6 meses</option>
                                </select>
                                <label for="duracionSelect">Duración</label>
                            </div>

                            <!-- Subida de foto -->
                            <div class="form-floating mb-3">
                                <input id="fotoInput" class="form-control" type="file" accept=".jpg,.jpeg,.png"
                                    onchange="previewFile('fotoPreview', 'fotoHiddenInput')">
                                <label for="fotoInput">Foto</label>
                                <div id="fotoPreview" class="mt-2"></div>
                                <input type="hidden" id="fotoHiddenInput" name="imagen" value="">
                            </div>


                            <!-- Checkbox para último módulo -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="ultimoModulo" name="ultimo_modulo"
                                    value="1">
                                <label class="form-check-label" for="ultimoModulo">
                                    Este es el último módulo de la materia
                                </label>
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


    <script>
        // Validar campos antes de enviar el formulario
        document.getElementById('moduloForm').addEventListener('submit', function (e) {
            const modulo = document.getElementById('moduloSelect').value;
            const descripcion = document.getElementById('descripcionTextarea').value.trim();
            const duracion = document.getElementById('duracionSelect').value;
            const imagen = document.getElementById('fotoHiddenInput').value;
            const portada = document.getElementById('portadaHiddenInput').value;
            const ultimo_modulo = document.getElementById('ultimoModulo').value;

            let errorMessages = [];

            if (!modulo) errorMessages.push("Debe seleccionar un módulo.");
            if (!descripcion) errorMessages.push("Debe ingresar una descripción.");
            if (!duracion) errorMessages.push("Debe seleccionar una duración.");
            if (!imagen) errorMessages.push("Debe subir una foto.");
            if (!portada) errorMessages.push("Debe subir una portada.");

            if (errorMessages.length > 0) {
                e.preventDefault(); // Previene el envío del formulario
                alert(errorMessages.join('\n'));
            }
        });

        // Función reutilizable para previsualizar archivos
        function previewFile(previewId, hiddenInputId) {
            const preview = document.getElementById(previewId);
            const hiddenInput = document.getElementById(hiddenInputId);
            const fileInput = event.target;
            const file = fileInput.files[0];

            preview.innerHTML = ''; // Limpia el preview

            if (file && /\.(jpe?g|png)$/i.test(file.name)) {
                const sizeLimit = 5000; // Tamaño máximo en KB
                const sizeInKB = Math.round(file.size / 1024);

                if (sizeInKB > sizeLimit) {
                    alert(`Tamaño máximo permitido: ${sizeLimit} KB.\nTu archivo: ${sizeInKB} KB`);
                    fileInput.value = ''; // Resetea el input si excede el tamaño
                } else {
                    const reader = new FileReader();
                    reader.onload = () => {
                        const img = new Image();
                        img.height = 100;
                        img.title = file.name;
                        img.src = reader.result;
                        preview.appendChild(img);
                        hiddenInput.value = reader.result;
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                alert('Solo se permiten archivos JPEG y PNG.');
                fileInput.value = ''; // Resetea el input si no es un formato válido
            }
        }
    </script>

@endsection