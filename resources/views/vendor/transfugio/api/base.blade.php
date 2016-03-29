@extends ('base')

@section ('content')
    @if ($isError)
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="alert alert-danger">Ooops, something went wrong here!</div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12 col-md-4">
            @include('transfugio::api.sidebar')
        </div>
        <div class="col-xs-12 col-md-8">
            @include('transfugio::api.main')
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <hr />
            <div class="text-center">
                {!! $paginationCode or '' !!}
            </div>
        </div>
    </div>
@stop
