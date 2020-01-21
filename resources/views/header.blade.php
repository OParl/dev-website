<header role="navigation">
    <b-navbar class="navbar is-transparent">
        <template slot="brand">
            <div class="navbar-brand">
                <a href="{{ url('/') }}" class="navbar-item">
                    <img src="{{ asset('images/logos/oparl.svg') }}" alt="OParl Logo" height="48"
                         longdesc="'OParl.' in hellblauer Schrift auf weißem Grund.">
                </a>
            </div>
        </template>

        <template slot="end">
            @foreach ($sections as $section)
                <div class="navbar-item">
                    <a href="{{ $section['href'] }}" @if (isset($section['url'])) target="_blank" @endif>
                        {{--<i class="fa {{ $section['icon'] }}" aria-hidden="true"></i>--}}
                        <span>@lang($section['title'])</span>
                    </a>
                </div>
            @endforeach
        </template>
    </b-navbar>
    @yield('header_extra')
</header>
