<!DOCTYPE html>
<html lang="de">
    <head>
        <base href="{{ route('developers.index') }}">

        <meta charset="utf-8" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <meta property="og:locale" content="de_DE">
        <meta property="og:type" content="website">
        <meta property="og:title" content="OParl - Initiative für Offenheit parlamentarischer Informationssysteme">
        <meta property="og:description" content="Initiative für Offenheit parlamentarischer Informationssysteme">
        <meta property="og:site_name" content="OParl">
        <meta property="og:url" content="{{ route('developers.index') }}">

        <title>
            @if (isset($title))
                {{ $title.' - ' }}OParl.org
            @else
                @yield('title')
            @endif
        </title>

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />

        @yield('styles')

        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <![endif]-->

        <link rel="icon" href="{{ asset('/img/favicon.png') }}" />
        <link rel="shortcut icon" href="{{ asset('/img/favicon.png') }}" />
    </head>
    <body>
        <div id="app">
            <div class="sr-only">
                <ul>
                    <li><a href="#content" class="sr-only-focusable">Direkt zum Inhalt</a></li>
                    {{-- TODO: insert links to main chapters --}}
                </ul>
            </div>

            @include ('header')

            <main id="content">
                @yield('content')
            </main>

            @include ('footer')
        </div>

        @yield ('scripts')
        @include ('piwik')
    </body>
</html>
