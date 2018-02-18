@foreach ($sections as $section)
    @if (isset($section['current']) && $section['current'])
        <div class="level-item is-active">
    @else
        <div class="level-item">
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
        </div>
@endforeach