@extends('layouts.argon')

@section('content')
    @if($tiempo_cambio_contraseña != 1)



        <div class="container-fluid">
            @if(session('alert'))
                <div id="alertMessage" class="alert alert-{{ session('alert')['type'] }}">
                    {{ session('alert')['message'] }}
                </div>
            @endif

            <style>
                .class {
                    background-color: #fff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    margin-bottom: 20px;
                    overflow: hidden;
                    transition: transform 0.3s ease;
                }

                .class:hover {
                    transform: translateY(-5px);
                }

                .class-header {
                    background-color: #4CAF50;
                    color: #fff;
                    padding: 15px;
                    text-align: center;
                    border-bottom: 2px solid #388E3C;
                    border-radius: 10px 10px 0 0;
                }

                .class-body {
                    padding: 20px;
                }

                .class-body p {
                    margin: 0;
                }

                .wednesday .class-header {
                    background-color: #FFC107;
                }
            </style>

            <style>
                @keyframes mover-flecha {
                    0% {
                        transform: translateX(0);
                    }

                    50% {
                        transform: translateX(6px);
                    }

                    100% {
                        transform: translateX(0);
                    }
                }

                .animate-flecha {
                    display: inline-block;
                    animation: mover-flecha 1s infinite;
                }
            </style>



            <div class="row align-items-md-stretch mt-1">

                @role('admin')
                <div class="col-md-4  mb-2">
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <!-- Icono de Materias -->
                            <div class="d-flex align-items-center">
                                <i class="fas fa-book text-primary fs-3 me-3"></i>
                                <!-- Título -->
                                <div>
                                    <h5 class="mb-0">Total Materias</h5>
                                </div>
                            </div>
                            <!-- Número de Materias en círculo -->
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px;">
                                <span class="fs-4">{{     $Casig }}</span>
                                <!-- Cambia este número por la cantidad de materias -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4  mb-2">
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <!-- Icono de Estudiantes -->
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-graduate fs-3 me-3" style="color:#67b700"></i>
                                <!-- Título -->
                                <div>
                                    <h5 class="mb-0">Total Estudiantes</h5>
                                </div>
                            </div>
                            <!-- Número de Estudiantes en círculo -->
                            <div class="rounded-circle  text-white d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px; background-color: #67b700;">
                                <span class="fs-4">{{ $students  }}</span>
                                <!-- Cambia este número por la cantidad de estudiantes -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4  mb-2">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <!-- Icono de Profesor -->
                                <i class="fas fa-chalkboard-teacher fs-3 me-3" style="color:#df8b12;"></i>
                                <!-- Título -->
                                <div>
                                    <h5 class=" mb-0">Total Profesores</h5>
                                </div>
                            </div>
                            <!-- Número en círculo -->
                            <div class="rounded-circle  text-white d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px; background-color: #df8b12; ">
                                <span class="fs-4">{{ $profesor }}</span>
                                <!-- Cambia este número por la cantidad de profesores -->
                            </div>
                        </div>
                    </div>
                </div>
                @endrole


                <div class="col-md-6 mt-1 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <h3>Hola {{ Auth::user()->name }}, Bienvenido al sistema Académico de Tecnonautas</h3>
                            <p class="text-dark"><i class="bi bi-emoji-heart-eyes"></i> Encontrarás información multiple aquí,
                                revisa tambien si tienes notificaciones en la parte superior derecha</p>


                        </div>
                    </div>
                </div>

                @role('profesor|admin')
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-primary fw-bold">Acciones Rápidas</h5>
                            <div class="row">


                                <!-- Auditoria Pagos -->
                                <div class="col-md-6 mt-3">
                                    <a href="{{ route('pagos.index') }}"
                                        class="btn btn-info w-100 d-flex flex-column  text-center py-3">
                                        <i class="fas fa-file fa-2x mb-2"></i>
                                        <span>Auditoria Pagos</span>
                                    </a>
                                </div>

                                <!-- Crear Materia -->
                                <div class="col-md-6 mt-3">
                                    <a href="{{route('asignacion.create')}}"
                                        class="btn btn-info w-100 d-flex flex-column  text-center py-3">
                                        <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                        <span>Crear Materia</span>
                                    </a>
                                </div>

                                <!-- Editar Perfil -->
                                <div class="col-md-6 mt-3">
                                    <a href="{{route('perfil')}}"
                                        class="btn btn-info w-100 d-flex flex-column  text-center py-3">
                                        <i class="fas fa-user-edit fa-2x mb-2"></i>
                                        <span>Editar Perfil</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endrole

                <div class="col-md-12">

                    <div class="row mt-3">
                        @role('estudiante')
                        @if ($horariosF !== null)
                            @foreach ($horariosF as $horario)

                                <div class="col-md-4">
                                    @foreach ($horario['horarios'] as $dat)
                                        @if($dat->dias === $dia)

                                            <div class="class wednesday">
                                                @break
                                        @else

                                                <div class="class">
                                                    @break
                                            @endif
                                    @endforeach
                                            <div class="class-header">{{ $horario['materia'] }}</div>
                                            <div class="class-body">
                                                @foreach ($horario['horarios'] as $horario)
                                                    @if ($horario->dias === $dia)
                                                        <div class="alert alert-success" role="alert">


                                                            <p><strong>Día:</strong> {{ $horario->dias }}</p>
                                                            <p><i class="fas fa-clock"></i>{{ $horario->inicio }}-{{ $horario->fin }}</p>
                                                        </div>

                                                        <div>
                                                            @if($id_pm != null)
                                                                <button class="btn btn-info btn-sm"
                                                                    onclick="regsistrarAsistencia('{{ $id_pm }}')">Registrar
                                                                    Asistencia</button>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info" role="alert">


                                                            <p><strong>Día:</strong> {{ $horario->dias }}</p>
                                                            <p><i class="fas fa-clock"></i>{{ $horario->inicio }}-{{ $horario->fin }}</p>
                                                        </div>
                                                    @endif


                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        @endif

                            @endrole
                        </div>


                    </div>

                </div>

                @if($datosParalelos != null)
                    <div class="row">
                        @foreach ($datosParalelos as $materia => $paralelos)
                            <div class="col-12 col-md-6">
                                <div class="card mt-3" style="min-height: 250px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="fw-bold mb-0">{{ $materia }}</h5>
                                            @if (count($paralelos) > 1)
                                                <i class="fas fa-arrow-right animate-flecha text-primary"></i>
                                            @endif
                                        </div>

                                        <!-- Scroll horizontal si hay múltiples paralelos -->
                                        <div class="overflow-x-auto mt-2">
                                            <div class="d-flex flex-nowrap gap-3">
                                                @foreach ($paralelos as $paralelo)
                                                    <div class="card border-0 shadow-sm rounded-3 position-relative mt-2"
                                                        style="flex: 0 0 260px;">
                                                        <div class="position-absolute top-0 end-0 p-2 text-success rounded-start">
                                                            {{ $paralelo['activo'] ? 'Activo' : 'Inactivo' }}
                                                        </div>

                                                        <div class="card-body p-2">
                                                            <h5 class="card-title text-success fw-semibold">
                                                                {{ $paralelo['modulo'] }} ({{ $paralelo['paralelo'] }})
                                                            </h5>

                                                            <p class="mb-1 small">
                                                                <small>
                                                                    <i class="fas fa-chalkboard-teacher me-1 text-secondary"></i>
                                                                    <strong>Profesor:</strong> {{ $paralelo['profesor'] }}
                                                                </small>
                                                            </p>

                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <small>
                                                                        <p class="mb-1 small">
                                                                            <i class="fas fa-user-friends me-1 text-secondary"></i>
                                                                            <strong>Cupo:</strong> {{ $paralelo['cupo'] }}
                                                                        </p>
                                                                    </small>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small>
                                                                        <p class="mb-1 small">
                                                                            <i class="fas fa-users me-1 text-secondary"></i>
                                                                            <strong>Inscritos:</strong> {{ $paralelo['inscritos'] }}
                                                                        </p>
                                                                    </small>
                                                                </div>
                                                            </div>

                                                            @if (!empty($paralelo['horarios']))
                                                                <div class="mt-2">
                                                                    <small>
                                                                        <strong class="text-muted small">Horarios:</strong>
                                                                        <ul class="list-unstyled small ps-2">
                                                                            @foreach ($paralelo['horarios'] as $dia => $horario)
                                                                                <li class="mb-1">
                                                                                    <i class="far fa-clock me-1"></i>
                                                                                    {{ $dia }}: {{ $horario['hora_inicio'] }} -
                                                                                    {{ $horario['hora_fin'] }}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


            <script>

                function registrarAsistencia(id_pm) {
                    // Obtener la fecha actual en formato YYYY-MM-DD
                    const fechaActual = new Date().toISOString().split('T')[0]; // Ejemplo: '2024-11-25'

                    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Mostrar un mensaje de confirmación antes de editar
                    alertify.confirm(
                        '¿Estás seguro?',
                        `¿Deseas registrar tu asistencia para la fecha ${fechaActual}?`,
                        function () {
                            // Hacer una solicitud al servidor para verificar si ya existe un registro de asistencia para la fecha y id_pm
                            let url = '/registra/asistencia/{id_pm}';

                            // Reemplazar {id_pm} con el valor de la variable id_pm
                            url = url.replace('{id_pm}', id_pm);

                            fetch(url, {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    asistencia: "asistencia",
                                }),
                            })
                                .then(response => response.json())
                                .then(updatedData => {
                                    alertify.success('La asistencia ha sido editada correctamente.');
                                })
                                .catch(error => {
                                    alertify.error('Ocurrió un error al editar la asistencia.');
                                });
                        },
                        function () {

                        }
                    );

                }

            </script>
    @else

            <div class="alert alert-warning" role="alert">
                <strong>!Alerta!</strong> Debes actualizar tu contraseña
            </div>

        @endif
@endsection