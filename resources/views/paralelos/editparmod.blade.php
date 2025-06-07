@extends('layouts.argon')

@section('content')

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Editar Paralelo</h5>
                <form action="{{route('ParaleloModulo.update', ['id' => $idp])}}" enctype="multipart/form-data"
                    method="post" onsubmit="">
                    @csrf
                    <div class="form-group">
                        <label for="paralelo">Paralelo </label>
                        <select class="form-control" id="paralelo" name="paralelo">
                            @foreach($datos as $dato)
                                <option value="{{ $dato['nombre'] }}">{{ $dato['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="horarios">
                        @foreach($datos as $dato)
                            <div class="form-row">
                                @foreach($dato['horarios'] as $horario)
                                    <div class="col-md-4">
                                        <label for="dia">Día</label>
                                        <input type="hidden" value="{{$horario['id']}}" name="horarioid[]">
                                        <select class="form-control" name="dia[]">
                                            <option value="Lunes" {{ $horario['dia'] === 'lunes' ? 'selected' : '' }}>Lunes</option>
                                            <option value="Martes" {{ $horario['dia'] === 'martes' ? 'selected' : '' }}>Martes
                                            </option>
                                            <option value="Miercoles" {{ $horario['dia'] === 'miercoles' ? 'selected' : '' }}>
                                                Miércoles</option>
                                            <option value="Jueves" {{ $horario['dia'] === 'jueves' ? 'selected' : '' }}>Jueves
                                            </option>
                                            <option value="Viernes" {{ $horario['dia'] === 'viernes' ? 'selected' : '' }}>Viernes
                                            </option>
                                            <option value="Sabado" {{ $horario['dia'] === 'sabado' ? 'selected' : '' }}>Sábado
                                            </option>

                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="horaInicio">Hora de Inicio</label>
                                        <input type="time" class="form-control" name="horaInicio[]"
                                            value="{{ substr($horario['horaInicio'], 0, 5) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="horaFin">Hora de Fin</label>
                                        <input type="time" class="form-control" name="horaFin[]"
                                            value="{{ substr($horario['horaFin'], 0, 5) }}">
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group mt-3">
                        <label for="profesor">Profesor Asignado</label>
                        <select class="form-control" id="profesor" name="profesor">
                            @foreach($profesores as $prof)
                                <option value="{{ $prof->id }}" {{ $prof->id === $dato['profesorid'] ? 'selected' : '' }}>
                                    {{ $prof->name }} {{ $prof->apepat }} {{ $prof->apemat }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <button type="submit" class="btn btn-primary btn-block">editar Paralelo</button>
                </form>
            </div>
        </div>
    </div>
@endsection