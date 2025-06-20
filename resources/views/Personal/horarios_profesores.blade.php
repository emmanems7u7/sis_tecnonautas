@extends('layouts.argon')


@section('content')


    <div class="alert alert-info text-white shadow-sm" role="alert">
        <h1 class="mb-3"><i class="fas fa-clock"></i> Visualización de Horarios Docentes</h1>

        <p class="mb-1">
            Esta sección muestra de manera clara y organizada los <strong>horarios de todos los profesores</strong> que
            están asignados actualmente a materias y paralelos activos.
        </p>

        <p class="mb-1">
            Puede visualizar la distribución de clases por día y hora, incluyendo el nombre del profesor, la materia, el
            módulo y el paralelo correspondiente.
        </p>

        <p class="mb-1"><strong>Acciones recomendadas:</strong> Revise la carga horaria para evitar conflictos, identificar
            espacios disponibles o planificar actividades complementarias.</p>

        <p class="mb-1"><strong>Opciones de Exportación:</strong> Puede exportar esta vista en formato PDF para impresión o
            uso administrativo.</p>
    </div>



    <div class="card">
        <div class="card-body">
            <div class="table-responsive">

                <a href="{{ route('horarios_profesores.export') }}" class="btn btn-sm btn-info mb-3" target="_blank">
                    Exportar PDF</a>

                @include('Personal.tabla_horarios_profesores')


            </div>


        </div>
    </div>

@endsection