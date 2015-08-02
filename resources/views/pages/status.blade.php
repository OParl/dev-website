@extends ('pages.page')

@section ('page')
    <p>
        Hier sammeln wir Informationen zum Stand der Standard-Entwicklung (Spezifikation).
        Zukünftig werden wir hier auch über den Stand der Implementierung und deren
        Verbreitung berichten.
    </p>

    <h2>Spezifikation („Der Standard“)</h2>

    <p>
        Zur Zeit wird an der ersten verabschiedbaren Version der Spezifikation gearbeitet,
        welche die Versionsnummer 1.0 tragen soll. Die aktuellste Version der Spezifikation
        kann jederzeit <a href="{{ route('specification.index') }}">auf dieser Seite</a>
        eingesehen werden. Wahlweise bieten wir auch
        <a href="{{ route('downloads.index') }}">Downloads</a> in verschiedenen Formaten an.
    </p>

    <h2>Validierungs-Client</h2>

    <p>
        Sobald die Spezifikation in Version 1.0 verabschiedet ist, beabsichtigen wir die
        Entwicklung einer Client-Software zum Testen von OParl API-Endpunkten. Dieser Client
        soll Software-Entwickler bei der Implementierung von OParl-konformen APIs unterstützen.
        Darüber hinaus soll mit diesem Client die Evaluation, ob ein System OParl-konform ist,
        ermöglicht werden, was einer offiziellen Zertifizierung nahe kommt.
    </p>

    <h2>Implementierung und Verbreitung</h2>

    <p>
        Sobald der Standard verabschiedet ist, möchten wir hier transparent machen,
        welche Systeme OParl-konforme APIs anbieten und wo Installationen dieser Systeme
        vorgenommen werden.
    </p>
@stop
