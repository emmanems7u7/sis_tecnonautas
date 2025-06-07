@extends('layouts.argon')


@section('content')


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb"
            style="background-color: #f8f9fa; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"
                    style="color: #007bff; text-decoration: none;">Inicio</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Horarios</li>
        </ol>
    </nav>


    <div class="alert alert-info shadow-sm" role="alert">
        <h1 class="mb-3"><i class="fas fa-clock"></i> Visualización de Horarios</h1>
        <p class="mb-1">Aquí puede visualizar los horarios de sus clases de manera clara y organizada. Revise sus materias,
            horarios de inicio y fin.</p>



        <p class="mb-1"><strong>Acciones recomendadas:</strong> Le recomendamos revisar su horario semanal para estar al
            tanto de sus próximas clases y evitar cualquier conflicto.</p>

        <p class="mb-1"><strong>Opciones de Exportación:</strong> Puede exportar su horario en formato PDF para tener una
            copia impresa o guardarla para su referencia.</p>
    </div>

    <a href="{{ route('reporte.horarios') }}" class="btn btn-sm btn-info mb-3" target="_blank"> Exportar PDF</a>

    @include('Personal.tabla_horarios')

@endsection