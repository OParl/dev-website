@extends('base')

@section('content')
    <div class="">

    </div>
    <h2>
        Ihre Anfrage wurde erfolgreich bearbeitet!
    </h2>

    <p>
        Sie werden in Kürze eine E-Mail an {{ old('email') }} erhalten, sobald der von Ihnen angeforderte
        Download bereit steht.
    </p>

    <p class="text-muted">
        (Diese Seite muss nicht geöffnet bleiben, damit der Download erzeugt wird.)
    </p>

    <ul>
        <li><a href="{{ route('specification.index') }}">Zur Liveversion</a></li>
        <li><a href="{{ route('downloads.index') }}">Zurück zur Downloadübersicht</a></li>
    </ul>
@stop