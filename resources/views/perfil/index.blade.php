@extends('layouts.argon')

@section('content')

    <div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div
                class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePhotoModalLabel">Cambiar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('perfil.updateFoto') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="fotoperfil" class="form-label">Selecciona una nueva foto</label>
                            <input type="file" class="form-control" id="fotoperfil" name="fotoperfil" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Foto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('perfil.update') }}">
        @csrf
        @method('PUT')

        <div class="card mt-3">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <!-- Foto de perfil (circulada) -->
                            <div class="" style="    position: relative;">
                                <img src="{{ auth()->user()->fotoperfil ? asset('storage/' . auth()->user()->fotoperfil) : 'default-avatar.jpg' }}"
                                    alt="Foto de perfil" class="rounded-circle" width="170" height="170"
                                    id="fotoperfil-img">

                                <!-- Botón para cambiar la foto -->
                                <button type="button"
                                    class="btn btn-dark position-absolute bottom-0 start-50 translate-middle-x"
                                    id="change-photo-btn" data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                                    <i class="fas fa-camera "></i>
                                </button>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <!-- Campos de nombre y apellidos en una sola fila -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="apepat" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apepat" name="apepat"
                                    value="{{ old('apepat', auth()->user()->apepat) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="apemat" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apemat" name="apemat"
                                    value="{{ old('apemat', auth()->user()->apemat) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">

                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}" required>

                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fechanac" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fechanac" name="fechanac"
                                    value="{{ old('fechanac', auth()->user()->fechanac) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ci" class="form-label">Cédula de Identidad</label>
                                <input type="number" class="form-control" id="ci" name="ci"
                                    value="{{ old('ci', auth()->user()->ci) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                value="{{ old('direccion', auth()->user()->direccion) }}" required>
                        </div>
                    </div>

                </div>










                <!-- Botón de guardar -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Actualizar datos</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Card para cambiar la contraseña -->
    <div class="card mb-3 mt-3">
        <div class="card-header">
            <h5 class="card-title">Cambiar Contraseña</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-danger shadow-sm" role="alert">
                <h4 class="alert-heading">
                    <i class="fas fa-lock"></i> Seguridad de tu Contraseña
                </h4>
                <p>
                    <i class="fas fa-key"></i> <strong>Recuerda:</strong> tu contraseña inicial está compuesta por las
                    <strong>tres primeras letras de tu nombre</strong> seguidas de tu <strong>número de CI</strong>.
                    <br>Por ejemplo, si tu nombre es <em>Diego</em> y tu CI es <em>123456789</em>, entonces tu contraseña
                    por defecto será: <code>Die123456789</code>.
                </p>
                <p>
                    <i class="fas fa-exclamation-triangle text-warning"></i> <strong>Importante:</strong> Si es tu primer
                    ingreso o aún no cambiaste tu contraseña, te recomendamos actualizarla para mantener tu cuenta segura.
                </p>
                <p>
                    <i class="fas fa-envelope-open-text"></i> Si olvidas tu contraseña, puedes solicitar el envío de un
                    enlace para restablecerla desde la pantalla principal del sistema.
                </p>
            </div>

            <form action="{{ route('perfil.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Contraseña Actual -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">Contraseña Actual</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>

                <!-- Nueva Contraseña y Confirmación en una fila -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password_confirmation"
                            name="new_password_confirmation" required>
                    </div>
                </div>

                <!-- Botón de Guardar -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                </div>
            </form>
        </div>

    </div>
@endsection