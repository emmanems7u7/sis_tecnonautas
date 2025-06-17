<style>
    * {
        font-family: 'Arial', sans-serif;

    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        vertical-align: middle;
    }

    thead tr:first-child th {
        background-color: #4a90e2;
        color: white;
        font-weight: bold;
    }

    thead tr:nth-child(2) th {
        background-color: #d9e6f2;
        font-weight: normal;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody tr:hover {
        background-color: #f1f7ff;
    }

    tbody td[style*="color:red"] {
        color: red;
        font-weight: bold;
    }

    .card-info {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        background-color: #fefefe;
    }

    .card-info h2 {
        margin-top: 0;
        color: #2c3e50;
    }

    .card-info table td {
        padding: 8px;
        font-size: 13px;
    }

    .label {
        font-weight: bold;
        color: #555;
        width: 30%;
    }
</style>

<div id="tabla-estudiantes">
    <h6>TECNONAUTAS</h6>
    <div class="card-info">
        <h2>Reporte de Notas de Estudiantes</h2>
        <table>
            <tr>
                <td class="label">Materia:</td>
                <td>{{ $asignacion->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Módulo:</td>
                <td>{{ $modulo->nombreM }}</td>
            </tr>
            <tr>
                <td class="label">Total de Estudiantes:</td>
                <td>{{ $total }}</td>
            </tr>
            <tr>
                <td class="label">Generado por:</td>
                <td>{{ $nombre }}</td>
            </tr>
            <tr>
                <td class="label">Fecha y Hora:</td>
                <td>{{ $hora }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Estudiante</th>

                @if($data['tareas']->count())
                    <th colspan="{{ $data['tareas']->count() }}">Tareas</th>
                @endif

                @if($data['evaluaciones']->count())
                    <th colspan="{{ $data['evaluaciones']->count() }}">Evaluaciones</th>
                @endif
            </tr>
            <tr>
                @foreach($data['tareas'] as $tarea)
                    <th class="vertical-text">{{ $tarea->nombre }}</th>
                @endforeach

                @foreach($data['evaluaciones'] as $eval)
                    <th class="vertical-text">{{ $eval->nombre }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data['estudiantesTareas'] as $item)
                <tr>
                    <td>{{ $item['estudiante'] }}</td>

                    @foreach($data['tareas'] as $tarea)
                        @php
                            $notaTarea = collect($item['tareas'])->firstWhere('tareas_id', $tarea->id);
                        @endphp
                        <td style="{{ !$notaTarea ? 'color:red;' : '' }}">
                            {{ $notaTarea['nota'] ?? 0 }}
                        </td>
                    @endforeach

                    @foreach($data['evaluaciones'] as $eval)
                        @php
                            $notaEval = collect($item['evaluaciones'])->firstWhere('id_e', $eval->id);
                        @endphp
                        <td style="{{ !$notaEval ? 'color:red;' : '' }}">
                            {{ $notaEval['nota'] ?? 0 }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>