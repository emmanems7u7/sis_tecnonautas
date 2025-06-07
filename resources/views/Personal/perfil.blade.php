@section('perfil')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-user-edit"></i> Cambiar Nombre/Apellidos
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-key"></i> Cambiar Contraseña
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-image"></i> Cambiar Foto de Perfil
                        </li>
                        <li class="list-group-item">
                            <a href="{{route('apoderados.index')}}"><i class="fas fa-image"></i> Datos de Apoderados</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
@endsection