<!DOCTYPE html>
<html lang="de">
    <head>
        <title>OParl.org Administration</title>

        <meta charset="utf-8" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
        <link href="{{ asset('/css/lib.css') }}" rel="stylesheet" />

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="icon" href="{{ asset('/img/favicon.png') }}" />
        <link rel="shortcut icon" href="{{ asset('/img/favicon.png') }}" />
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="glyphicon glyphicon-menu-hamburger"></span>
                    </button>
                    <a class="navbar-brand" href="#"><span class="text-oparl">OParl.</span></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ route('admin.dashboard') }}">Übersicht</a></li>
                        <li><a href="{{ route('admin.news.index') }}">Nachrichten</a></li>
                        <li><a href="{{ route('admin.comments.index') }}">Kommentare</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="{{ route('admin.news.create') }}">
                                <span class="glyphicon glyphicon-pencil text-primary"
                                      title="Neue Nachricht erstellen"></span>
                            </a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('admin.settings') }}">Einstellungen</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('admin.logout') }}">Abmelden</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                @yield ('content')
            </div>
        </div>

        <script src="{{ asset('/js/lib.js') }}"></script>
        <script src="{{ asset('/js/app.js') }}"></script>
    </body>
</html>
