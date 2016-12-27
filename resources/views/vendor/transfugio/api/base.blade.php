@extends ('base')

@section ('scripts')
    <script type="text/javascript" src="{{ asset('/js/api.js') }}"></script>
@stop

@section('subheader')
@stop

@section ('content')
    @if (isset($isError) && $isError)
        <div class="row">
            <div class="alert alert-danger">
                Whoops! Something went terribly wrong here!
            </div>
        </div>
    @endif

    <div>
        @include('transfugio::api.sidebar')
    </div>
    <div>
        @include('transfugio::api.main')
    </div>

    @if (isset($paginationCode))
    <div class="row text-center">
        {!! $paginationCode or '' !!}
    </div>
    @endif
@stop
