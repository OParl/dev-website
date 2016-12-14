<header role="navigation">
    <nav>
        <ul class="row">
            <li class="col-xs-12 col-sm-4">
                <img src="{{ asset('img/logos/oparl.svg') }}" alt="OParl Logo">
            </li>

            @foreach ($sections as $section)
                @if (isset($section['current']) && $section['current'])
                    <li class="col-xs-12 col-sm-2 active">
                @else
                    <li class="col-xs-12 col-sm-2">
                @endif

                        @if (isset($section['routeKey']) && !isset($section['params']))
                            <a href="{{ route($section['routeKey'] . '.index') }}">
                        @elseif (isset($section['params']))
                            <a href="{{ route($section['routeKey'], $section['params']) }}">
                        @else
                            <a href="{{ $section['url'] }}" target="_blank">
                        @endif
                                @lang($section['title'])
                            </a>

                    </li>
            @endforeach
        </ul>
    </nav>
    <nav v-fixed.top>
        <ul class="row center-xs">
            @yield('subheader')

            {{-- TODO: implement subheader stickyness --}}
        </ul>
    </nav>
</header>
