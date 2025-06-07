@extends('layouts.argon')

@section('content')
    <style>
        .email-body {
            max-width: 100%;
            overflow: auto;
        }

        /* Ajuste del spinner */
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }
    </style>


    <div class="row">
        <!-- Primera columna -->
        <div class="col-md-3">
            <h3 class="mb-4 text-white">Gestión de Correos</h3>
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-inbox me-3 text-primary"></i>
                    <span>Recibidos</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-paper-plane me-3 text-success"></i>
                    <span>Enviados</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-trash-alt me-3 text-danger"></i>
                    <span>Eliminados</span>
                </a>
            </div>
        </div>

        <!-- Segunda columna -->
        <div class="col-md-9">



            <!-- Formulario de filtro -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h1 class="mb-4 text-white">Filtrar Correos</h1>
                    <form id="filter-form">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    <label for="start_date">Fecha de Inicio</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    <label for="end_date">Fecha de Fin</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Filtrar por dirección de email">
                                    <label for="email">Dirección de Email</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Contenedor de resultados -->
            <div id="results" class="mt-4"></div>

        </div>
    </div>
    <!-- Contenedor del spinner -->
    <div id="loading" class="d-none position-fixed top-50 start-50 translate-middle text-center bg-transparent">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="mt-2 text-primary">Cargando resultados...</p>
    </div>


    <!-- Modal -->
    <!-- Modal para mostrar detalles del correo -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Detalles del Correo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="modal-subject"></h5>
                    <p><strong>Desde:</strong> <span id="modal-from"></span></p>
                    <p><strong>Fecha:</strong> <span id="modal-date"></span></p>
                    <hr>
                    <div id="modal-body-content"></div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .spinner-border {
            width: 4rem;
            height: 4rem;
            border-width: 0.4em;
        }

        #loading {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 2rem;
        }

        #results {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            background-color: #ffffff;
            padding: 1rem;
        }

        .email-body {
            max-width: 100%;
            overflow: auto;
            word-wrap: break-word;
        }

        .card {
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            font-size: 0.875rem;
        }
    </style>
    <script>
        document.getElementById('filter-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional

            const formData = new FormData(this);
            const params = new URLSearchParams();

            // Construir los parámetros de consulta
            formData.forEach((value, key) => {
                if (value) { // Solo agregar parámetros no vacíos
                    params.append(key, value);
                }
            });

            // Mostrar el spinner de carga
            document.getElementById('loading').classList.remove('d-none');

            // Construir la URL con parámetros
            const url = `{{ route('mails.filter') }}?${params.toString()}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    const resultsDiv = document.getElementById('results');
                    resultsDiv.innerHTML = ''; // Limpiar resultados anteriores

                    if (data.length > 0) {
                        data.forEach(email => {
                            resultsDiv.innerHTML += `
                                                                                    <div class="card mb-3" data-bs-toggle="modal" data-bs-target="#emailModal" 
                                                                                        onclick="abrir_modal_pagos('${email.subject}', '${email.from}', '${email.date}', '${encodeURIComponent(email.body)}')">
                                                                                        <div class="card-body">
                                                                                            <h5 class="card-title">${email.subject}</h5>
                                                                                            <h6 class="card-subtitle mb-2 text-muted">Desde: ${email.from}</h6>
                                                                                            <h6 class="card-subtitle mb-2 text-muted" style="text-align: right;">Fecha: ${email.date}</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                `;

                        });
                    } else {
                        resultsDiv.innerHTML = '<p class="text-muted">No se encontraron correos con los criterios especificados.</p>';
                    }

                    // Ocultar el spinner de carga
                    document.getElementById('loading').classList.add('d-none');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loading').classList.add('d-none');
                });
        });
        function abrir_modal_pagos(subject, from, date, data) {




            const body = decodeURIComponent(data);

            const modalTitle = document.getElementById('emailModalLabel');
            const modalSubject = document.getElementById('modal-subject');
            const modalFrom = document.getElementById('modal-from');
            const modalDate = document.getElementById('modal-date');
            const modalBodyContent = document.getElementById('modal-body-content');


            console.log(body);
            modalTitle.textContent = 'Detalles del Correo';
            modalSubject.textContent = subject;
            modalFrom.textContent = from;
            modalDate.textContent = date;
            modalBodyContent.innerHTML = body;

            // Extraer datos del correo
            const remitente = extraerDato(body, "De", /<a\s+href=["']mailto:([^"']+)["']/);


            const fecha = extraerDato(body, "Date", /Date:\s*[^>]*>\s*([^<]+)/);
            const asunto = extraerDato(body, "Subject", /Subject:\s*([^<]+)/);

            const numTransaccion = extraerDato(body, "N° Transacción", /N° Transacción:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>(\d+)/);
            const numComprobante = extraerDato(body, "N° Comprobante", /N° Comprobante:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>(\d+)/);

            const origenTitular = extraerDato(body, "Origen Titular", /Titular:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>([^<]+)/);
            const origenCuenta = extraerDato(body, "Origen Cuenta", /Cuenta:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>(\d+)/);

            const destinoBeneficiario = extraerDato(body, "Destino Beneficiario", /Beneficiario:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>([^<]+)/);
            const destinoCuenta = extraerDato(body, "Destino Cuenta", /Cuenta:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>BIL<\/font><br>\s*<font[^>]*>(\d+)/);

            const montoTransferido = extraerDato(body, "Monto Transferido", /Monto transferido:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>([^<]+)/);
            const glosa = extraerDato(body, "Glosa", /Glosa:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>([^<]+)/);

            // Mostrar los resultados en consola
            console.log("📧 Datos Extraídos:");
            console.log("Remitente:", remitente);
            console.log("Fecha:", fecha);
            console.log("Asunto:", asunto);
            console.log("N° Transacción:", numTransaccion);
            console.log("N° Comprobante:", numComprobante);
            console.log("Origen Titular:", origenTitular);
            console.log("Origen Cuenta:", origenCuenta);
            console.log("Destino Beneficiario:", destinoBeneficiario);
            console.log("Destino Cuenta:", destinoCuenta);
            console.log("Monto Transferido:", montoTransferido);
            console.log("Glosa:", glosa);
        }


        // Obtener el contenido del correo en formato HTML (simulado aquí con document.body.innerHTML)
        const emailHTML = document.body.innerHTML;

        // Función para extraer datos con expresiones regulares
        function extraerDato(html, etiqueta, regex) {
            const match = html.match(regex);
            return match ? match[1].trim() : `No encontrado (${etiqueta})`;
        }



        // Inicializar la carga de correos en la página con la fecha actual
        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').value = today;
            document.getElementById('end_date').value = today;
            document.getElementById('filter-form').dispatchEvent(new Event('submit'));
        });



    </script>

    <style>
        .email-body {
            /* Asegúrate de que los estilos del correo no interfieran con el sistema */
            max-width: 100%;
            overflow: auto;
        }
    </style>

@endsection