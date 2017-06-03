<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style type="text/css">
        @if ($app->environment('production'))
            @dumpasset('css/pdf.min.css')
        @else
            @dumpasset('css/pdf.css')
        @endif
    </style>
</head>
<body>
    <div class="page">
        <header>
            <img src="@dumpasset('img/favicon.png')" width="32" height="32">
            <span>OParl.org – Validator</span>
        </header>

        <h1>
            OParl {{ $oparlVersion }} Validierung
        </h1>

        <p>
            Resultat der OParl {{ $oparlVersion }} Validierung für <a href="{{ $endpoint }}">{{ $endpoint }}</a> vom {{ $validationDate }}.
        </p>

        <div class="pagebreak">
            <h2>Zusammenfassung</h2>

            <ul>
                <li>{{ $result['counts']['total'] }} referenzierte Objekte
                    <ul>
                        <li class="result--okay">davon sind {{ $result['counts']['valid'] }} valide</li>

                        @if ($result['counts']['failed'] > 0)
                            <li class="result--warning">{{ $result['counts']['failed'] }} enthalten Fehler</li>
                        @endif

                        @if ($result['counts']['fatal'] > 0)
                            <li class="result--error">{{ $result['counts']['fatal'] }} widersprechen der Spezifikation</li>
                        @endif
                    </ul>
                </li>
                @if ($result['network']['ssl'])
                    <li class="result--okay">Gültiges SSL-Zertifikat</li>
                @else
                    <li class="result--error">Kein gültiges SSL-Zertifikat</li>
                @endif
                @if ($result['network']['average_ttl'] < 500)
                    <li class="result--okay">Durchschnittliche Antwortzeit des Servers &lt; 500ms</li>
                @endif
            </ul>
        </div>
        <p>
            Auf den folgenden Seiten finden Sie detaillierte Auswertungen der Analyse Ihres Endpunktes
            aufgeschlüsselt nach OParl-Objekten. Falls zu einzelnen Punkten Fragen aufkommen, wenden
            Sie sich bitte mit diesem Dokument im Anhang per E-Mail an info@oparl.org.
        </p>
    </div>

    @foreach ($result['object_messages'] as $objectName => $data)
        @if (!$loop->last)
            <div class="pagebreak">
        @else
            <div>
        @endif
            <header>
                <img src="@dumpasset('img/favicon.png')" width="32" height="32">
                <span>OParl.org – Validator</span>
            </header>

            <h2>oparl:{{ strtolower($objectName) }}</h2>

            <h3>Aufgetretene Meldungen</h3>

            @foreach ($data as $message)
                <strong>{{ $message['severity'] }}: {{ $message['message'] }}</strong>
                {{--<p>TODO: long description</p>--}}
                <h4>Aufgetretenen in folgenden Objekten:</h4>
                <ul>
                    @foreach($message['objects'] as $errorObject)
                        <li>{{ $errorObject }}</li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    @endforeach
</body>
</html>