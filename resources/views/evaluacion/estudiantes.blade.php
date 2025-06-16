@extends('layouts.argon')


@section('content')



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <div class="container-fluid mt-4">
        <div class="alert alert-info shadow-sm text-white" role="alert">
            <h4 class="alert-heading"><i class="fas fa-clipboard-list"></i> Instrucciones para Resolver el Examen</h4>

            <p><strong>Resolución del Examen:</strong> Para completar el examen, deberás responder a las preguntas de
                acuerdo con los tipos de preguntas que se presentan: <em>párrafo</em>, <em>selección única</em>, y
                <em>selección múltiple</em>.
            </p>

            <p><strong>Preguntas Abiertas:</strong> Para las preguntas de tipo <em>párrafo</em>, deberás escribir una
                respuesta completa en el campo correspondiente. Recuerda que estas respuestas serán revisadas manualmente
                por el docente, así que asegúrate de expresar tu opinión o respuesta de manera clara.</p>

            <p><strong>Selección Única:</strong> En las preguntas de <em>selección única</em>, deberás elegir una sola
                opción entre las disponibles. Solo puedes seleccionar una respuesta, así que elige la opción que creas que
                es correcta.</p>

            <p><strong>Selección Múltiple:</strong> Para las preguntas de <em>selección múltiple</em>, puedes seleccionar
                una o varias opciones que consideres correctas. Marca todas las respuestas que sean apropiadas para cada
                pregunta.</p>

            <p><strong>Revisión:</strong> Antes de enviar el examen, revisa todas tus respuestas para asegurarte de que has
                completado todas las preguntas de acuerdo con las instrucciones. Una vez que envíes el examen, no podrás
                modificar tus respuestas.</p>

            <p><strong>Envío del Examen:</strong> Cuando hayas terminado de responder todas las preguntas, haz clic en el
                botón "Enviar Respuestas". Asegúrate de revisar bien cada respuesta antes de enviarlo, ya que después no
                podrás
                realizar cambios.</p>
        </div>
    </div>


    <div class="container-fluid mt-5">
        <div id="preguntas-list" class="mt-4">
            <!-- Aquí se mostrarán las preguntas -->
        </div>

        <div class="container-fluid">
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

            <form id="form_examen" aria-required=""
                action="{{ route('respuestas.store', ['id_pm' => $id_pm, 'id_m' => $id_mod]) }}" method="POST">
                @csrf
                <input type="hidden" name="id_e" value="{{ $id_m }}">

                @foreach ($preguntas as $pregunta)
                    <div class="card mb-3 custom-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-2">
                                <small class="text-muted">Nota</small>
                            </div>
                            <h5 class="card-title" style="color: #4285f4;">{{ $pregunta->texto }}</h5>

                            @if ($pregunta->tipo === 'parrafo')
                                <div class="col-md-11">
                                    <textarea class="form-control" name="respuestas[{{ $pregunta->id }}]" rows="3"
                                        placeholder="Escribe tu respuesta" style="background-color: #fff;">
                                                    {{ old('respuestas.' . $pregunta->id) }}
                                                </textarea>
                                </div>
                            @elseif ($pregunta->tipo === 'opciones')
                                <div class="form-check">
                                    @foreach($pregunta->opciones as $opcion)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->id }}]"
                                                value="{{ $opcion->id }}" {{ old('respuestas.' . $pregunta->id) == $opcion->id ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $opcion->texto }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif ($pregunta->tipo === 'casillas')
                                <div class="form-check">
                                    @foreach($pregunta->opciones as $opcion)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="respuestas[{{ $pregunta->id }}][]"
                                                value="{{ $opcion->id }}" {{ in_array($opcion->id, old('respuestas.' . $pregunta->id, [])) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $opcion->texto }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="container">
                    <button type="submit" class="btn btn-primary" id="enviar_examen">Enviar Respuestas</button>
                </div>
            </form>


        </div>


        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script>

            $('#enviar_examen').click(function (event) {
                event.preventDefault();
                alertify.confirm(
                    '¿Estás seguro?',
                    '¿Quieres enviar el examen? Esta acción no se puede deshacer.',
                    function () {
                        // Acción cuando el usuario confirma
                        document.getElementById('form_examen').submit();
                    },
                    function () {
                        // Acción cuando el usuario cancela (opcional)
                        alertify.error('Envío cancelado');
                    }
                    ).set('labels', { ok: 'Sí, enviar', cancel: 'Cancelar' })
                    .set('movable', false); // Opcional: no permitir mover la ventana
            });


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
            $(document).ready(function () {
                var optionCounter = 0;

                $('#agregar-opcion').click(function () {
                    var nuevaOpcionValue = optionCounter;
                    optionCounter++;

                    var tipoPregunta = $('#tipo_pregunta').val();
                    var inputType = tipoPregunta === 'casillas' ? 'checkbox' : 'radio';

                    var optionInput = '<input class="form-check-input" type="' + inputType + '" name="opcion_correcta" value="' + nuevaOpcionValue + '">';

                    if (inputType === 'checkbox') {
                        optionInput = '<input class="form-check-input" type="' + inputType + '" name="opciones_correctas[]" value="' + nuevaOpcionValue + '">';
                    }

                    $('#opciones-field').append(
                        '<div class="form-check">' +
                        optionInput +
                        '<input type="text" class="form-control" name="opciones[]" placeholder="Nueva Opción">' +
                        '</div>'
                    );
                });

                $('form').submit(function () {
                    var opcionCorrectaValue = $('input[name="opcion_correcta"]:checked').val();
                    $('input[name="opcion_correcta_enviar"]').val(opcionCorrectaValue);
                });
            });

        </script>



        <script>
            function actualizarListaPreguntas() {
                $.ajax({
                    url: '{{ route('preguntas.list', ['id_e' => $id_m]) }}', // Cambia la ruta según tu configuración
                    type: 'GET',
                    success: function (response) {
                        $('#preguntas-list').html(response);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $(document).ready(function () {
                actualizarListaPreguntas();

                $('#agregar-pregunta').click(function () {
                    // ... Código para agregar pregunta (puede incluir llamada a actualizarListaPreguntas())
                });
            });
        </script>
        </body>

        </html>


@endsection