<div class="page">
    <header>
        <img src="@dumpasset('img/favicon.png')" width="32" height="32">
        <span>OParl.org – Validator</span>
    </header>

    <h1>
        OParl {{ $result['oparl_version'] }} Validierung
    </h1>

    <p>
        Resultat der OParl {{ $result['oparl_version'] }} Validierung für <a href="{{ $endpoint }}">
            {{ $endpoint }}</a> vom {{ $validationDate }}.
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
            @if (isset($result['network']))
                @if ($result['network']['ssl'])
                    <li class="result--okay">Gültiges SSL-Zertifikat</li>
                @else
                    <li class="result--error">Kein gültiges SSL-Zertifikat</li>
                @endif
                @if ($result['network']['average_ttl'] < 500)
                    <li class="result--okay">Durchschnittliche Antwortzeit des Servers &lt; 500ms</li>
                @endif
            @endif
        </ul>

        <p>
            Auf den folgenden Seiten finden Sie detaillierte Auswertungen der Analyse Ihres Endpunktes
            aufgeschlüsselt nach OParl-Objekten. Falls zu einzelnen Punkten Fragen aufkommen, wenden
            Sie sich bitte mit diesem Dokument im Anhang per E-Mail an <a
                    href="mailto:info@oparl.org?subject=Nachfrage+zur+OParl+Validierung+von+{{ $urlEncodedEndpoint }}">info@oparl.org</a>.
        </p>
    </div>
</div>

@foreach ($result['object_messages'] as $objectName => $data)
    <div @if (!$loop->last)class="pagebreak"@endif>
        <header>
            <img src="@dumpasset('img/favicon.png')" width="32" height="32">
            <span>OParl.org – Validator</span>
        </header>

        <h2>oparl:{{ strtolower($objectName) }}</h2>
        <h3>Aufgetretene Meldungen</h3>

        <table id="result">
            <thead>
            <tr>
                <th></th>
                <th>Schwere</th>
                <th>Meldung</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $message)
                <tr>
                    <td><span class="result-id result-id__{{ $message['severity'] }}">{{ $loop->index + 1 }}</span></td>
                    <td class="result--{{ $message['severity'] }}">{{ $message['severity'] }}</td>
                    <td>{{ $message['message'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h3>Betroffene Objekte</h3>

        <ul class="linklist">
            @foreach ($data as $message)
                @foreach ($message['objects'] as $messageObject)
                    <li>
                        <span class="result-id result-id__{{ $message['severity'] }}">{{ $loop->parent->index + 1 }}</span>
                        <a href="{{ $messageObject }}">{{ $messageObject }}</a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endforeach