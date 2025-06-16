@extends('layouts.argon')


@section('content')



    <style>
        .custom-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .custom-card:hover {
            transform: scale(1.05);
        }
    </style>

    <style>
        .custom-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            position: relative;
        }

        .custom-card:hover {
            transform: scale(1.05);
        }

        .card-actions {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="alert alert-info shadow-sm" role="alert">
            <h4 class="alert-heading"><i class="fas fa-clipboard-list"></i> Creación de Exámenes</h4>

            <p><strong>Crear Examen:</strong> Esta herramienta permite crear exámenes de forma rápida y estructurada,
                similar a formularios de Google.</p>

            <p><strong>Tipos de Pregunta Disponibles:</strong> Puedes utilizar preguntas de tipo <em>párrafo</em>,
                <em>selección única</em> y <em>selección múltiple</em>, lo que te brinda flexibilidad para evaluar distintos
                tipos de conocimientos.
            </p>

            <p><strong>Validación de Preguntas Abiertas:</strong> Las preguntas de tipo <em>párrafo</em> deben ser revisadas
                manualmente por el docente una vez que el estudiante haya finalizado el examen, ya que requieren
                interpretación y validación posterior.</p>

            <p><strong>Gestión de Opciones:</strong> Para preguntas de <em>selección única</em> y <em>selección
                    múltiple</em>, debes añadir las opciones posibles utilizando el ícono <i class="fas fa-plus"></i>. Una
                vez agregadas, selecciona la opción correcta para las de selección única, y una o varias para las de
                selección múltiple.</p>

            <p><strong>Guardado Final:</strong> Tras configurar cualquiera de los tres tipos de preguntas, deberás guardar
                la información utilizando el botón <i class="fas fa-save"></i>.</p>
        </div>
    </div>




    <div class="container-fluid mt-5">
        <div id="preguntas-list" class="mt-4">
            <!-- Aquí se mostrarán las preguntas -->
        </div>

        <div class="container-fluid">


            @foreach ($preguntas as $pregunta)
                <div class="card mb-3 custom-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-2">
                            <small class="text-muted">Nota</small>
                        </div>
                        <h5 class="card-title" style="color: #4285f4;">{{ $pregunta->texto }}</h5>

                        @if ($pregunta->tipo === 'parrafo')
                            <div class="col-md-11">
                                <textarea disabled class="form-control" rows="3" placeholder="Escribe tu respuesta"
                                    style="background-color: #fff;"></textarea>
                            </div>
                        @elseif ($pregunta->tipo === 'opciones')
                            <div class="form-check">
                                @foreach($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input disabled class="form-check-input me-2" type="radio" name="opcion_{{ $pregunta->id }}"
                                            value="{{ $opcion->id }}" {{ $opcion->correcta ? 'checked' : '' }}>
                                        <label class="form-check-label" style="color: #007bff;">{{ $opcion->texto }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @elseif ($pregunta->tipo === 'casillas')
                            <div class="form-check">
                                @foreach($pregunta->opciones as $opcion)
                                    <div class="form-check">
                                        <input disabled class="form-check-input me-2" type="checkbox"
                                            name="opcion_{{ $pregunta->id }}[]" value="{{ $opcion->id }}" {{ $opcion->correcta ? 'checked' : '' }}>
                                        <label class="form-check-label" style="color: #28a745;">{{ $opcion->texto }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if(!$evaluacion->publicado)

                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('profesor'))

                            <div class="card-actions">

                                <a type="button" class="btn btn-sm btn-danger" id="modal_edit_usuario_button"
                                    onclick="confirmarEliminacion('eliminarPreguntaForm-{{ $pregunta->id }}', '¿Estás seguro de que deseas eliminar esta pregunta?')">
                                    <i class="fas fa-trash-alt"></i></a>

                                <form id="eliminarPreguntaForm-{{ $pregunta->id }}" method="POST"
                                    action="{{ route('preguntas.destroy', ['id' => $pregunta->id]) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>


                            </div>
                        @endif
                    @endif

                </div>
            @endforeach



            @if(!$evaluacion->publicado)
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('profesor'))
                    <div class="card shadow">
                        <div class="card-body">
                            <h2 class="card-title" style="color: #4285f4;"><i class="fas fa-plus-circle"></i> Crear Nueva Pregunta
                            </h2>
                            <form method="POST" action="{{ route('preguntas.store') }}">
                                @csrf
                                <input type="hidden" value="{{ old('id_e', $id_e) }}" name="id_e">

                                <div class="form-group">
                                    <textarea class="form-control @error('texto_pregunta') is-invalid @enderror" id="texto_pregunta"
                                        name="texto_pregunta" rows="3"
                                        placeholder="Texto de la Pregunta:">{{ old('texto_pregunta') }}</textarea>
                                    @error('texto_pregunta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <select class="form-control @error('tipo_pregunta') is-invalid @enderror" id="tipo_pregunta"
                                        name="tipo_pregunta">
                                        <option disabled value="-1" {{ old('tipo_pregunta') == '-1' ? 'selected' : '' }}>Seleccione un
                                            tipo de pregunta</option>
                                        <option value="parrafo" {{ old('tipo_pregunta') == 'parrafo' ? 'selected' : '' }}>Párrafo
                                        </option>
                                        <option value="opciones" {{ old('tipo_pregunta') == 'opciones' ? 'selected' : '' }}>Opciones
                                        </option>
                                        <option value="casillas" {{ old('tipo_pregunta') == 'casillas' ? 'selected' : '' }}>Casillas
                                        </option>
                                    </select>
                                    @error('tipo_pregunta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="opciones-field"
                                    style="{{ in_array(old('tipo_pregunta'), ['opciones', 'casillas']) ? '' : 'display:none;' }}">
                                    @if (is_array(old('opciones')))
                                        @foreach (old('opciones') as $i => $opcion)
                                            <div class="input-group mb-2">
                                                <input type="text" name="opciones[]"
                                                    class="form-control @error("opciones.$i") is-invalid @enderror" value="{{ $opcion }}"
                                                    placeholder="Opción {{ $i + 1 }}">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger eliminar-opcion"><i
                                                            class="fas fa-trash"></i></button>
                                                </div>
                                                @error("opciones.$i")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <button type="button" id="agregar-opcion" class="btn btn-success"><i
                                        class="fas fa-plus"></i></button>
                                <button type="submit" class="btn btn-primary" style="background-color: #4285f4;"><i
                                        class="fas fa-save"></i></button>
                            </form>

                        </div>
                    </div>


                @endif
                <!-- Sección para publicar el examen -->
                <div class="container-fluid mt-4">
                    <div class="alert alert-info shadow-sm" role="alert">
                        <h4 class="alert-heading"><i class="fas fa-clipboard-list"></i> Publicar el Examen</h4>

                        <p><strong>Publicar el examen:</strong> Una vez hayas configurado todas las preguntas del examen,
                            deberás hacer
                            clic en el botón "Publicar Examen", el cual almacenará todas las configuraciones de preguntas y
                            opciones
                            realizadas hasta el momento.</p>

                        <p>Al publicar el examen, este estará disponible para los estudiantes y podrás monitorear sus
                            respuestas. Es
                            importante asegurarte de que todas las preguntas estén correctamente configuradas antes de realizar
                            esta
                            acción, ya que una vez publicado, el examen ya no podrá ser modificado sin una intervención
                            administrativa.
                        </p>

                        <p><strong>Recuerda:</strong> Antes de hacer clic en el botón "Publicar Examen", asegúrate de haber
                            revisado
                            todos los detalles del examen, las preguntas y las opciones, para evitar errores o cambios
                            posteriores.</p>
                    </div>
                </div>

                <div class="container">
                    <button class="btn btn-success mt-3" id="publicarExamen">Publicar Examen</button>

                    <a class="btn btn-warning mt-3"
                        href=" {{ route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m]) }}"
                        class="btn btn-primary">Guardar como borrador </a>




                    <a type="button" class="btn btn-danger mt-3" id="modal_edit_usuario_button"
                        onclick="confirmarEliminacion('eliminarEvaluacionForm', '¿Estás seguro de que deseas eliminar esta evaluación?')">
                        Eliminar Examen</a>

                    <form id="eliminarEvaluacionForm" method="POST"
                        action="{{ route('evaluacion.delete', ['evaluacion' => $evaluacion, 'id_pm' => $id_pm, 'id_m' => $id_m]) }}"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>


                </div>

            @endif
        </div>

        <script>
            // Función para confirmar la acción de publicar
            $('#publicarExamen').click(function () {
                alertify.confirm(
                    '¿Estás seguro?',
                    '¿Quieres publicar este examen? Esta acción no se puede deshacer.',
                    function () {
                        // Confirmado
                        var url = "{{ route('evaluacion.publicar', ['id_e' => $id_e]) }}";

                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                alertify.success(data.message);
                                window.location.href = "{{ route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m]) }}";
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alertify.error('Ocurrió un error al publicar el examen.');
                            });

                    },
                    function () {
                        // Cancelado
                        alertify.error('Publicación cancelada.');
                    }
                ).set('labels', { ok: 'Sí, publicar', cancel: 'Cancelar' });
            });

            // Función para confirmar la acción de eliminar
            $('#eliminarExamen').click(function () {
                alertify.confirm('¿Estás seguro?',
                    '¿Quieres eliminar este examen? Esta acción no se puede deshacer.',
                    function () {
                        // Acción si confirma
                        alertify.success('El examen ha sido eliminado.');
                        // Puedes redirigir o ejecutar lógica de eliminación aquí:
                        // window.location.href = '/ruta/a/la/funcion/de/eliminar';
                    },
                    function () {
                        // Acción si cancela
                        alertify.error('Acción cancelada.');
                    }
                ).set('labels', { ok: 'Sí, eliminar', cancel: 'Cancelar' });
            });


        </script>
        <script>
            $(document).ready(function () {
                $('#tipo_pregunta').change(function () {
                    if ($(this).val() === 'opciones' || $(this).val() === 'casillas') {
                        $('#opciones-field').show();
                    } else {
                        $('#opciones-field').hide();
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $('#tipo_pregunta').change(function () {
                    if ($(this).val() === 'opciones' || $(this).val() === 'casillas') {
                        $('#opciones-field').show();
                        $('#agregar-opcion').show(); // Mostrar el botón
                    } else {
                        $('#opciones-field').hide();
                        $('#agregar-opcion').hide(); // Ocultar el botón
                    }
                });
            });
        </script>

        <script>

            function eliminarOpcion(opcionId) {
                // Eliminar el contenedor de la opción
                $('#opcion-' + opcionId).remove();
            }
            $(document).ready(function () {
                var optionCounter = 0;


                let currentType = ""; // Almacenamos el tipo de pregunta actual

                // Evento para detectar el cambio en el tipo de pregunta
                $('#tipo_pregunta').change(function () {
                    let nuevoTipo = $(this).val();

                    if (nuevoTipo !== currentType) {
                        // Cambiar el tipo de las opciones existentes sin borrarlas
                        $('#opciones-field .form-check').each(function () {
                            // Detectamos el tipo de input (checkbox o radio)
                            let input = $(this).find('input[type="checkbox"], input[type="radio"]');
                            let tipoPregunta = nuevoTipo === 'casillas' ? 'checkbox' : 'radio';

                            // Si el tipo de input no coincide, lo cambiamos
                            if (input.attr('type') !== tipoPregunta) {
                                input.attr('type', tipoPregunta);

                                // Si es checkbox, debe ser un array de opciones correctas
                                if (tipoPregunta === 'checkbox') {
                                    input.attr('name', 'opciones_correctas[]');
                                } else {
                                    input.attr('name', 'opcion_correcta');
                                }
                            }
                        });
                    }

                    // Actualizar el tipo de pregunta actual
                    currentType = nuevoTipo;
                });

                // Evento para agregar una nueva opción
                $('#agregar-opcion').click(function () {
                    let tipo = document.getElementById("tipo_pregunta").value;

                    if (tipo !== 'parrafo' && tipo !== '-1') {
                        var nuevaOpcionValue = optionCounter;
                        optionCounter++;

                        var tipoPregunta = $('#tipo_pregunta').val();
                        var inputType = tipoPregunta === 'casillas' ? 'checkbox' : 'radio';

                        // Creamos la opción en función del tipo seleccionado
                        var optionInput = '<input class="form-check-input me-2" type="' + inputType + '" name="opcion_correcta" value="' + nuevaOpcionValue + '">';

                        if (inputType === 'checkbox') {
                            optionInput = '<input class="form-check-input me-2" type="' + inputType + '" name="opciones_correctas[]" value="' + nuevaOpcionValue + '">';
                        }

                        // Añadimos la opción al formulario con el campo de texto
                        $('#opciones-field').append(
                            '<div class="form-check d-flex align-items-center mb-2 me-2" id="opcion-' + nuevaOpcionValue + '">' +
                            optionInput +
                            '<input type="text" class="form-control mt-2" name="opciones[]" placeholder="Nueva Opción">' +
                            '<button type="button" class="btn btn-danger btn-sm ms-2" onclick="eliminarOpcion(' + nuevaOpcionValue + ')">' +
                            '<i class="fas fa-trash"></i>' +
                            '</button>' +
                            '</div>'
                        );
                    } else {
                        alertify.warning('Seleccione un tipo de pregunta diferente a "Párrafo".');

                    }
                });

                // Asegúrate de que las opciones existentes sean visibles al cargar la página
                $(document).ready(function () {
                    if (currentType && currentType !== 'parrafo') {
                        $('#opciones-field .form-check').each(function () {
                            let input = $(this).find('input[type="checkbox"], input[type="radio"]');
                            let tipoPregunta = currentType === 'casillas' ? 'checkbox' : 'radio';
                            input.attr('type', tipoPregunta);

                            if (tipoPregunta === 'checkbox') {
                                input.attr('name', 'opciones_correctas[]');
                            } else {
                                input.attr('name', 'opcion_correcta');
                            }
                        });
                    }
                });


                $('form').submit(function () {
                    var opcionCorrectaValue = $('input[name="opcion_correcta"]:checked').val();
                    $('input[name="opcion_correcta_enviar"]').val(opcionCorrectaValue);
                });
            });

        </script>



        <script>

            $(document).ready(function () {
                // Validación al enviar el formulario
                $('form').submit(function (event) {
                    var textoPregunta = $('#texto_pregunta').val();
                    var tipoPregunta = $('#tipo_pregunta').val();
                    var opcionesCorrectas = $('input[name="opciones[]"]').map(function () { return $(this).val(); }).get();


                    let tipo = document.getElementById("tipo_pregunta").value;

                    if (tipo == -1) {
                        alertify.alert('Tipo de pregunta no seleccionado', 'Seleccione un tipo de pregunta').set('iconClass', 'alertify-warning');

                        event.preventDefault(); // Evitar que el formulario se envíe
                        return;
                    }

                    // Validar el campo de texto de la pregunta
                    if (textoPregunta.trim() === "") {
                        alertify.alert('Alerta', 'Por favor, ingrese el texto de la pregunta.').set('iconClass', 'alertify-warning');

                        event.preventDefault(); // Evitar que el formulario se envíe
                        return;
                    }

                    // Validar las opciones dependiendo del tipo de pregunta
                    if ((tipoPregunta === 'opciones' || tipoPregunta === 'casillas') && opcionesCorrectas.length === 0) {

                        alertify.alert('Alerta', 'Por favor, agregue al menos una opción.').set('iconClass', 'alertify-warning');


                        event.preventDefault(); // Evitar que el formulario se envíe
                        return;
                    }

                    // Si es una pregunta de tipo 'opciones' o 'casillas', asegurarse de que al menos una opción esté seleccionada
                    if ((tipoPregunta === 'opciones' || tipoPregunta === 'casillas') && $('input[name="opciones[]"]').filter(function () { return this.value.trim() !== ''; }).length === 0) {

                        alertify.alert('Alerta', 'Por favor, ingrese las opciones de respuesta.').set('iconClass', 'alertify-warning');

                        event.preventDefault(); // Evitar que el formulario se envíe
                        return;
                    }
                });

                // Validación cuando cambia el tipo de pregunta
                $('#tipo_pregunta').change(function () {
                    if ($(this).val() === 'opciones' || $(this).val() === 'casillas') {
                        $('#opciones-field').show();
                        $('#agregar-opcion').show(); // Mostrar el botón
                    } else {
                        $('#opciones-field').hide();
                        $('#agregar-opcion').hide(); // Ocultar el botón
                    }
                });
            });

        </script>
        </body>

        </html>


@endsection