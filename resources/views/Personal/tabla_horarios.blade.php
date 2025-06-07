@php
        // Arreglo de colores disponibles
        $colores = ['#0d6efd', '#198754', '#efb812', '#7b35dc', '#6f42c1', '#fd7e14', '#20c997'];

        use Carbon\Carbon;

        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
        $inicioDia = Carbon::createFromTime(8, 0); // desde 08:00
        $finDia = Carbon::createFromTime(19, 00);  // hasta 20:30
        $intervalo = 30; // minutos (bloques de media hora)

        // Convertimos los datos de materias para acceso rápido
        $horariosPorDia = [];
        foreach ($horariosF as $materia) {
            foreach ($materia['horarios'] as $horario) {
                $dia = $horario->dias;
                $inicio = Carbon::parse($horario->inicio);
                $fin = Carbon::parse($horario->fin);
                $horariosPorDia[$dia][] = [
                    'materia' => $materia['materia'],
                    'inicio' => $inicio,
                    'fin' => $fin
                ];
            }
        }

        // Asignamos colores fijos a las materias
        $materiasColores = [];
        foreach ($horariosF as $materia) {
            // Elegimos un color aleatorio y lo eliminamos del array de colores para evitar que se repita
            $color = $colores[array_rand($colores)];
            $materiasColores[$materia['materia']] = $color;

            // Eliminar el color asignado para no repetirlo
            $colores = array_diff($colores, [$color]);
        }
    @endphp

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>Hora</th>
                @foreach ($dias as $dia)
                    <th>{{ $dia }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                // Definir el horario de descanso laboral (13:30 a 15:00)
                $horaDescansoInicio = Carbon::createFromTime(13, 30); // Inicio descanso 13:30
                $horaDescansoFin = Carbon::createFromTime(15, 0);     // Fin descanso 15:00
            @endphp
            @for ($hora = $inicioDia->copy(); $hora <= $finDia; $hora->addMinutes($intervalo))
                <tr>
                    <td>{{ $hora->format('H:i') }}</td>
                    @foreach ($dias as $dia)
                            <td>
                            @php
                    // Verificamos si estamos dentro del horario de descanso
                    $enDescanso = $hora->between($horaDescansoInicio, $horaDescansoFin);
                @endphp
                                @if($enDescanso)
                                <div class="text-white p-1 rounded bg-dark">
                                         Descanso Laboral
                                            <br>
                                        </div>
                                @else

                                @php
                                    // Encontramos el bloque de horario correspondiente
                                    $enBloque = collect($horariosPorDia[$dia] ?? [])->first(function ($h) use ($hora) {
                                        return $hora->between($h['inicio'], $h['fin']); // No restamos minutos
                                    });
                                @endphp

                                @if ($enBloque)
                                        @php
                                            // Asignamos el color a la materia
                                            $color = $materiasColores[$enBloque['materia']];
                                        @endphp
                                        <div class="text-white p-1 rounded" style="background-color: {{ $color }};">
                                            {{ $enBloque['materia'] }}
                                            <br>
                                        </div>
                                @endif
 @endif

                            </td>
                    @endforeach
                </tr>
            @endfor
        </tbody>
    </table>