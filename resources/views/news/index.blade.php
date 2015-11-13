@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-9">
            @include ('news.list')
        </div>
        <aside class="col-md-3">
            @include ('news.archive')
            @include ('common.contributors')
        </aside>
    </div>
@stop
