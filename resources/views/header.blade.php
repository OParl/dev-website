<div class="page-header">
    <div class="row">
        <div class="col-md-4">
            <h1><span class="text-oparl">OParl</span> - Spezifikation</h1>
        </div>
        <div class="col-md-8">
            <div class="text-page-header text-right">
                <ul class="list-unstyled list-inline">
                    <li>
                        <a href="http://demoserver.oparl.org/" class="btn btn-default">Demoserver</a>
                    </li>

                    <li>
                        @if (starts_with(Route::currentRouteName(), 'specification.'))
                            <a href="{{ route('downloads.index') }}" class="btn btn-primary">Downloads</a>
                        @else
                            <a href="{{ route('specification.index') }}" class="btn btn-primary">Online lesen</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>