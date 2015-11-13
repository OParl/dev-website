@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-9">
            @include ('news.list')
        </div>
        <aside class="col-md-3">
            @include ('common.contributors')
            @include ('news.archive')
        </aside>
    </div>
@stop
