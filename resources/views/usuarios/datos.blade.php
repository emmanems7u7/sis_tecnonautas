<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="{{ route('users.update', ['id' => Auth::user()->id, 'perfil' => 1]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Editar Perfil</p>
                        <button type="submit" class="btn btn-primary btn-sm ms-auto">Actualizar Datos</button>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-uppercase text-sm">Informacion de Usuario</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profile_picture" class="form-control-label">Foto de Perfil</label>
                                <div class="d-flex align-items-center">
                                    <!-- Campo para cargar imagen -->
                                    <input type="file" id="profile_picture" name="profile_picture"
                                        class="form-control @error('profile_picture') is-invalid @enderror"
                                        accept="image/*" onchange="previewImage(event)">

                                    <!-- Imagen previsualizada -->
                                    <div class="ms-3" id="preview-container">
                                        <img id="preview-img" src="#" alt="Previsualización"
                                            style="display: none; width: 80px; height: 80px; border-radius: 10%; object-fit: cover;">
                                    </div>

                                    <!-- Botón para eliminar imagen -->
                                    <button type="button" id="remove-img" class="btn btn-danger ms-2"
                                        style="display: none;" onclick="removeImage()">Eliminar</button>
                                </div>
                                @error('profile_picture')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Nombre de usuario</label>
                                <input id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                                    type="text" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">Email</label>
                                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                                    name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_nombres" class="form-control-label">Nombre</label>
                                <input id="usuario_nombres"
                                    class="form-control @error('usuario_nombres') is-invalid @enderror" type="text"
                                    name="usuario_nombres" value="{{ old('usuario_nombres', $user->usuario_nombres) }}">
                                @error('usuario_nombres')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_app" class="form-control-label">Apellido Paterno</label>
                                <input id="usuario_app" class="form-control @error('usuario_app') is-invalid @enderror"
                                    name="usuario_app" type="text" value="{{ old('usuario_app', $user->usuario_app) }}">
                                @error('usuario_app')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_apm" class="form-control-label">Apellido Materno</label>
                                <input id="usuario_apm" class="form-control @error('usuario_apm') is-invalid @enderror"
                                    name="usuario_apm" type="text" value="{{ old('usuario_apm', $user->usuario_apm) }}">
                                @error('usuario_apm')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_telefono" class="form-control-label">Teléfono</label>
                                <input id="usuario_telefono"
                                    class="form-control @error('usuario_telefono') is-invalid @enderror"
                                    name="usuario_telefono" type="text"
                                    value="{{ old('usuario_telefono', $user->usuario_telefono) }}">
                                @error('usuario_telefono')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_direccion" class="form-control-label">Dirección</label>
                                <input id="usuario_direccion"
                                    class="form-control @error('usuario_direccion') is-invalid @enderror"
                                    name="usuario_direccion" type="text"
                                    value="{{ old('usuario_direccion', $user->usuario_direccion) }}">
                                @error('usuario_direccion')
                                    <div class="invalid-feedback" style="display: block !important;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <hr class="horizontal dark">
            <p class="text-uppercase text-sm">Informacion Adicional</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Address</label>
                        <input class="form-control" type="text"
                            value="Bld Mihail Kogalniceanu, nr. 8 Bl 1, Sc 1, Ap 09">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">City</label>
                        <input class="form-control" type="text" value="New York">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Country</label>
                        <input class="form-control" type="text" value="United States">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Postal code</label>
                        <input class="form-control" type="text" value="437300">
                    </div>
                </div>
            </div>


            <hr class="horizontal dark">
            <p class="text-uppercase text-sm">About me</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">About me</label>
                        <input class="form-control" type="text"
                            value="A beautiful Dashboard for Bootstrap 5. It is Free and Open Source.">
                    </div>
                </div>
            </div>




            <hr class="horizontal dark">
            <p class="text-uppercase text-sm">Documentos cargados</p>
            <form action="{{ route('perfil.documentos.subir') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="archivo">Selecciona un documento:</label>
                    <input type="file" name="archivo" id="archivo" required
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.bmp,.webp" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary mt-2">Subir Documento</button>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <ul>

                        @forelse ($user->documentos as $documento)
                            @php
                                $ruta = asset($documento->ruta);
                                $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                                $id = 'preview-' . $documento->id;
                            @endphp

                            <li class="mb-3">
                                <a href="{{ $ruta }}" target="_blank" class="btn btn-primary btn-sm mb-1">Abrir en nueva
                                    pestaña</a>

                                <button type="button" class="btn btn-info btn-sm mb-1" onclick="togglePreview('{{ $id }}')">
                                    {{ in_array($ext, ['jpg', 'jpeg', 'png', 'bmp', 'webp', 'pdf']) ? 'Mostrar Previsualización' : 'Ver Detalles' }}
                                </button>

                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'bmp', 'webp']))
                                    <div id="{{ $id }}" style="display:none;">
                                        <img src="{{ $ruta }}" alt="Imagen"
                                            style="max-width: 400px; height: auto; border:1px solid #ccc; padding:5px;">
                                    </div>
                                @elseif ($ext === 'pdf')
                                    <div id="{{ $id }}" style="display:none;">
                                        <embed src="{{ $ruta }}" type="application/pdf" width="100%" height="500px"
                                            style="border:1px solid #ccc;" />
                                    </div>
                                @else
                                    <div id="{{ $id }}" style="display:none;">
                                        <p>Previsualización no disponible para este tipo de archivo.</p>
                                    </div>
                                @endif
                            </li>
                        @empty
                            <li>No tienes documentos subidos.</li>
                        @endforelse
                    </ul>


                </div>
            </div>
        </div>
    </div>
</div>



<script>
    function togglePreview(id) {
        const el = document.getElementById(id);
        if (!el) return;
        if (el.style.display === 'none') {
            el.style.display = 'block';
        } else {
            el.style.display = 'none';
        }
    }
</script>