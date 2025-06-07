@extends('layouts.argon')

@section('content')

    @role('profesor|admin')
    @include('tareas.calificar')
    @endrole

    @role('estudiante')
    @include('tareas.verTareas')
    @endrole

@endsection