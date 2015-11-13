@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-9">
            @yield ('page')
        </div>
        <aside class="col-md-3">
            @include ('common.contributors')
        </aside>
    </div>
@stop
