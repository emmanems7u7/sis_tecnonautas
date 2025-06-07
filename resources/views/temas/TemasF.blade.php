@foreach($temas as $index => $tema)
<div class="card">
    <div class="card-header card-c" id="heading{{ $index }}">
        <h5 class="mb-0">
            <button class="text-white btn btn-link" data-toggle="collapse" data-target="#collapse{{ $index }}">
                {{ $tema['nombre'] }}
            </button>
        </h5>
    </div>
    <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}" data-parent="#accordionExample">
        <div class="card-body">
            <div class="card">dato</div>
        </div>
    </div>
</div>
@endforeach