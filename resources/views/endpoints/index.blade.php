@extends('base')

@section('content')
    <h1>Bekannte OParl Endpunkte</h1>

    <list></list>

    <p id="bodyCountNotice">
        <em>
            Die Anzahl der Körperschaften entspricht nicht zwingend der Anzahl an Kommunen
            in Deutschland die bereits OParl unterstützen. Dies hängt zum Einen damit
            zusammen, dass OParl-Daten von Projekten bereitgestellt werden, welche gar nicht
            auf kommunaler Datenbasis aufsetzen (z.B.: kleineAnfragen), zum Anderen können
            auf Grund der Natur von OParl-Systemen Körperschaften in mehr als
            einem dieser vorkommen.
        </em>
    </p>
@stop

@section ('bundle')
    <script src="{{ mix('js/endpoints.js') }}"></script>
@overwrite
