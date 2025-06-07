@extends('layouts.argon')


@section('content')
    <div class="container-fluid mt-4">
    <div class="alert alert-info" role="alert" style="text-align: justify;">
    <h5 class="alert-heading">Información sobre el proceso de pago</h5>
    <p><strong>Aceptación Automática del Pago:</strong> La aceptación de su pago es automática. Sin embargo, debe esperar entre 5 y 30 minutos para que su pago sea aceptado y procesado correctamente.</p>
    <p><strong>Acceso al Contenido:</strong> Mientras su pago no sea rechazado, podrá acceder al contenido de la materia y módulo correspondiente al pago realizado.</p>
    <p><strong>Posibles Errores:</strong> Si algún dato ingresado es incorrecto o no cumple con los requisitos establecidos, el pago será revisado por un administrador.</p>
    <p><strong>Acción en Caso de Error:</strong> Si el administrador detecta algún error en los datos del pago, será necesario que registre nuevamente el formulario de pago para que el proceso continúe de forma correcta.</p>

</div>

        <div class="row">
        @if($materiaArray != null)
            @foreach ($materiaArray as $materia)
                <div class="col-md-12 mb-4">
                    <div class="card shadow border-0 h-100">
                        <div class="card-header text-black bg-gradient d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(135deg, #4CAF50, #2E7D32);">
                            <h5 class="mb-0">
                                <i class="fas fa-book"></i> {{ $materia['asignacion'] }}
                            </h5>
                            <span class="badge bg-light text-dark px-3 py-2">
                                Bs.{{ number_format($materia['costo'], 2) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <h6 class="text-muted"><i class="fas fa-list-ul"></i> Módulos inscritos:</h6>
                            <div class="list-group mt-2">
                                @foreach ($materia['modulos_inscritos'] as $modulo)
                                    <div class="list-group-item border-0 d-flex justify-content-between align-items-center shadow-sm p-3 mb-2 rounded"
                                        style="background: rgba(0,0,0,0.05);">
                                        <div>


                                            <h6 class="mb-1"><i class="fas fa-chalkboard"></i> {{ $modulo['modulo'] }}</h6>
                                            <small class="text-secondary"><i class="fas fa-calendar-alt"></i> Fecha de Inscripción
                                                {{ \Carbon\Carbon::parse($modulo['fecha_registro'])->format('d/m/Y') }}</small>
                                        </div>

                                        @switch($modulo['pagado'])
                                            @case(1)
                                                <span class="badge bg-success">Pagado</span>
                                                @break

                                            @case(0)
                                                <span class="badge bg-danger">Pago Pendiente</span>
                                                @break

                                            @case(2)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-spinner fa-spin"></i> Procesando Pago
                                                </span>
                                                @break
                                                @case(3)
                                                <span class="badge bg-danger">
                                                  Pago Rechazado
                                                </span>
                                                @break

                                            @default
                                                <span class="badge bg-secondary">Estado Desconocido</span>
                                        @endswitch




                                        @if($modulo['pagado'] == 0||$modulo['pagado'] == 3)

                                       
                                            <button class="btn btn-sm btn-danger"
                                                onclick="AdjuntarPago({{$modulo['id_pago'] }})">Pagar</button>
                                        @else
                                        @php   
                                        
                                        @endphp
                                            <button class="btn btn-sm btn-success" disabled>Pagado</button>
                                        @endif

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @else
              <p>No hay materias por pagar</p>
            @endif
        </div>
    </div>




    <div class="modal fade" id="modal_pagos" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="miModalLabel">Realizar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <div class="col-12 col-md-5">
                            <form id='formPago' action="{{ route('admpagos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Mensaje de cabecera -->
                                <div class="alert alert-info mb-4" role="alert">
                                    <strong>Por favor, lea e ingrese los datos correctamente.</strong> Asegúrese de que
                                    todos los
                                    campos sean correctos antes de enviar el formulario.
<br>
                                    ¿No estás seguro de cómo hacer el pago? <a href="https://www.youtube.com/watch?v=ZfKlvuB-vdc" target="_blank">Haz clic aquí</a> para ver una guía paso a paso.
                                </div>

                                <!-- Columna izquierda (Formulario) -->

                                <!-- Sección: Método de Pago -->
                                <div class="mb-3">
                                    <label for="metodo_pago" class="form-label">Método de Pago</label>
                                    <select class="form-select" name="tipo_pago_s" id="tipo_pago_s" onchange="obtener_datos_cuenta(this.value);">
                                        <option value="" disabled selected>Seleccione un tipo de pago</option>

                                        @foreach($tiposDePago as $tipoPago)
                                            <option value="{{ $tipoPago->id }}">{{ $tipoPago->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Seleccione el método de pago que utilizará.</div>
                                    @error('metodo_pago')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sección: Apoderado -->
                                <div class="mb-3">
                                    <label for="id_a_s" class="form-label">Apoderado</label>
                                    <select class="form-select" id="id_a_s" name="id_a_s" required>
                                        <option value="" disabled selected>Seleccione apoderado</option>

                                        @foreach($apoderados as $apoderado)
                                            <option value="{{ $apoderado->id }}">{{ $apoderado->nombre }}
                                                {{ $apoderado->apepat }} {{ $apoderado->apemat }} | {{ $apoderado->parentezco }}
                                            </option>

                                        @endforeach
                                    </select>
                                    <div class="form-text">Seleccione el apoderado correspondiente.</div>
                                    @error('id_a')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sección: Monto, Número de Comprobante y Fecha de Pago -->
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="monto" class="form-label">Monto</label>
                                            <input type="number" class="form-control @error('monto') is-invalid @enderror"
                                                id="monto" name="monto" required>
                                            <div class="form-text">Ingrese el monto que ha pagado.</div>
                                            @error('monto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="numeroComprobante" class="form-label">Número de Comprobante</label>
                                            <input type="text"
                                                class="form-control @error('numeroComprobante') is-invalid @enderror"
                                                id="numeroComprobante" name="numeroComprobante" required>
                                            <div ccióss="form-text">Ingrese el número de transacción de pago.</div>
                                            @error('numeroComprobante')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                                            <input type="date"
                                                class="form-control @error('fecha_pago') is-invalid @enderror"
                                                id="fecha_pago" name="fecha_pago" required>
                                            <div class="form-text">Seleccione la fecha en que se realizó el pago.</div>
                                            @error('fecha_pago')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección: Imagen del Comprobante -->
                                <div class="mb-3">
                                    <label for="imagenComprobante" class="form-label">Imagen del Comprobante</label>
                                    <input type="file" class="form-control @error('imagenComprobante') is-invalid @enderror"
                                        id="imagenComprobante" name="imagenComprobante" onchange="previewImage(event)"
                                        accept="image/*">
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <img id="previewImg" src="" alt="Imagen Comprobante" class="img-fluid"
                                            style="max-height: 200px;">
                                        <button type="button" class="btn btn-danger mt-2" onclick="removeImage()">Quitar
                                            Imagen</button>
                                    </div>
                                    <div class="form-text">Suba la imagen del comprobante de pago.</div>
                                    @error('imagenComprobante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Columna derecha (Vista previa de la imagen) -->
                                <div class="col-12 col-md-6">
                                    <!-- Imagen o área de vista previa -->
                                    <div id="imagePreview" class="mt-4" style="display: none;">
                                        <img id="previewImg" src="" alt="Imagen Comprobante" class="img-fluid"
                                            style="max-height: 300px;">
                                    </div>
                                </div>


                                <input type="hidden" id="pago_id" name="pago_id">

                               
                                <div class="alert alert-info" role="alert" >
                                Por favor, reenvía el correo del pago que realizaste a <strong>diegoc9716@gmail.com</strong> con el asunto <strong>Pago Estudiante</strong>.
                                Ten en cuenta que el correo puede demorar entre 30 minutos y 1 hora en llegar, por lo que te pedimos un poco de paciencia.
                                Una vez que lo hayas recibido y reenviado, marca el check para confirmar.
                                <br>
                                ¿No te llegó el correo aún? No te preocupes, puedes marcar el check de todas formas, nuestro sistema se encargará del resto
                                <i class="fas fa-smile" style="color: orange;"></i>
<br>
                                


                                    </div>

                                    <!-- Confirmación del reenviado -->
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="confirmCorreo" required>
                                        <label class="form-check-label" for="confirmCorreo">
                                            He reenviado el correo de pago.
                                        </label>
                                    </div>

                                <hr>

                                <button type="submit" class="btn btn-success" id="submitButton" disabled>Registrar Pago</button>
                            </form>
                            <!-- Loader -->
                        <div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
                            background:rgba(255,255,255,0.8); z-index:9999; text-align:center; padding-top:20%;">
                            <div class="spinner-border text-warning" role="status" style="width: 4rem; height: 4rem;"></div>
                            <p style="margin-top:20px; font-weight:bold;">Procesando tu pago... esto puede tomar unos segundos</p>
                        </div>
                        </div>

                        <div class="col-12 col-md-7 mt-3" id="datos_inscripcion">
                            <div class="col-12 col-md-12" id="datos_inscripcion">
                                <!-- Card con Información de Asignación -->
                                <div class="card shadow-sm p-3 mb-4">
                                    <h5 class="card-title text-center mb-3">Detalles del Pago</h5>

                                    <div class="row">
                                        <!-- Columna 1 -->
                                        <div class="col-12 col-md-6 mb-2">
                                            <!-- Asignacion -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="icon-box bg-primary text-white me-2 p-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-muted">Asignación:</strong>
                                                    <p class="fw-bold mb-0" id="asignacion">s</p>
                                                </div>
                                            </div>

                                            <!-- Módulo -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="icon-box bg-primary text-white me-2 p-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-bookmark"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-muted">Módulo:</strong>
                                                    <p class="fw-bold mb-0" id="modulo">Módulo 1: Introducción a la
                                                        Programación</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Columna 2 -->
                                        <div class="col-12 col-md-6 mb-2">
                                            <!-- Tipo de Pago -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="icon-box bg-success text-white me-2 p-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-muted">Tipo de Pago:</strong>
                                                    <p class="fw-bold mb-0" id="tipo_pago">Pago Único</p>
                                                </div>
                                            </div>

                                            <!-- Precio -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="icon-box bg-warning text-white me-2 p-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-muted">Precio:</strong>
                                                    <p class="fw-bold mb-0" id="precio">$300.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row" id="datos_cuentas">
                                <div class="col-12 col-md-12">
                                    <div id="contenido_cuentas">

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>


                </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('formPago').addEventListener('submit', function () {
        document.getElementById('loader').style.display = 'block';
    });
</script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('confirmCorreo');
    const submitButton = document.getElementById('submitButton');

    // Habilitar el botón solo si el checkbox está marcado
    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            submitButton.disabled = false;  // Habilitar el botón si el checkbox está marcado
        } else {
            submitButton.disabled = true;  // Deshabilitar el botón si el checkbox no está marcado
        }
    });
});

        

        function obtener_datos_cuenta(metodo) {
            const contenidoCuentas = document.getElementById('contenido_cuentas');
            contenidoCuentas.innerHTML = '';
            fetch('/datos/cuentas/' + metodo)
                .then(response => response.json())
                .then(data => {
                    var cuentaHTML = '';
                    cuentaHTML = cuentaHTML + `<div class="row mb-3">`;

                    data.forEach((item, index) => {
                        cuentaHTML += `
                                                                                               <div class="col-md-6">
                                                                                                   <div class="card">
                                                                                                       <img src="${item.imagen}" alt="Imagen" class="card-img-top" style="width: 100%; height: auto;" />
                                                                                                       <div class="card-body">
                                                                                                           <h5 class="card-title">Banco: ${item.banco}</h5>
                                                                                                           <p class="card-text">Detalle: ${item.detalle}</p>
                                                                                                           <p class="card-text">Número de Cuenta: ${item.numero_cuenta}</p>
                                                                                                       </div>
                                                                                                   </div>
                                                                                               </div>
                                                                                           `;
                    });

                    contenidoCuentas.innerHTML += cuentaHTML;


                })
                .catch(error => {
                    console.error('Error al cargar las materias:', error);
                });
        }
    </script>

    <script>
        function AdjuntarPago(id_pago) {
            var modal = new bootstrap.Modal(document.getElementById('modal_pagos'));
            var asignacion = document.getElementById('asignacion');
            var modulo = document.getElementById('modulo');
            var tipo_pago = document.getElementById('tipo_pago');
            var precio = document.getElementById('precio');

            var pago = document.getElementById('pago_id');
            fetch('/pagos/detalle/' + id_pago)
                .then(response => response.json())
                .then(data => {
                    asignacion.innerText = data.asignacion;
                    modulo.innerText = data.modulo
                    tipo_pago.innerText = data.tipo
                    precio.innerText = 'Bs. ' + data.costo
                    modal.show();
                    pago.value = id_pago;
                })
                .catch(error => {
                    console.error('Error al cargar las materias:', error);
                });
        }
    </script>

    <script>
        // Función para mostrar la vista previa de la imagen
        function previewImage(event) {
            const image = document.getElementById('previewImg');
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.style.display = 'block';
            image.src = URL.createObjectURL(event.target.files[0]);
        }

        // Función para quitar la imagen
        function removeImage() {
            const imagePreview = document.getElementById('imagePreview');
            const image = document.getElementById('previewImg');
            imagePreview.style.display = 'none';
            image.src = '';
        }
    </script>
@endsection