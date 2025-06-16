@extends('layouts.argon')


@section('content')

    <div class="container-fluid mt-4">
        <div class="container-fluid mt-4">
            <div class="alert alert-info text-white shadow-sm" role="alert">
                <h4 class="alert-heading"><i class="fas fa-book-open"></i> Administración de Materias y Módulos</h4>
                <p><strong>Gestión de Materias:</strong> Aquí puedes visualizar las materias asignadas, los módulos
                    inscritos y el estado de cada usuario.</p>

                <p><strong>Monitoreo en Tiempo Real:</strong> Puedes verificar la información de pagos, fechas de
                    inscripción y disponibilidad de los módulos en una sola vista.</p>
                <p><strong>Acción Administrativa:</strong> Si detectas alguna irregularidad, puedes gestionar las
                    inscripciones y pagos manualmente.</p>
            </div>
        </div>



        @foreach ($materiasConModulosPaginated as $usuario)
            <div class="card mb-4 shadow">
                <div class="card-header bg-dark ">
                    <h5 class="mb-0 text-white">Estudiante: {{ $usuario['nombre'] }}</h5>
                </div>
                <div class="card-body">
                    @foreach ($usuario['asignaciones'] as $asignacion)
                        <div class="border p-3 mb-3 rounded">
                            <h5 class="text-dark">Asignatura: {{ $asignacion['asignacion'] }}</h5>
                            <p class="mb-2"><strong>Costo:</strong> BS. {{ $asignacion['costo'] }}</p>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nº</th>
                                            <th>Módulo</th>
                                            <th>Pagado</th>
                                            <th>Estado</th>
                                            <th>Fecha de Registro</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asignacion['modulos_inscritos'] as $modulo)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <td>{{ $modulo['modulo'] }}</td>
                                                <td>


                                                    <span
                                                        class="badge bg-{{ $modulo['pagado'] == 1 ? 'success' : ($modulo['pagado'] == 2 ? 'warning' : 'danger') }}">
                                                        {{ $modulo['pagado'] == 1 ? 'Sí' : ($modulo['pagado'] == 2 ? 'En proceso' : ($modulo['pagado'] == 3 ? 'Pago Rechazado' : 'No')) }}


                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $modulo['activo'] == 'activo' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($modulo['activo']) }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($modulo['fecha_registro'])->format('d/m/Y H:i') }}</td>
                                                <td>

                                                    @if($modulo['id_pago'] != null || $modulo['id_pago'] != '')
                                                        <a href="{{ route('auditoria.index', ['id' => $modulo['id_pago']]) }}"
                                                            class="btn btn-info btn-sm mt-3">Auditoria Pagos</a>
                                                    @else
                                                        <p>Pago no Realizado</p>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>

@endsection