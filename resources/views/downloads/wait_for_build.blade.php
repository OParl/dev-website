@extends('base')

@section('content')
    <div class="col-md-12 loading">
        <h2>
            Ihre Anfrage wird momentan bearbeitet!
        </h2>

        <p>
            Bitte gedulden Sie sich einen kleinen Moment, während wir Ihren
            Download vorbereiten.
        </p>

        <div class="text-center">
            <br />
            <br />
            <div class="spinner">
                <div>Laden&hellip;</div>
            </div>
            <br />
            <br />
        </div>

        <ul class="list-unstyled">
            <li class="pull-left"><a href="{{ route('specification.index') }}">Zur Liveversion</a></li>
            <li class="pull-right"><a href="{{ route('downloads.index') }}">Zurück zur Downloadübersicht</a></li>
        </ul>

        <div class="clearfix">&nbsp;</div>
    </div>

    <div class="col-md-12 hidden done">
        <h2>Fertig!</h2>

        <p>
            Ihr Download steht jetzt bereit und sollte in den nächsten Sekunden
            automatisch starten. Falls nicht, wählen Sie bitte unten erneut das
            von Ihnen gewünschte Format aus.
        </p>

        @include ('downloads.format_list')
    </div>
@stop

@section ('scripts')
    <script src="asset('js/pusher.js')" type="text/javascript"></script>
    <script type="text/javascript">
        var __requested_format = '{{ session('requested_format'); }}';
        var __pusher_config = {
            key: "{{ env('PUSHER_KEY') }}",
            channel: "build_requests"
        };

    </script>
@stop
