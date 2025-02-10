@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Buscar Grabación</h2>
        <form action="{{ route('questionRecordings') }}" method="GET">
            <input type="text" name="id" placeholder="ID de la llamada" required>
            <button type="submit">Buscar</button>
        </form>

        @if (session('message'))
            <p>{{ session('message') }}</p>
        @endif

        @if (!empty($archivoNombre))
            <p>Grabación encontrada: {{ $archivoNombre }}</p>

            <audio controls>
                <source src="{{ route('escuchar', ['archivo' => $archivoNombre]) }}" type="audio/wav">
                Tu navegador no soporta la reproducción de audio.
            </audio>

            <br>

            <a href="{{ route('descargar', ['archivo' => $archivoNombre]) }}" class="btn btn-primary">
                Descargar Grabación
            </a>
        @endif
    </div>
@endsection
