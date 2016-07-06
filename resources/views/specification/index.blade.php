@extends ('base')

@section ('content')
    <div class="row sub-nav" id="specification-sub-nav">
        <div class="col-xs-11 col-md-2 col-md-offset-2">
            <div class="dropdown">
                <button type="button" class="btn btn-default dropdown-toggle"
                        data-toggle="dropdown" aria-hidden="true">
                    <img src="{{ asset('/img/icons/book-reference.svg') }}" alt="Menüicon">
                    <span class="caret"></span>
                </button>
                {!! $liveversion->getNav() !!}
            </div>
        </div>

        @if ($isLoggedIn)
            <div class="col-xs-1 col-md-1 col-md-offset-2">
                <button class="btn btn-default pull-right">
                   <img src="{{ asset('/img/icons/download.svg') }}" alt="Downloadicon">
                </button>
            </div>

            <div class="col-xs-12 col-md-3">
                <f-select label="Wähle eine andere Version" data="versions"></f-select>
            </div>
        @else
            <div class="col-xs-1 col-md-1 col-md-offset-5">
                <button class="btn btn-default pull-right">
                    <img src="{{ asset('/img/icons/download.svg') }}" alt="Downloadicon">
                </button>
            </div>
        @endif
    </div>

    <div class="row sr-only sr-only-focusable">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            {!! $liveversion->getNav() !!}
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            {!! $liveversion->getContent() !!}
        </div>
    </div>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
