<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style type="text/css">
        @dumpasset('css/pdf.css')
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
            Result der OParl {{ $oparlVersion }} Validierung für <a href="{{ $endpoint }}">{{ $endpoint }}</a> vom {{ $validationDate }}.
        </p>

        <div class="infobox">
            <h2>Zusammenfassung</h2>

            <ul>
                <li>52.500 referenzierte Objekte
                    <ul>
                        <li class="result--okay">davon wurden 51.300 gefunden</li>
                        <li class="result--warning">25.000 haben keine Validierungsfehler</li>
                        <li class="result--error">26.300 Objekte sind unvollständig verlinkt</li>
                    </ul>
                </li>
                <li class="result--okay">Gültiges SSL-Zertifikat</li>
                <li class="result--okay">Durchschnittliche Antwortzeit des Servers < 500ms</li>
            </ul>
        </div>
    </div>

    <div class="pagebreak"></div>

    <div class="page">
        <header>
            <img src="@dumpasset('img/favicon.png')" width="32" height="32">
            <span>OParl.org – Validator</span>
        </header>

        TODO: INSERT COMPLETE VALIDATION RESULT HERE
    </div>
</body>
</html>