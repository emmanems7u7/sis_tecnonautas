@extends('layouts.argon')


@section('content')
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('profesor'))

        <div class="alert alert-info text-white" role="alert" style="text-align: justify; font-size: 15px;">
            <h5 class="alert-heading">Información sobre la Gestión de Estudiantes</h5>

            <p style="font-size: 15px !important;"><strong>Estudiantes Inscritos:</strong> En esta sección podrá visualizar a
                los
                estudiantes inscritos en la
                materia o módulo. Dispone de las siguientes acciones:</p>
            <ul>
                <li><strong>Editar:</strong> Permite modificar los datos del estudiante.</li>
                <li><strong>Eliminar:</strong> Elimina al estudiante del registro actual.</li>
                <li><strong>Generar Certificado:</strong> Crea un certificado correspondiente al módulo aprobado por el
                    estudiante.</li>
            </ul>

            <p style="font-size: 15px !important;"><strong>Notas de Estudiantes:</strong> Desde aquí puede acceder a:</p>
            <ul>
                <li><strong>Detalle:</strong> Visualiza información específica y detallada del desempeño del estudiante.</li>
                <li><strong>Reporte:</strong> Abre una ventana flotante con la previsualización en PDF del informe generado.
                </li>
                <li><strong>Asignar:</strong> Permite asignar manualmente al estudiante al siguiente módulo, previa validación
                    de que ha aprobado el actual.</li>
                <li><strong>Importante:</strong> Si algún estudiante no aparece en el listado, es porque no ha entregado
                    ninguna tarea ni evaluación en el módulo correspondiente.</li>
            </ul>

            <p style="font-size: 15px !important;"><strong>Reporte de Asistencia:</strong> Esta opción le permite generar un
                informe
                completo sobre
                la asistencia registrada de cada estudiante en el módulo correspondiente.</p>

            <p style="font-size: 15px !important;"><strong>Importante:</strong> La acción de asignar al siguiente módulo solo
                será
                habilitada si el estudiante ha
                aprobado el módulo actual</p>
        </div>


        <div class="card">
            <div class="card-body">

                <div class="col-12">
                    <h3 class="text-dark">Curso: {{$materia->nombre}} {{ $materia->nombreM}}</h3>
                </div>

            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h3 class="text-black mb-4"><i class="fas fa-tools me-2"></i>Panel de Administración</h3>

                <button id="mostrar-estudiantes" class="btn btn-outline-primary text-start" onclick="recargarContenedor()">
                    <i class="fas fa-users me-2"></i> Ver estudiantes inscritos
                </button>
                <button id="mostrar-notas" class="btn btn-outline-success text-start" onclick="notasEstudiantes()">
                    <i class="fas fa-clipboard-list me-2"></i> Ver notas de estudiantes
                </button>
                <button class="btn btn-outline-warning text-start">
                    <i class="fas fa-calendar-check me-2"></i> Ver reporte de asistencia
                </button>
            </div>
        </div>



        <div class="card mt-3">
            <div class="card-body">


                <h3 id="panelTitulo" class="">Panel de administración</h3>

                <div class="table-responsive">

                    <table id="tabla-estudiantes"
                        class=" table-sm table table-striped""
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        style="
                        display: none;">
                        <thead style="background-color: #343a40; color: #ffffff;">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Módulo</th>
                                <th>Asignación</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se generarán las filas de los estudiantes con JavaScript -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <style>
            .vertical-text {
                writing-mode: vertical-lr;
                transform: rotate(0deg);
                white-space: nowrap;
                text-align: center;
            }
        </style>

        @include('modulos.modalReporte')
        @yield('reporteModal')


        <script>

            const loader = document.getElementById('loader');
            function recargarContenedor() {


                loader.style.display = '';


                fetch('{{ route('studiantes.ver', ['id_a' => $id_a, 'id_p' => $id_p]) }}').then(response => response.json())
                    .then(data => {
                        loader.style.display = 'none';
                        var botonMostrar = document.getElementById('mostrar-tabla');

                        var tablaEstudiantes = document.getElementById('tabla-estudiantes');
                        tablaEstudiantes.innerHTML = `  <thead>
                                                                                                                                                            <tr>
                                                                                                                                                            <th>Foto</th>
                                                                                                                                                            <th>Nombre</th>
                                                                                                                                                            <th>Apellidos</th>
                                                                                                                                                            <th>Email</th>
                                                                                                                                                        <th>Acciones</th>
                                                                                                                                                        </tr>
                                                                                                                                                         </thead>`;
                        const myHeading = document.getElementById('panelTitulo');
                        myHeading.textContent = data.dato;

                        var estudiantes = data.estudiantes;

                        // Obtener el elemento de la tabla en el que se mostrarán los datos
                        var tablaEstudiantes = document.getElementById('tabla-estudiantes');
                        tablaEstudiantes.style.border = '1px solid #000';
                        tablaEstudiantes.style.display = 'table';
                        // Iterar sobre los estudiantes y crear filas en la tabla
                        estudiantes.forEach(function (estudiante) {
                            // Crear una nueva fila en la tabla
                            var fila = tablaEstudiantes.insertRow();

                            var celdaFotoPerfil = fila.insertCell();
                            var fotoPerfilUrl = "{{ asset('') }}" + estudiante.fotoperfil;
                            celdaFotoPerfil.innerHTML = `<img src="${fotoPerfilUrl.replace(/&quot;/g, '')}" class="rounded" alt="Foto de perfil" height="40" width="40">`;

                            var celdaNombre = fila.insertCell();
                            celdaNombre.textContent = estudiante.usuario_nombres;
                            var celdaApellido = fila.insertCell();
                            celdaApellido.textContent = estudiante.usuario_app + ' ' + estudiante.usuario_apm;
                            var celdaModulo = fila.insertCell();
                            celdaModulo.textContent = estudiante.email;

                            var celdaVer = fila.insertCell();


                            celdaVer.innerHTML = '<div class="d-flex flex-wrap gap-2 justify-content-center">' +
                                '<a href="{{route('estudiante.show', ['id' => 'estudiante.id']) }}'.replace('estudiante.id', estudiante.id) + '" class="btn btn-primary mb-2"><i class="fas fa-pencil-alt"></i></a>' +
                                '<a href="{{ route('estudiante.destroy', ['id' => 'estudiante.id']) }}'.replace('estudiante.id', estudiante.id) + '" class="btn btn-danger mb-2"><i class="fas fa-trash"></i></a>';

                            if (data.finalizado == 1) {
                                if (estudiante.nota >= 51) {
                                    celdaVer.innerHTML += '<a href="' + "{{ route('generar_certificados_seguro', ['user_id' => '__user_id__', 'id_a' => '__id_a__']) }}"
                                        .replace('__user_id__', estudiante.id)
                                        .replace('__id_a__', {{$id_a}}) + '" class="btn btn-success" target="_blank"><i class="fas fa-certificate"></i> Certificado</a>';
                                }
                            }

                            celdaVer.innerHTML += '</div>';



                        });


                    })
                    .catch(error => {
                        console.log(error);
                    });



            }

            //notas
            function notasEstudiantes() {
                loader.style.display = '';

                fetch("{{ route('notasEstudiantes.ver', ['id_a' => $id_a, 'id_m' => $id_m, 'id_p' => $id_p]) }}")
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        loader.style.display = 'none';
                        var tablaEstudiantes = document.getElementById('tabla-estudiantes');
                        tablaEstudiantes.innerText = '';
                        tablaEstudiantes.style.display = 'table';

                        // Obtener todos los posibles IDs de tareas y evaluaciones
                        const allTareasIds = new Set();
                        const allEvaluacionesIds = new Set();

                        const TareasNombres = new Set();
                        const EvaluacionesNombres = new Set();

                        // Añadir nombres de tareas y evaluaciones a los conjuntos
                        data.tareas.forEach(tarea => TareasNombres.add(tarea.nombre));
                        data.evaluaciones.forEach(evaluacion => EvaluacionesNombres.add(evaluacion.nombre));

                        // Añadir IDs de tareas y evaluaciones a los conjuntos
                        data.tareas.forEach(tarea => allTareasIds.add(tarea.id));
                        data.evaluaciones.forEach(evaluacion => allEvaluacionesIds.add(evaluacion.id));

                        // Crear la tabla
                        const table = document.createElement('table');
                        table.classList.add('table', 'table-bordered', 'table-striped', 'table-hover'); // Clases de Bootstrap para mejorar el estilo
                        table.style.width = '100%'; // Ajustar el ancho de la tabla

                        // Crear el encabezado de la tabla
                        const thead = document.createElement('thead');

                        // Crear la primera fila para el encabezado principal
                        const headerRow = document.createElement('tr');

                        // Columna para el nombre del estudiante
                        const estudianteHeader = document.createElement('th');
                        estudianteHeader.textContent = 'Estudiante';
                        estudianteHeader.rowSpan = 2; // El nombre del estudiante ocupa dos filas
                        headerRow.appendChild(estudianteHeader);

                        // Crear encabezado para tareas

                        if (data.tareas && data.tareas.length > 0) {
                            const tareasHeader = document.createElement('th');
                            tareasHeader.colSpan = allTareasIds.size; // La cantidad de columnas es igual al número de tareas
                            tareasHeader.textContent = 'Tareas';
                            tareasHeader.style.textAlign = 'center'; // Alinear el texto al centro
                            tareasHeader.classList.add('font-weight-bold'); // Hacer el texto en negrita
                            headerRow.appendChild(tareasHeader);

                        }


                        // Crear encabezado para evaluaciones
                        const evaluacionesHeader = document.createElement('th');
                        evaluacionesHeader.colSpan = allEvaluacionesIds.size; // La cantidad de columnas es igual al número de evaluaciones
                        evaluacionesHeader.textContent = 'Evaluaciones';
                        evaluacionesHeader.style.textAlign = 'center'; // Alinear el texto al centro
                        evaluacionesHeader.classList.add('font-weight-bold'); // Hacer el texto en negrita
                        headerRow.appendChild(evaluacionesHeader);

                        thead.appendChild(headerRow);

                        // Crear la segunda fila para los encabezados de tareas y evaluaciones
                        const subHeaderRow = document.createElement('tr');

                        // Crear encabezados para cada tarea
                        data.tareas.forEach(tarea => {
                            const header = document.createElement('th');
                            header.textContent = tarea.nombre;
                            header.classList.add('vertical-text');
                            header.style.textAlign = 'center';
                            subHeaderRow.appendChild(header);
                        });

                        // Crear encabezados para cada evaluación
                        data.evaluaciones.forEach(evaluacion => {
                            const header = document.createElement('th');
                            header.textContent = evaluacion.nombre;
                            header.style.textAlign = 'center';
                            header.classList.add('vertical-text');
                            subHeaderRow.appendChild(header);
                        });

                        thead.appendChild(subHeaderRow);
                        table.appendChild(thead);

                        // Crear la fila de cabecera para las acciones
                        const accionesHeaderRow = document.createElement('tr');

                        // Columna para las acciones
                        const accionesHeader = document.createElement('th');
                        accionesHeader.textContent = 'Acciones';
                        accionesHeader.colSpan = 1; // Solo una columna para las acciones
                        accionesHeader.style.textAlign = 'center'; // Alinear el texto al centro
                        accionesHeader.classList.add('font-weight-bold'); // Hacer el texto en negrita
                        subHeaderRow.appendChild(accionesHeader);

                        // Añadir la fila de cabecera de acciones al encabezado de la tabla
                        thead.appendChild(subHeaderRow);
                        // Crear el cuerpo de la tabla
                        const tbody = document.createElement('tbody');
                        const estudiantesArray = Object.values(data.estudiantesTareas);
                        // Iterar sobre los estudiantes para crear las filas
                        estudiantesArray.forEach(item => {
                            const row = document.createElement('tr');

                            // Columna para el nombre del estudiante
                            const estudianteCell = document.createElement('td');
                            estudianteCell.textContent = item.estudiante;
                            estudianteCell.style.padding = '15px'; // Espacio en las celdas para los nombres
                            row.appendChild(estudianteCell);

                            // Crear celdas para las tareas
                            allTareasIds.forEach(tareaId => {
                                const tareaCell = document.createElement('td');
                                const tarea = item.tareas.find(t => t.tareas_id === tareaId);
                                if (tarea) {

                                    tareaCell.textContent = tarea.nota;
                                }
                                else {
                                    tareaCell.style.color = 'red';
                                    tareaCell.textContent = 0;
                                }

                                tareaCell.style.padding = '10px'; // Espacio en las celdas
                                tareaCell.style.textAlign = 'center'; // Alinear el texto al centro
                                row.appendChild(tareaCell);
                            });

                            // Crear celdas para las evaluaciones
                            allEvaluacionesIds.forEach(evaluacionId => {
                                const evaluacionCell = document.createElement('td');
                                const evaluacion = item.evaluaciones.find(e => e.id_e === evaluacionId);
                                if (evaluacion) {

                                    evaluacionCell.textContent = evaluacion.nota;
                                }
                                else {
                                    evaluacionCell.style.color = 'red';
                                    evaluacionCell.textContent = 0;
                                }

                                evaluacionCell.style.padding = '10px'; // Espacio en las celdas
                                evaluacionCell.style.textAlign = 'center'; // Alinear el texto al centro
                                row.appendChild(evaluacionCell);
                            });

                            // Añadir celdas con botones de acción
                            const celdaAcciones = document.createElement('td');
                            celdaAcciones.innerHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a href="${'{{ route('estudiante.detalle', ['id' => ':id', 'id_m' => $id_p]) }}'.replace(':id', item.user_id)}" class="btn btn-sm btn-primary mb-1">Detalles</a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a  class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#reportModal" onclick="datosPersonal(${item.user_id})">Reporte</a>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `;
                            celdaAcciones.innerHTML += `<a  class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#modalAsigna" onclick="asignar(${item.user_id})">Asignar</a>`;

                            row.appendChild(celdaAcciones);

                            tbody.appendChild(row);
                        });

                        table.appendChild(tbody);

                        // Agregar la tabla al elemento con ID 'tabla-estudiantes'
                        tablaEstudiantes.appendChild(table);
                    })
                    .catch(error => console.error('Error:', error));
            }


            function datosPersonal(idEstudiante) {
                // Crear la URL con el ID del estudiante
                var url = "{{ route('estudiante.reporte', ['id' => 'id_est', 'id_m' => $id_m, 'id_p' => $id_p]) }}";
                var dat = url.replace('id_est', idEstudiante);

                // Realizar la solicitud Fetch
                loader.style.display = '';

                fetch(dat)
                    .then(response => {

                        if (!response.ok) {
                            throw new Error('Error al realizar la solicitud.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Manejar la respuesta de la solicitud
                        loader.style.display = 'none';

                        // Suponiendo que data contiene la respuesta JSON de la solicitud Fetch
                        const nombreEstudiante = data.usuario.usuario_nombres + ' ' + data.usuario.usuario_app + ' ' + data.usuario.usuario_apm;
                        const nombreParalelo = data.paralelo.nombre;
                        const nombreModulo = data.nombreMod.nombreM;
                        const profesor = data.profesor.usuario_nombres + ' ' + data.profesor.usuario_app + ' ' + data.profesor.usuario_apm;
                        const nombreMateria = data.materia; // Reemplaza "Nombre de la materia" con el nombre real de la materia

                        // Actualizar el contenido de los elementos en la tarjeta

                        const imagenEstudiante = `{{ asset('${data.usuario.fotoperfil}') }}`;
                        var rutaPdf = '{{ route('reporte.estudiante', ['id' => ':idEstudiante', 'id_m' => $id_m, 'id_p' => $id_p]) }}';
                        rutaPdf = rutaPdf.replace(':idEstudiante', idEstudiante);

                        // Asignar como action al formulario
                        document.getElementById('EnviarDatos').action = rutaPdf;
                        document.getElementById('fotoe').src = imagenEstudiante;
                        document.getElementById('estudiante').textContent = `Nombre: ${nombreEstudiante}`;
                        document.getElementById('paralelo').textContent = `Paralelo: ${nombreParalelo}`;
                        document.getElementById('modulo').textContent = `Módulo: ${nombreModulo}`;
                        document.getElementById('profesor').textContent = `Profesor: ${profesor}`;
                        document.getElementById('materia').textContent = `Materia: ${nombreMateria}`;


                        const evaluaciones = data.evaluaciones;

                        const tareas = data.tareasEstudiantes;
                        const tbody = document.querySelector('#evaluacionesTable tbody');

                        const tbodyt = document.querySelector('#tareasTable tbody');

                        tbody.innerHTML = '';
                        tbodyt.innerHTML = '';

                        evaluaciones.forEach(evaluacion => {
                            const row = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td>${evaluacion.nombre}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td>${evaluacion.nota}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td>${evaluacion.creado}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td>${evaluacion.limite}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <td>${evaluacion.completado}</td>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `;
                            tbody.innerHTML += row;
                        });

                        tareas.forEach(tarea => {
                            const row = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${tarea.nombre}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${tarea.detalle}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${tarea.entregado}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${tarea.limite}</td>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${tarea.nota}</td>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `;
                            tbodyt.innerHTML += row;
                        });
                        if (data.nota >= 80) {
                            document.getElementById('destacado').style.display = 'block';

                        }
                        else {
                            document.getElementById('destacado').style.display = 'none';

                        }

                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function asignar(estudiante_id) {
                var url = "{{ route('estudiante.asignar.paralelo', ['id' => 'id_est', 'id_m' => $id_m, 'id_p' => $id_p]) }}";
                url = url.replace('id_est', estudiante_id);
                loader.style.display = '';

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        loader.style.display = 'none';
                        let container = document.getElementById('cardsContainer');
                        container.innerHTML = generateStudentCard(data, estudiante_id);
                    })
                    .catch(error => console.error('Error:', error));

            }

            function generateStudentCard(data, estudiante_id) {
                let tareasHTML = '';
                let evaluacionesHTML = '';

                // Generar HTML para las tareas con validación de color solo en el número
                data.Tareas.forEach(tarea => {
                    let tareaClass = tarea.nota >= 51 ? 'text-success' : 'text-danger';
                    tareasHTML += `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <strong>${tarea.nombre}</strong>: <span class="${tareaClass}">${tarea.nota}</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `;
                });

                // Generar HTML para las evaluaciones con validación de color solo en el número
                data.evaluaciones.forEach(evaluacion => {
                    let evaluacionClass = evaluacion.nota >= 51 ? 'text-success' : 'text-danger';
                    evaluacionesHTML += `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <strong>${evaluacion.evaluacion}</strong>: <span class="${evaluacionClass}">${evaluacion.nota}</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `;
                });

                // Mensaje de validación
                let messageHTML = '';
                let notaFinalClass = data.nota_final >= 51 ? 'text-success' : 'text-danger';
                if (data.nota_final >= 51) {
                    messageHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card message-card bg-success text-white">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="card-body">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <h5 class="card-title">Asignación Permitida</h5>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <p class="card-text">El estudiante puede ser asignado.</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href="javascript:void(0);" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           class="btn btn-sm btn-success mt-1" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           onclick="confirmarAsignacion(`+ estudiante_id + `)">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            Asignar
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `;
                } else {
                    messageHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card message-card bg-danger text-white">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="card-body">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <h5 class="card-title">Asignación No Permitida</h5>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <p class="card-text">No se puede asignar a un estudiante que no aprobó.</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `;
                }

                // Generar la tarjeta completa
                return `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="card mb-3">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card-header">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Estudiante
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card-body">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <h6 class="card-subtitle mb-2 text-muted">Nombre: ${data.estudiante}</h6>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <p class="card-text"><strong>Promedio Final:</strong> <span class="${notaFinalClass}">${data.nota_final}</span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ${messageHTML}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="card mb-3">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card-header">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Tareas y Evaluaciones
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card-body">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <h6>Tareas:</h6>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ${tareasHTML}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <h6>Evaluaciones:</h6>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ${evaluacionesHTML}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    `;
            }

            function confirmarAsignacion(estudiante_id) {
                alertify.confirm(
                    '¿Estás seguro?',
                    '¿Deseas asignar al estudiante al siguiente modulo?',
                    function () {
                        let url = "{{ route('asignacion.individual', ['id' => ':id', 'id_m' => $id_m, 'id_p' => $id_p]) }}".replace(':id', estudiante_id);
                        loader.style.display = '';

                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                loader.style.display = 'none';
                                if (data.status == 'error') {
                                    alertify.error(data.message);
                                } else {
                                    alertify.success(data.message);
                                }
                            })
                            .catch(error => {
                                alertify.error('Hubo un problema al asignar.');
                            });
                    },
                    function () {
                        // Cancelado
                    }
                ).set('labels', { ok: 'Sí, asignar', cancel: 'Cancelar' }).set('closable', false);
            }
        </script>


    @else

        <div class="container mt-5">
            <div class="alert alert-danger">
                <h4 class="alert-heading">¡Acceso Denegado!</h4>
                <p>No tienes permiso para acceder a esta página. Por favor, contacta con el administrador si consideras que esto
                    es un error.</p>
                <hr>
                <p class="mb-0">
                    <a href="{{ route('home') }}" class="btn btn-primary">Volver a la página principal</a>
                </p>
            </div>
        </div>
    @endif
@endsection