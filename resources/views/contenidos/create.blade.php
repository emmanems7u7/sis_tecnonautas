@extends('layouts.argon')


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Subir Archivos') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('archivo.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="nombre">{{ __('nombre') }}</label>
                                <input type="text" id="nombre" name="nombre" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="documento">{{ __('Documento') }}</label>
                                <input type="file" id="documento" name="documento" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="video">{{ __('Video') }}</label>
                                <input type="file" id="video" name="video" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label for="enlace">{{ __('Enlace') }}</label>
                                <input type="text" id="enlace" name="enlace" class="form-control">
                            </div>

                            <input type="hidden" name="id_t" value="{{$id_t}}">
                            <button type="submit" class="btn btn-primary">{{ __('Subir Archivos') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection