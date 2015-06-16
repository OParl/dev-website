<div class="page-header">
    <div class="row">
        <div class="col-md-10">
            <h1><span class="text-oparl">OParl</span> - Spezifikation</h1>
        </div>
        <div class="col-md-2">
            <div class="text-page-header text-right">
                @if (starts_with(Route::currentRouteName(), 'specification.'))
                    <a href="{{ route('downloads.index') }}" class="btn btn-primary">Downloads</a>
                @else
                    <a href="{{ route('specification.index') }}" class="btn btn-primary">Online lesen</a>
                @endif
            </div>
        </div>
    </div>
</div>