@php
    $isEdit = isset($asignacion);
@endphp

<div class="row">
    <!-- Columna Izquierda -->
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre"
                required value="{{ old('nombre', $asignacion->nombre ?? '') }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" rows="5"
                name="descripcion" required>{{ old('descripcion', $asignacion->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="descripcionCorta" class="form-label">Descripción Corta</label>
            <textarea class="form-control @error('descripcionCorta') is-invalid @enderror" id="descripcionCorta"
                rows="3" name="descripcionCorta"
                required>{{ old('descripcionCorta', $asignacion->descripcionCorta ?? '') }}</textarea>
            @error('descripcionCorta')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Características</label>
            <div id="caracteristicas">
                @php
                    $oldCaracteristicas = old('caracteristicas', $asignacion->caracteristicas ?? ['']);
                @endphp
                @foreach($oldCaracteristicas as $i => $car)
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('caracteristicas.' . $i) is-invalid @enderror"
                            name="caracteristicas[]" value="{{ is_object($car) ? $car->caracteristica : $car }}"
                            placeholder="Característica" required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="agregarCampo('caracteristicas')">+</button>
                        @error('caracteristicas.' . $i)
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Objetivos</label>
            <div id="objetivos">
                @php
                    $oldObjetivos = old('objetivos', $asignacion->objetivos ?? ['']);
                @endphp
                @foreach($oldObjetivos as $i => $obj)
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('objetivos.' . $i) is-invalid @enderror"
                            name="objetivos[]" value="{{ is_object($obj) ? $obj->objetivo : $obj }}" placeholder="Objetivo"
                            required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="agregarCampo('objetivos')">+</button>
                        @error('objetivos.' . $i)
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                @php
                    $oldBeneficios = old('beneficios', $asignacion->beneficios ?? ['']);
                @endphp
                @foreach($oldBeneficios as $i => $ben)
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('beneficios.' . $i) is-invalid @enderror"
                            name="beneficios[]" value="{{ is_object($ben) ? $ben->beneficio : $ben }}"
                            placeholder="Beneficio" required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="agregarCampo('beneficios')">+</button>
                        @error('beneficios.' . $i)
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de asignacion</label>
            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" onchange="mostrarCamposPago()"
                required>
                <option disabled {{ old('tipo', $asignacion->tipo ?? '') == '' ? 'selected' : '' }}>Seleccione tipo de
                    asignacion</option>
                <option value="gratuito" {{ old('tipo', $asignacion->tipo ?? '') == 'gratuito' ? 'selected' : '' }}>
                    Gratuito</option>
                <option value="pago" {{ old('tipo', $asignacion->tipo ?? '') == 'pago' ? 'selected' : '' }}>Pago</option>
            </select>
            @error('tipo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div id="camposPago" style="{{ old('tipo', $asignacion->tipo ?? '') == 'pago' ? '' : 'display: none;' }}">
            <div class="mb-3">
                <label for="costo" class="form-label">Costo del curso</label>
                <input type="text" name="costo" class="form-control @error('costo') is-invalid @enderror"
                    value="{{ old('costo', $asignacion->costo ?? '') }}">
                @error('costo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="formFile" class="form-label">Imagen</label>
            <input class="form-control @error('img1') is-invalid @enderror" type="file" id="formFile" name="img1"
                onchange="previewFile('#formFile', '#previewPhoto', '#photoHiddenInput1')">
            <div id="previewPhoto">
                @if($isEdit && $asignacion->img1)
                    <img src="{{ asset($asignacion->img1) }}" alt="Imagen actual" class="img-fluid mt-2">
                @endif
            </div>
            @error('img1')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input id="portadaInput" class="form-control @error('portada_imagen') is-invalid @enderror" type="file"
                accept=".jpg,.jpeg,.png" onchange="previewFile('portadaPreview', 'portadaHiddenInput')"
                name="portada_imagen">
            <label for="portadaInput">Portada</label>
            <div id="portadaPreview" class="mt-2">
                @if($isEdit && $asignacion->portada)
                    <img src="{{ asset($asignacion->portada) }}" alt="Portada actual" class="img-fluid">
                @endif
            </div>
            <input type="hidden" id="portadaHiddenInput" name="portada"
                value="{{ old('portada', $asignacion->portada ?? '') }}">
            @error('portada_imagen')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>