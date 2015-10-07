<html lang="de">
    <head>
        <title>OParl</title>

        <meta charset="utf-8" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <meta property="og:locale" content="de_DE">
        <meta property="og:type" content="website">
        <meta property="og:title" content="OParl - Initiative für Offenheit parlamentarischer Informationssysteme">
        <meta property="og:description" content="Initiative für Offenheit parlamentarischer Informationssysteme">
        <meta property="og:site_name" content="OParl">
        <meta property="og:url" content="http://oparl.org">

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
        <link href="{{ asset('/css/lib.css') }}" rel="stylesheet" />

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="icon" href="{{ asset('/img/favicon.png') }}" />
        <link rel="shortcut icon" href="{{ asset('/img/favicon.png') }}" />

        <!-- Relieve the user of having to actually reload to check if maintenance is done yet -->
        <meta http-equiv="refresh" content="15" />
    </head>
    <body>
        <div class="container-fluid" style="margin-top: 15%">
            <div class="row">
                <div class="col-sm-6 col-xs-12 col-sm-offset-3 text-center">
                    <h1 class="text-oparl">OParl.</h1>

                    <p>
                        Diese Webseite wird momentan aktualisiert. Bitte haben Sie einen
                        Augenblick Geduld bevor Sie Ihre
                        <a href="{{ \Request::fullUrl() }}">Anfrage wiederholen</a>.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>