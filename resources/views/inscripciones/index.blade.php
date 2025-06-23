@extends('layouts.argon')

@section('content')
    <form action="{{ route('inscripcion_adm.store') }}" method="POST">
        @csrf

        <div class="card mt-3 shadow-sm">
            <div class="card-body">
                <h5>Estudiantes</h5>
                <select id="estudiantes" class="form-control" name="estudiante">
                    <option value="">Seleccione</option>
                    @foreach($estudiantes as $estudiante)
                        <option value="{{ $estudiante->id }}">{{ $estudiante->usuario_nombres }} {{ $estudiante->usuario_app }}
                            {{ $estudiante->usuario_apm }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card mt-3 shadow-sm">
            <div class="card-body">

                <h5>Cursos</h5>

                <select id="curso_selector" class="form-control" name="curso">
                    <option value="">Seleccione un curso</option>
                    @foreach($asignacionesPago as $pago)
                        <option value="{{ $pago->id }}">{{ $pago->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div id="card_contenedor" class="d-none mt-3">
            <div class="card shadow-lg animate__animated animate__fadeIn">
                <div class="d-flex flex-row">
                    <!-- Columna izquierda: imagen, nombre, descripción -->
                    <div style="flex: 1;">
                        <img id="card_imagen" src="" class="img-fluid rounded-start" alt="Curso"
                            style="height: 100%; object-fit: cover;">
                    </div>
                    <!-- Columna derecha: detalles -->
                    <div class="card-body" style="flex: 2;">
                        <h5 class="card-title" id="card_nombre"></h5>
                        <p class="card-text" id="card_descripcion"></p>
                        <p class="mb-1"><strong>Módulo:</strong> <span id="card_modulo"></span></p>
                        <p class="mb-1"><strong>Duración:</strong> <span id="card_duracion"></span></p>
                        <p class="mb-1"><strong>Precio:</strong> Bs.<span id="card_precio"></span></p>
                        <select id="select_paralelo" class="form-control mt-3" name="paralelo">
                            <option value="">Seleccione un paralelo</option>
                        </select>

                        <p id="nombreProfesor" class="mt-3 fw-bold"></p>
                        <div id="datosHorario" class="mb-3"></div>
                    </div>


                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Inscribir estudiante</button>
    </form>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}
        ">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar inscripción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Estudiante:</strong> <span id="modal_estudiante"></span></p>
                    <p><strong>Curso:</strong> <span id="modal_curso"></span></p>
                    <p><strong>Paralelo:</strong> <span id="modal_paralelo"></span></p>
                    <p>¿Está seguro de inscribir al estudiante?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmSubmit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('inscripcion_adm.store') }}"]');
            const estudiantesSelect = form.querySelector('select[name="estudiante"]');
            const cursoSelect = form.querySelector('select[name="curso"]');
            const paraleloSelect = form.querySelector('select[name="paralelo"]');

            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            const modalEstudiante = document.getElementById('modal_estudiante');
            const modalCurso = document.getElementById('modal_curso');
            const modalParalelo = document.getElementById('modal_paralelo');
            const confirmSubmitBtn = document.getElementById('confirmSubmit');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                let errores = [];

                if (!estudiantesSelect.value) errores.push('Debe seleccionar un estudiante.');
                if (!cursoSelect.value) errores.push('Debe seleccionar un curso.');
                if (!paraleloSelect.value) errores.push('Debe seleccionar un paralelo.');

                if (errores.length) {
                    alertify.error(errores.join('\n'));
                    return;
                }

                // Poner en el modal los textos seleccionados
                modalEstudiante.textContent = estudiantesSelect.options[estudiantesSelect.selectedIndex].text.trim();
                modalCurso.textContent = cursoSelect.options[cursoSelect.selectedIndex].text.trim();
                modalParalelo.textContent = paraleloSelect.options[paraleloSelect.selectedIndex].text.trim();

                confirmModal.show();
            });

            confirmSubmitBtn.addEventListener('click', function () {
                confirmModal.hide();
                form.submit();
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#pago_selector').select2({
                placeholder: 'Seleccione un curso',
                allowClear: true
            });

            // Opcional: acción al seleccionar
            $('#pago_selector').on('change', function () {
                const id = $(this).val();
                if (id) {
                    crearModal(id); // Reutiliza tu función
                }
            });
        });
    </script>



    <script>
        document.getElementById('curso_selector').addEventListener('change', function () {
            const cursoId = this.value;

            if (cursoId) {
                fetch("{{ route('inscripcionModal.show') }}/?id_a=" + cursoId)
                    .then(response => response.json())
                    .then(data => {

                        if (data.status == 'success') {
                            console.log(data.data);

                            const contenedor = document.getElementById('card_contenedor');
                            document.getElementById('card_imagen').src = '/' + data.data.imagen;
                            document.getElementById('card_nombre').textContent = data.data.nombre;
                            document.getElementById('card_descripcion').textContent = data.data.descripcion;
                            document.getElementById('card_modulo').textContent = data.data.datosModulo?.nombreM ?? 'N/A';
                            document.getElementById('card_duracion').textContent = data.data.datosModulo?.Duracion ?? 'N/A';
                            document.getElementById('card_precio').textContent = data.data.precio;

                            if (data.data.datosParalelo) {

                                cargarParalelos(data.data.datosParalelo, data.data.nombreM, cursoId);
                            }

                            // Mostrar card con animación
                            contenedor.classList.remove('d-none');
                            contenedor.classList.add('animate__fadeIn');

                        }
                        else if (data.status == 'error') {


                            alertify.error(data.message)

                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener los datos de la materia:', error);
                    });
            }
        });

        function cargarParalelos(datosParalelo, nombre, id_a) {
            console.log(datosParalelo);
            console.log(nombre);
            console.log(id_a);
            const select = document.getElementById('select_paralelo');
            select.innerHTML = '<option value="">Seleccione un paralelo</option>';

            for (const clave in datosParalelo) {
                if (datosParalelo.hasOwnProperty(clave)) {
                    const option = document.createElement('option');
                    option.value = datosParalelo[clave].id_p;
                    option.textContent = clave;
                    select.appendChild(option);
                }
            }

            // Llamar a la función para manejar el cambio
            handleSelectChange(nombre, id_a);
        }

        function handleSelectChange(nombre, id_a) {
            const selectParalelo = document.getElementById('select_paralelo');

            // Limpiar posibles listeners previos
            selectParalelo.onchange = function () {
                const id_p = this.value;

                if (!id_p) return;

                let routeUrl = '{{ route("Paralelo.get", ["nombre" => ":nombre", "id_a" => ":id_a", "id_p" => ":id_p"]) }}';
                routeUrl = routeUrl
                    .replace(':nombre', encodeURIComponent(nombre))
                    .replace(':id_a', id_a)
                    .replace(':id_p', id_p);

                fetch(routeUrl)
                    .then(response => {
                        if (!response.ok) throw new Error('Ocurrió un error al cargar los datos');
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('nombreProfesor').textContent = data.profesor;
                        const datosHorario = document.getElementById('datosHorario');
                        datosHorario.innerHTML = '';

                        Object.entries(data.horarios).forEach(([dia, horario]) => {
                            const p = document.createElement('p');
                            p.textContent = `${dia}: ${horario.hora_inicio} - ${horario.hora_fin}`;
                            datosHorario.appendChild(p);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            };
        }

        function mostrarDatosCurso(data) {
            // Ejemplo: mostrar en consola o actualizar elementos del DOM
            // document.getElementById('nombre_curso').textContent = data.nombre;
            console.log(data);
        }
    </script>



@endsection