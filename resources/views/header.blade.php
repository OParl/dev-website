<header role="navigation">
    <nav>
        <ul class="row">
            <li>
                <img src="{{ asset('img/logos/oparl.svg') }}" alt="OParl Logo">
            </li>

            @foreach ($sections as $section)
                @if (isset($section['current']) && $section['current'])
                    <li class="active">
                @else
                    <li>
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
    <nav>
        <ul class="row row--center">
            @yield('subheader')
        </ul>
    </nav>
</header>
