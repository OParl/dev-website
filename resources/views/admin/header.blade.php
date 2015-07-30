<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </button>
            <a class="navbar-brand" href="#"><span class="text-oparl">OParl.</span></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @foreach ($sections as $section)
                    @if ($section['current'])
                        <li class="active">
                    @else
                        <li>
                    @endif
                        <a href="{{ route($section['route']) }}">{!! $section['title'] !!}</a>
                    </li>
                @endforeach
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{ route('admin.news.create') }}">
                                <span class="glyphicon glyphicon-pencil text-primary"
                                      title="Neue Nachricht erstellen"></span>
                    </a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.settings') }}">Einstellungen</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('admin.logout') }}">Abmelden</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>