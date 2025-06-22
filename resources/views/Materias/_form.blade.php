@php
    $isEdit = isset($asignacion);
@endphp

<div class="row">
    <!-- Columna Izquierda -->
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required
                value="{{ old('nombre', $asignacion->nombre ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" rows="5" name="descripcion"
                required>{{ old('descripcion', $asignacion->descripcion ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="descripcionCorta" class="form-label">Descripción Corta</label>
            <textarea class="form-control" id="descripcionCorta" rows="3" name="descripcionCorta"
                required>{{ old('descripcionCorta', $asignacion->descripcionCorta ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Características</label>
            <div id="caracteristicas">
                @foreach(old('caracteristicas', $asignacion->caracteristicas ?? ['']) as $car)
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="caracteristicas[]" value="{{ $car->caracteristica }}"
                            placeholder="Característica" required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="agregarCampo('caracteristicas')">+</button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Objetivos</label>
            <div id="objetivos">
                @foreach(old('objetivos', $asignacion->objetivos ?? ['']) as $obj)
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="objetivos[]" value="{{ $obj->objetivo }}"
                            placeholder="Objetivo" required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="agregarCampo('objetivos')">+</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Columna Derecha -->
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Beneficios</label>
            <div id="beneficios">
                @foreach(old('beneficios', $asignacion->beneficios ?? ['']) as $ben)
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="beneficios[]" value="{{ $ben->beneficio }}"
                            placeholder="Beneficio" required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="agregarCampo('beneficios')">+</button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de asignacion</label>
            <select name="tipo" class="form-select" onchange="mostrarCamposPago()" required>
                <option disabled {{ old('tipo', $asignacion->tipo ?? '') == '' ? 'selected' : '' }}>Seleccione tipo de
                    asignacion
                </option>
                <option value="gratuito" {{ old('tipo', $asignacion->tipo ?? '') == 'gratuito' ? 'selected' : '' }}>
                    Gratuito
                </option>
                <option value="pago" {{ old('tipo', $asignacion->tipo ?? '') == 'pago' ? 'selected' : '' }}>Pago</option>
            </select>
        </div>

        <div id="camposPago" style="{{ old('tipo', $asignacion->tipo ?? '') == 'pago' ? '' : 'display: none;' }}">
            <div class="mb-3">
                <label for="costo" class="form-label">Costo del curso</label>
                <input type="text" name="costo" class="form-control"
                    value="{{ old('costo', $asignacion->costo ?? '') }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="formFile" class="form-label">Imagen</label>
            <input class="form-control" type="file" id="formFile" name="img1"
                onchange="previewFile('#formFile', '#previewPhoto', '#photoHiddenInput1')">
            <div id="previewPhoto">
                @if($isEdit && $asignacion->img1)
                    <img src="{{ asset($asignacion->img1) }}" alt="Imagen actual" class="img-fluid mt-2">
                @endif
            </div>
        </div>

        <div class="form-floating mb-3">
            <input id="portadaInput" class="form-control" type="file" accept=".jpg,.jpeg,.png"
                onchange="previewFile('portadaPreview', 'portadaHiddenInput')" name="portada_imagen">
            <label for="portadaInput">Portada</label>
            <div id="portadaPreview" class="mt-2">
                @if($isEdit && $asignacion->portada)
                    <img src="{{ asset($asignacion->portada) }}" alt="Portada actual" class="img-fluid">
                @endif
            </div>
            <input type="hidden" id="portadaHiddenInput" name="portada"
                value="{{ old('portada', $asignacion->portada ?? '') }}">
        </div>
    </div>
</div>