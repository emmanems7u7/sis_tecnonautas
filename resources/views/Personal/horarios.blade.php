@extends('layouts.argon')


@section('content')



    <div class="alert alert-info text-white shadow-sm" role="alert">
        <h1 class="mb-3"><i class="fas fa-clock"></i> Visualización de Horarios</h1>
        <p class="mb-1">Aquí puede visualizar los horarios de sus clases de manera clara y organizada. Revise sus materias,
            horarios de inicio y fin.</p>



        <p class="mb-1"><strong>Acciones recomendadas:</strong> Le recomendamos revisar su horario semanal para estar al
            tanto de sus próximas clases y evitar cualquier conflicto.</p>

        <p class="mb-1"><strong>Opciones de Exportación:</strong> Puede exportar su horario en formato PDF para tener una
            copia impresa o guardarla para su referencia.</p>
    </div>

    <a href="{{ route('reporte.horarios') }}" class="btn btn-sm btn-info mb-3" target="_blank"> Exportar PDF</a>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                @include('Personal.tabla_horarios')

            </div>


        </div>
    </div>

@endsection