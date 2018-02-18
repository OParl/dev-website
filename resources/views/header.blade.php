<header role="navigation">
    <nav class="navbar is-transparent">
        <div class="navbar-brand">
            <a href="{{ url('/') }}" class="navbar-item">
                <img src="{{ asset('img/logos/oparl.svg') }}" alt="OParl Logo" height="48"
                     longdesc="'OParl.' in hellblauer Schrift auf weißem Grund.">
            </a>

            <div class="navbar-burger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="navbar-menu">
            <div class="navbar-end">
                @foreach ($sections as $section)
                    <div class="navbar-item">
                        <a href="{{ $section['href'] }}" @if (isset($section['url'])) target="_blank" @endif>
                            {{--<i class="fa {{ $section['icon'] }}" aria-hidden="true"></i>--}}
                            <span>@lang($section['title'])</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </nav>
    <nav class="navbar">
        <div class="navbar-menu">
            <div class="navbar-start">
                @yield('subheader')
            </div>
            <div class="navbar-end">
                @yield('subheader_actions')
            </div>
        </div>
    </nav>
</header>
