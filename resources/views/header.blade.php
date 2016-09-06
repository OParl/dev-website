<header>
    <nav role="navigation">
        <ul class="row">
            <li class="text-oparl">
                <img src="{{ asset('img/logos/oparl.svg') }}" alt="OParl Logo">
            </li>

            @foreach ($sections as $section)
                @if (isset($section['current']) && $section['current'])
                    <li role="presentation" class="active">
                @else
                    <li role="presentation">
                @endif

                        @if (isset($section['routeKey']) && !isset($section['params']))
                            <a href="{{ route($section['routeKey'] . '.index') }}">
                        @elseif (isset($section['params']))
                            <a href="{{ route($section['routeKey'], $section['params']) }}">
                        @else
                            <a href="{{ $section['url'] }}" target="_blank">
                        @endif
                                {!! $section['title'] !!}
                            </a>

                    </li>
            @endforeach
        </ul>
    </nav>
    <nav>
        @yield('subheader')
    </nav>
</header>
