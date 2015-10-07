<header class="page-header">
    <h1>
        <span class="text-oparl">OParl.</span>
        <small>Initiative für Offenheit parlamentarischer Informationssysteme</small>
    </h1>

    <div class="row">
        <div class="col-md-10">
            <nav>
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
                                    {!! $section['title'] !!}
                                </a>
                            </li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="col-md-2">
            {{--
            <form class="form-inline " method="POST" action="{{ route('search.lookup') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-search"></span></div>
                        <label for="query" class="sr-only">Suchbegriffe</label>
                        <input type="search" name="q" id="query" placeholder="Suche&hellip;"
                               class="form-control" />
                    </div>
                </div>
            </form>
            --}}
        </div>
    </div>

</header>
