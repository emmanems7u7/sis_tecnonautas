@extends('layouts.argon')


@section('content')


    <div class="alert alert-info shadow-sm" role="alert">
        <h4 class="alert-heading"><i class="fas fa-book-open"></i> Revisión de Registros de Pago por Materia y Módulo</h4>
        <p><strong>Estado de los Registros:</strong> En esta página puedes consultar el estado de los pagos realizados por
            los estudiantes en relación a las materias y módulos correspondientes.</p>

        <p><strong>Historial de Pagos:</strong> Se muestra cuántas veces el estudiante ha intentado registrar su comprobante
            de pago.</p>

        <p><strong>Detección de Errores:</strong> El sistema identifica automáticamente los casos con uno o más errores en
            los datos del comprobante o del correo.</p>

        <p><strong>Acción Administrativa:</strong> En los casos con un solo error, tienes la opción de habilitar o rechazar
            el registro del estudiante, según la revisión detallada de los datos del pago.</p>

        <p><strong>Verificación Detallada:</strong> Puedes acceder a la información completa del pago y visualizar la imagen
            del comprobante para tomar decisiones informadas.</p>
    </div>




    @foreach ($pagos as $pago)
        @if(
                $pago->metodo_pago != null &&
                $pago->imagenComprobante != null &&
                $pago->pagado != null &&
                $pago->monto != null &&
                $pago->fecha_pago != null &&
                $pago->numeroComprobante != null
            )



            @php
                $salidaError = false;
            @endphp

            @php
                $salidaError = false;
            @endphp

            @foreach ($pago->transacciones as $pa)
                @php
                    $data = $pa->data_imagen;
                    $dataC = $pa->data_correo;

                    $erroresC = $dataC['errores'] ?? null;
                    $error = $data['error'] ?? null;

                    $tieneError = !empty($error);
                    $tieneErrorCorreo = !empty($erroresC);

                    // Si tiene solo un error (uno sí, el otro no)
                    if (($tieneError && !$tieneErrorCorreo) || (!$tieneError && $tieneErrorCorreo)) {
                        $salidaError = true;
                    } else {
                        $salidaError = false;

                    }
                @endphp
            @endforeach




            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3 text-primary">Estudiante: {{ $pago->nombre_estudiante }}
                        @if($salidaError)
                            <div class="alert alert-warning shadow-sm mt-3" role="alert">
                                <p style="font-size:15px;">
                                    El sistema ha detectado uno error o mas errores en los datos del comprobante de pago o en la
                                    información
                                    del correo recibido. Por favor, revise la información de los registros existentes y proceda a
                                    habilitar al estudiante o rechace
                                    el registro según corresponda.
                                </p>
                                <button class="btn btn-sm btn-success">
                                    <i class="fas fa-check-circle"></i> Habilitar Estudiante
                                </button>

                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-times-circle"></i> Rechazar Registro
                                </button>
                            </div>
                        @else
                            <div class="alert alert-success shadow-sm mt-3" role="alert">
                                <p style="font-size:15px;">
                                    El sistema autorizó correctamente el pago del estudiante. Puede revisar los registros asociados y,
                                    en caso de detectar alguna irregularidad, proceder con el rechazo del registro si lo considera
                                    necesario.
                                </p>
                                <a class="btn btn-sm btn-danger" href="{{ route('pago.rechazo', ['id' => $pago->id]) }}">
                                    <i class="fas fa-times-circle"></i> Rechazar Registro
                                </a>
                            </div>
                        @endif
                    </h5>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Método de pago:</strong> {{ $pago->metodo_pago }}</div>
                                <div class="col-md-6"><strong>Monto:</strong> {{ $pago->monto }}</div>


                                <div class="col-md-6"><strong>Fecha de pago:</strong> {{ $pago->fecha_pago }}</div>
                                <div class="col-md-6"><strong>Estado de pago:</strong> @if($pago->pagado == 1)
                                    Pagado
                                @elseif($pago->pagado == 2)
                                        Para revision
                                    @elseif($pago->pagado == 3)
                                        Pago rechazado
                                    @else
                                        No Pagado
                                    @endif
                                </div>

                                <div class="col-md-12"><strong>Nro. Comprobante:</strong> {{ $pago->numeroComprobante }}</div>
                            </div>


                            @foreach ($pago->transacciones as $pa)
                                @php
                                    $data = $pa->data_imagen;
                                    $dataC = $pa->data_correo;

                                    $erroresC = $dataC['errores'] ?? null;
                                    $datosCorreo = $dataC['datos_correo'] ?? [];

                                    $error = $data['error'] ?? null;
                                    $detalle = $data['detalle'] ?? [];
                                    $errores = $detalle['errores'] ?? [];
                                    $datosImagen = $detalle['datos_imagen'] ?? [];
                                @endphp
                                @php $collapseId = 'collapse-' . $pa->id; @endphp

                                <div class="card mt-3  ">

                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>
                                            <strong>Pago:</strong>
                                            {{ $pa->created_at->translatedFormat('d \d\e F \d\e Y \a \l\a\s H:i') }}
                                        </span>
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                                            <i class="fas fa-plus" id="icon-{{ $pa->id }}"></i>
                                        </button>
                                    </div>

                                    <div id="{{ $collapseId }}" class="collapse">
                                        <div class="card-body">

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="card mt-3 border-2">
                                                        <div class="card-body">
                                                            @if($error != '' || $error != [])
                                                                <h6 class="text-danger">Error: {{ $error }}</h6>

                                                                @if(count($errores))
                                                                    <p class="mb-1"><strong>Errores detectados:</strong></p>
                                                                    <ul class="mb-2 ps-4">
                                                                        @foreach ($errores as $er)
                                                                            <li>{{ $er }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif

                                                                <p class="mb-1"><strong>Datos de la Imagen:</strong></p>
                                                                @if($datosImagen['fecha'] && $datosImagen['total_enviado'] && $datosImagen['nro_transaccion'])
                                                                    <ul class="ps-4 mb-0">
                                                                        <li><strong>Fecha:</strong> {{ $datosImagen['fecha'] }}</li>
                                                                        <li><strong>Total Enviado:</strong>
                                                                            {{ $datosImagen['total_enviado'] }}</li>
                                                                        <li><strong>Nombre Beneficiario:</strong>
                                                                            {{ $datosImagen['nombre_beneficiario'] }}</li>
                                                                        <li><strong>Número Beneficiario:</strong>
                                                                            {{ $datosImagen['numero_beneficiario'] }}</li>
                                                                        <li><strong>Destino:</strong> {{ $datosImagen['destino'] }}</li>
                                                                        <li><strong>Envío Realizado De:</strong>
                                                                            {{ $datosImagen['envio_realizado_de'] }}</li>
                                                                        <li><strong>Nro. de Transacción:</strong>
                                                                            {{ $datosImagen['nro_transaccion'] }}</li>
                                                                    </ul>
                                                                @else
                                                                    <p class="text-muted mb-0">No hay datos disponibles.</p>
                                                                @endif
                                                            @else
                                                                <h6 class="text-success">No se encontraron errores en el pago</h6>
                                                                @if($data['fecha'] && $data['total_enviado'] && $data['nro_transaccion'])
                                                                    <ul class="ps-4 mb-0">
                                                                        <li><strong>Fecha:</strong> {{ $data['fecha'] }}</li>
                                                                        <li><strong>Total Enviado:</strong> {{ $data['total_enviado'] }}
                                                                        </li>
                                                                        <li><strong>Nombre Beneficiario:</strong>
                                                                            {{ $data['nombre_beneficiario'] }}</li>
                                                                        <li><strong>Número Beneficiario:</strong>
                                                                            {{ $data['numero_beneficiario'] }}</li>
                                                                        <li><strong>Destino:</strong> {{ $data['destino'] }}</li>
                                                                        <li><strong>Envío Realizado De:</strong>
                                                                            {{ $data['envio_realizado_de'] }}</li>
                                                                        <li><strong>Nro. de Transacción:</strong>
                                                                            {{ $data['nro_transaccion'] }}</li>
                                                                    </ul>
                                                                @else
                                                                    <p class="text-muted mb-0">No hay datos disponibles.</p>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card mt-3 border-2">
                                                        <div class="card-body">
                                                            @if($erroresC != '' || $erroresC != [])
                                                                <h6 class="text-danger">Errores Encontrados</h6>

                                                                @foreach ($erroresC as $data)
                                                                    <ul class="mb-2 ps-4">
                                                                        <li>
                                                                            {{$data }}
                                                                        </li>

                                                                    </ul>


                                                                    <br>

                                                                @endforeach

                                                                <p class="mb-1"><strong>Datos Email:</strong></p>
                                                                <ul class="ps-4 mb-0">
                                                                    <li><strong>Remitente:</strong> {{  $datosCorreo['remitente'] }}
                                                                    </li>
                                                                    <li><strong>Fecha:</strong> {{  $datosCorreo['fecha'] }}</li>
                                                                    <li><strong>Asunto:</strong> {{  $datosCorreo['asunto'] }}</li>
                                                                    <li><strong>Nro de Transaccion:</strong>
                                                                        {{  $datosCorreo['numTransaccion'] }}</li>
                                                                    <li><strong>Nro de Comprobante:</strong>
                                                                        {{  $datosCorreo['numComprobante'] }}</li>
                                                                    <li><strong>Origen Titular:</strong>
                                                                        {{  $datosCorreo['origenTitular'] }}</li>
                                                                    <li><strong>Origen Cuenta:</strong>
                                                                        {{  $datosCorreo['origenCuenta'] }}</li>
                                                                    <li><strong>Destino Beneficiario:</strong>
                                                                        {{  $datosCorreo['destinoBeneficiario'] }}
                                                                    </li>
                                                                    <li><strong>Destino Cuenta:</strong>
                                                                        {{  $datosCorreo['destinoCuenta'] }}</li>
                                                                    <li><strong>Monto Transferido:</strong>
                                                                        {{  $datosCorreo['montoTransferido'] }}</li>
                                                                    <li><strong>Glosa:</strong> {{  $datosCorreo['glosa'] }}</li>
                                                                </ul>
                                                            @else
                                                                <h6 class="text-success">No se encontraron errores en el Email</h6>
                                                                <p class="mb-1"><strong>Datos Email:</strong></p>
                                                                <ul class="ps-4 mb-0">
                                                                    <li><strong>Remitente:</strong> {{  $dataC['remitente'] }}</li>
                                                                    <li><strong>Fecha:</strong> {{  $dataC['fecha'] }}</li>
                                                                    <li><strong>Asunto:</strong> {{  $dataC['asunto'] }}</li>
                                                                    <li><strong>Nro de Transaccion:</strong>
                                                                        {{  $dataC['numTransaccion'] }}</li>
                                                                    <li><strong>Nro de Comprobante:</strong>
                                                                        {{  $dataC['numComprobante'] }}</li>
                                                                    <li><strong>Origen Titular:</strong> {{  $dataC['origenTitular'] }}
                                                                    </li>
                                                                    <li><strong>Origen Cuenta:</strong> {{  $dataC['origenCuenta'] }}
                                                                    </li>
                                                                    <li><strong>Destino Beneficiario:</strong>
                                                                        {{  $dataC['destinoBeneficiario'] }}
                                                                    </li>
                                                                    <li><strong>Destino Cuenta:</strong> {{  $dataC['destinoCuenta'] }}
                                                                    </li>
                                                                    <li><strong>Monto Transferido:</strong>
                                                                        {{  $dataC['montoTransferido'] }}</li>
                                                                    <li><strong>Glosa:</strong> {{  $dataC['glosa'] }}</li>
                                                                </ul>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-4 mt-3">
                            <p> <strong>Captura de Pago</strong></p>
                            <img src="{{ asset($pago->imagenComprobante) }}" alt="" style="width:100%">
                        </div>
                    </div>


                </div>
            </div>
        @elseif($pago->pagado == 3)
            <div class="card">
                <div class="card-body">
                    <p>El pago registrado fue Rechazado</p>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <p>Pago no realizado</p>
                </div>
            </div>
        @endif
    @endforeach


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.collapse').forEach(function (collapse) {
                const button = document.querySelector(`[data-bs-target="#${collapse.id}"]`);
                const icon = button?.querySelector('i');

                if (!icon) return;

                collapse.addEventListener('shown.bs.collapse', function () {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                });

                collapse.addEventListener('hidden.bs.collapse', function () {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                });
            });
        });
    </script>
@endsection