<!DOCTYPE html>
<html lang="de">
    <head>
        <base href="{{ \Request::url() }}">

        <meta charset="utf-8" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes" />

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

        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />

        @yield('styles')

        <link rel="icon" href="{{ asset('/images/favicon.png') }}" />
        <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" />
    </head>
    <body>
        <div id="app" class="container" data-messages="{{ json_encode(Session::get('message')) }}">
            <div class="sr-only">
                <ul>
                    <li><a href="#content" class="sr-only-focusable">Direkt zum Inhalt</a></li>
                    {{-- TODO: insert links to main chapters --}}
                    @stack('sr-links')
                </ul>
            </div>

            @include ('header')

            <main id="content" class="content">
                <div class="container u-pt-0_5">
                    @yield('content')
                </div>
            </main>

            @include ('footer')
        </div>

        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
        @include ('piwik')
    </body>
</html>
