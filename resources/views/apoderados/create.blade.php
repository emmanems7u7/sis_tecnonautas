@section('create')
    <div class="modal fade" id="modalAgregarApoderado" tabindex="-1" role="dialog"
        aria-labelledby="modalAgregarApoderadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div
                class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarApoderadoLabel">Agregar Apoderado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <form action="{{ route('apoderados.store') }}" method="POST">
                        @csrf

                        <div class="form-group row mt-2">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">Nombre</label>

                            <div class="col-md-6">
                                <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>

                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="apepat" class="col-md-4 col-form-label text-md-right">Apellido Paterno</label>

                            <div class="col-md-6">
                                <input id="apepat" type="text" class="form-control @error('apepat') is-invalid @enderror"
                                    name="apepat" value="{{ old('apepat') }}" required autocomplete="apepat">

                                @error('apepat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="apemat" class="col-md-4 col-form-label text-md-right">Apellido Materno</label>

                            <div class="col-md-6">
                                <input id="apemat" type="text" class="form-control @error('apemat') is-invalid @enderror"
                                    name="apemat" value="{{ old('apemat') }}" required autocomplete="apemat">

                                @error('apemat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="parentezco" class="col-md-4 col-form-label text-md-right">Parentezco</label>

                            <div class="col-md-6">
                                <input id="parentezco" type="text"
                                    class="form-control @error('parentezco') is-invalid @enderror" name="parentezco"
                                    value="{{ old('parentezco') }}" required autocomplete="parentezco">

                                @error('parentezco')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="fechanac" class="col-md-4 col-form-label text-md-right">Fecha de Nacimiento</label>

                            <div class="col-md-6">
                                <input id="fechanac" type="date"
                                    class="form-control @error('fechanac') is-invalid @enderror" name="fechanac"
                                    value="{{ old('fechanac') }}" required>

                                @error('fechanac')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="ci" class="col-md-4 col-form-label text-md-right">Carnet de Identidad</label>

                            <div class="col-md-6">
                                <input id="ci" type="text" class="form-control @error('ci') is-invalid @enderror" name="ci"
                                    value="{{ old('ci') }}" required autocomplete="ci">

                                @error('ci')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="nit" class="col-md-4 col-form-label text-md-right">NIT</label>

                            <div class="col-md-6">
                                <input id="nit" type="text" class="form-control @error('nit') is-invalid @enderror"
                                    name="nit" value="{{ old('nit') }}" autocomplete="nit">

                                @error('nit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Correo Electrónico</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="celulares" class="col-md-4 col-form-label text-md-right">Celulares</label>

                            <div class="col-md-6">
                                <div id="celulares-container">
                                    <div class="input-group mb-3">
                                        <input type="text" name="celulares[]" class="form-control"
                                            placeholder="Número de celular" aria-label="Número de celular"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="addCelular()"
                                                type="
                                                                    button">+</button>
                                        </div>
                                    </div>
                                </div>

                                @error('celulares')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Agrega más campos según tus necesidades -->

                        <div class="form-group row mt-2 mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                                <a href="{{ route('apoderados.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        function addCelular() {
            var html = '<div class="input-group mb-3"><input type="text" name="celulares[]" class="form-control" placeholder="Número de celular" aria-label="Número de celular" aria-describedby="basic-addon2"><div class="input-group-append"><button class="btn btn-outline-secondary" onclick="removeCelular(this)" type="button">-</button></div></div>';
            $('#celulares-container').append(html);
        }
        function removeCelular(button) {
            $(button).closest('.input-group').remove();
        }

    </script>

@endsection