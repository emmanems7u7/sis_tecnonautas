@extends('layouts.argon')

@section('content')


    <!-- Header -->
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4 text-dark">
                <h1 class="text-dark">Gestión de Correos remitentes de pagos</h1>
                <p class="lead">Filtra y gestiona correos </p>
            </div>
        </div>
    </div>



    <!-- Fila de tarjetas para filtros y selección de correos -->
    <div class="row mb-4 mt-3">
        <!-- Card para selección de correos -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Seleccionar Correos</h3>

                </div>
                <div class="card-body">
                    <form id="filterForm" method="POST" action="{{ route('filter.mails') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Correos Disponibles</label>
                            <div class="form-check">
                                @foreach ($correos as $correo)
                                    <input class="form-check-input" type="checkbox" name="correos[]" value="{{ $correo->id }}"
                                        id="correo{{ $correo->id }}">
                                    <label class="form-check-label" for="correo{{ $correo->id }}">
                                        {{ $correo->email }}
                                    </label>
                                    <br>
                                @endforeach
                            </div>
                        </div>

                        <!-- Filtros de fechas -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Fecha Inicio</label>
                                <input type="date" id="startDate" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">Fecha Fin</label>
                                <input type="date" id="endDate" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <!-- Botón para buscar correos filtrados -->
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" id="searchEmailsButton" type="submit">
                                <i class="bi bi-search"></i> Buscar Correos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Contenedor del spinner -->
        <div id="loading" class="d-none position-fixed top-50 start-50 translate-middle text-center bg-transparent"
            style="z-index: 1000;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-primary">Cargando resultados...</p>
        </div>

        <!-- Card para mostrar correos filtrados -->

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Correos Filtrados</h3>
                </div>
                <div class="card-body">
                    <div id="filteredEmailsContainer" class="row">
                        <!-- Aquí se inyectarán las cards de correos filtrados -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('searchEmailsButton').addEventListener('click', function (event) {
            event.preventDefault();
            // Mostrar el spinner de carga
            document.getElementById('loading').classList.remove('d-none');

            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            // Obtener los correos seleccionados
            const selectedEmails = Array.from(document.querySelectorAll('input[name="correos[]"]:checked'))
                .map(checkbox => checkbox.value);

            fetch('{{ route("filter.mails") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate,
                    correos: selectedEmails
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al filtrar los correos');
                    }
                    return response.json();
                })
                .then(emails => {
                    console.log(emails)
                    if (emails.status == 'error') {
                        alertify.alert(emails.message);
                        document.getElementById('loading').classList.add('d-none');

                    }
                    else {
                        const container = document.getElementById('filteredEmailsContainer');
                        container.innerHTML = '';
                        // Ocultar el spinner de carga
                        document.getElementById('loading').classList.add('d-none');
                        emails.forEach(email => {
                            const card = document.createElement('div');
                            card.classList.add('col-md-12');
                            card.innerHTML = `
                                                                    <div class="card mb-4">
                                                                        <div class="card-body">
                                                                            <h5 class="card-title">${email.subject}</h5>
                                                                            <h6 class="card-subtitle mb-2 text-muted">${email.date}</h6>

                                                                            <p class="card-text"><strong>De:</strong> ${email.from}</p>
                                                                        </div>
                                                                    </div>
                                                                `;
                            console.log("data " + email.body);
                            container.appendChild(card);
                        });
                    }

                })
                .catch(error => {
                    alert(error.message);
                });
        });
    </script>


@endsection