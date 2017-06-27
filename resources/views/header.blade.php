<header role="navigation">
    <nav class="row">
        <span class="col-xs-12 col-sm-4">
            <img src="{{ asset('img/logos/oparl.svg') }}" alt="OParl Logo" height="48" longdesc="'OParl.' in hellblauer Schrift auf weißem Grund.">
        </span>
        <div class="col-xs-12 col-sm-8">
            <ul class="row">
                @foreach ($sections as $section)
                    <li class="col-xs-12 col-sm-6 col-md-3 @if (isset($section['current']) && $section['current']) active @endif">
                        <a href="{{ $section['href'] }}" @if (isset($section['url'])) target="_blank" @endif>
                            @lang($section['title'])
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
    <nav>
        <ul class="row center-xs">
            @yield('subheader')

            {{-- TODO: implement subheader stickyness --}}
        </ul>
    </nav>
</header>
