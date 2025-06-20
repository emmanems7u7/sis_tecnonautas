<style>
    table.horario {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
        font-family: Arial, sans-serif;
        background-color: #fff;
    }

    table.horario th,
    table.horario td {
        border: 1px solid #bbb;
        padding: 4px 6px;
        vertical-align: middle;
        text-align: center;
    }

    table.horario thead tr {
        background-color: #0a468a;
        color: white;
        font-weight: 600;
        font-size: 10px;
    }

    .horario-hora {
        font-size: 8px;
        font-weight: 600;
        color: #444;
    }

    .horario-profesor {
        font-weight: 700;
        margin-top: 2px;
        color: #111;
    }

    .horario-detalle {
        margin-top: 2px;
        font-size: 8px;
        color: #666;
    }

    .horario-vacio {
        font-size: 7px;
        color: #999;
        font-style: italic;
    }
</style>


<table width="100%">
    <tr>
        <td></td>
        <td style="text-align: center;">
            <p style="text-align: center; font-family: Arial">REPORTE DE HORARIOS TECNONAUTAS</p>
        </td>
        <td style="text-align: right;">
            <img src="{{ $src_body }}" alt="TECNONAUTAS" style="width: 50px;">
        </td>
    </tr>
</table>

<table style="width: 300px; border-collapse: collapse; font-family: Arial, sans-serif; margin: 10px 0;">
    <tr>
        <th style="text-align: left; padding: 8px; background-color: #0a468a; color: white; font-size: 14px;"> Generado
            por
        </th>
        <td
            style="padding: 8px; border-bottom: 1px solid #ddd; font-size: 13px; background-color:  #007BFF; color:white">
            {{ $nombre }}
        </td>
    </tr>
    <tr>
        <th style="text-align: left; padding: 8px; background-color: #0a468a; color: white; font-size: 14px;">Hora:</th>
        <td
            style="padding: 8px; border-bottom: 1px solid #ddd; font-size: 13px; background-color:  #007BFF; color:white">
            {{ $hora }}
        </td>
    </tr>
    <tr>
        <th style="text-align: left; padding: 8px; background-color: #0a468a; color: white; font-size: 14px;">Total de
            profesores</th>
        <td style="padding: 8px; font-size: 13px; background-color:  #007BFF; color:white">{{ $total }}</td>
    </tr>
</table>


<table class="horario">
    <thead>
        <tr>
            <th style="width: 12%;  color: white">Hora</th>
            @foreach ($diasSemana as $dia)
                <th style="width: 18%; color: white">{{ $dia }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rangos as $rango)
            <tr>
                <td><strong>{{ $rango }}</strong></td>
                @foreach ($diasSemana as $dia)
                    @php
                        $horaInicio = substr($rango, 0, 5);
                    @endphp
                    <td>
                        @if (!empty($horarioTabla[$horaInicio][$dia]))
                            @foreach ($horarioTabla[$horaInicio][$dia] as $item)
                                <table class=""
                                    style="background-color: {{ $item['color'] }};  color: #000; border-collapse: collapse; border: none; font-family: Arial, sans-serif;">
                                    <tr>
                                        <td style="text-align: right; padding: 5px; font-weight: bold; font-size: 8px; border: none;">
                                            {{ $item['inicio'] }} - {{ $item['fin'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px; border: none;">
                                            {{ $item['profesor'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; font-size: 10px; border: none;">
                                            {{ $item['asignacion_nombre'] }} | {{ $item['modulo_nombre'] }}
                                        </td>
                                    </tr>
                                </table>



                            @endforeach
                        @else
                            <div class="horario-vacio">-</div>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>