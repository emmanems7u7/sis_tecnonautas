<table class="table table-dark table-bordered text-center align-middle">
    <thead class="thead-dark">
        <tr>
            <th style="width: 1%;">Hora</th>
            @foreach ($diasSemana as $dia)
                <th style="width: 17%;">{{ $dia }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rangos as $rango)
            <tr>
                <td><strong style="font-size: 0.85rem;">{{ $rango }}</strong></td>
                @foreach ($diasSemana as $dia)
                    <td style="padding: 2px;">
                        @php
                            $horaInicio = substr($rango, 0, 5);
                        @endphp
                        @if (!empty($horarioTabla[$horaInicio][$dia]))
                            @foreach ($horarioTabla[$horaInicio][$dia] as $item)
                                <div class="mb-1 p-2 rounded text-dark text-center position-relative"
                                    style="background-color: {{ $item['color'] }}; font-size: 0.70rem; line-height: 1.2; padding-top: 1.4rem;">

                                    {{-- Horario arriba a la derecha, en espacio reservado --}}
                                    <div class="position-absolute top-0 end-0 pe-1 pt-1" style="font-size: 0.65rem;">
                                        <i class="fas fa-clock me-1"></i>{{ $item['inicio'] }} - {{ $item['fin'] }}
                                    </div>

                                    {{-- Profesor al centro --}}
                                    <div class="fw-semibold" style="font-size: 0.75rem;     padding-top: 18px;">
                                        <i class="fas fa-user-tie me-1"></i>{{ $item['profesor'] }}
                                    </div>

                                    {{-- Línea inferior con asignación y módulo --}}
                                    <div class="mt-1">
                                        <i class="fas fa-chalkboard me-1"></i>{{ $item['asignacion_nombre'] }} |
                                        <i class="fas fa-book me-1"></i>{{ $item['modulo_nombre'] }}
                                    </div>
                                </div>
                            @endforeach


                        @else
                            <span class="text-muted" style="font-size: 0.65rem;">-</span>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>