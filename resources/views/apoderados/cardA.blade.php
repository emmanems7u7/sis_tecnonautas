@section('cardA')

<div class="row">
    @if($apoderados !== null && count($apoderados) > 0)
        @foreach ($apoderados as $apoderado)
            <div class="col-md-4 mb-4 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $apoderado['parentezco'] }}</h5>
                        <p class="card-text"><strong>Nombre:</strong> {{ $apoderado['nombre'] }}</p>
                        <p class="card-text"><strong>Apellidos:</strong> {{ $apoderado['apepat'] }} {{ $apoderado['apemat'] }}</p>
                        <p class="card-text"><strong>Fecha de Nacimiento:</strong> {{ $apoderado['fechanac'] }}</p>
                        <p class="card-text"><strong>Carnet de Identidad:</strong> {{ $apoderado['ci'] }}</p>
                        <p class="card-text"><strong>NIT:</strong> {{ $apoderado['nit'] }}</p>
                        <p class="card-text"><strong>Correo Electrónico:</strong> {{ $apoderado['email'] }}</p>
                        
                        <p class="card-text"><strong>Celulares:</strong></p>
                        @foreach($apoderado['celular'] as $cel)
                            <p class="card-text">{{ $cel['celular'] }}</p>
                        @endforeach

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('apoderados.edit', ['apoderado' => $apoderado['id'] ]) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('apoderados.destroy', ['apoderado' => $apoderado['id'] ]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <p>No hay apoderados registrados.</p>
        </div>
    @endif
</div>

@endsection
