@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-9">
            @include ('news.list')
        </div>
        <div class="col-md-3">
            @include ('news.archive')
        </div>
    </div>
@stop
