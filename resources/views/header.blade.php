<header>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header" id="navbar-header">
                <div class="pull-left text-oparl logo">
                    <a>OParl.</a>
                </div>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Navigation öffnen</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-left">
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
                                {!! $section['title'] !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</header>
