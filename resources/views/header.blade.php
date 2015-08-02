<div class="page-header">
    <h1>
        <span class="text-oparl">OParl</span>
        <small>Initiative für Offenheit parlamentarischer Informationssysteme</small>
    </h1>

    <nav class="text-center">
        <a name="navigation">&nbsp;</a>
        <ul class="nav nav-pills">
            @foreach ($sections as $section)
                @if (isset($section['current']) && $section['current'])
                    <li role="presentation" class="active">
                @else
                    <li role="presentation">
                @endif
                        @if (isset($section['routeKey']))
                            <a href="{{ route($section['routeKey'] . '.index') }}">
                        @else
                            <a href="{{ $section['url'] }}" target="_blank">
                        @endif
                            {{ $section['title'] }}
                        </a>
                    </li>
            @endforeach
        </ul>
    </nav>
</div>
