@extends ('base')

@section ('scripts')
    <script type="text/javascript" src="{{ asset('/js/api.js') }}"></script>
@stop

@section('subheader')
@stop

@section ('content')
    @if ($isError)
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="alert alert-danger">
                    Whoops! Something went terribly wrong here!
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="">
            @include('transfugio::api.sidebar')
        </div>
        <div class="row-item--grow-2">
            @include('transfugio::api.main')
        </div>
    </div>

    @if (isset($paginationCode))
    <div class="row text-center">
        {!! $paginationCode or '' !!}
    </div>
    @endif
@stop
