@extends ('base')

@section ('content')
    <div>
        <header class="row sub-nav fixed-top bg-secondary" id="specification-sub-nav">
            @if (!$isLoggedIn)
                <div class="hidden-xs col-md-8 col-md-offset-2 text-center">
                    <h2 title="git: {{ $liveversion->getHash() }}, {{ $liveversion->getLastModified() }}">
                        OParl 1.0
                    </h2>
                </div>

                <div class="col-xs-1 col-md-1">
                    <button
                            class="btn btn-xs btn-default pull-right"
                            data-toggle="modal"
                            data-target="#downloadFormatSelectModal"
                    >
                        <img src="{{ asset('/img/icons/download.svg') }}" alt="Downloadicon">
                    </button>
                </div>
            @else
                {{-- this is a signed in user --}}

                <div class="col-md-2">
                    <h2 title="git: {{ $liveversion->getHash() }}, {{ $liveversion->getLastModified() }}">
                        OParl 1.0
                    </h2>
                </div>

                <div class="col-xs-1 col-md-1">
                    <button class="btn btn-default pull-right">
                        <img src="{{ asset('/img/icons/download.svg') }}" alt="Downloadicon">
                    </button>
                </div>

                <div class="col-xs-12 col-md-3">
                    <f-select label="Wähle eine andere Version" data="versions"></f-select>
                </div>
            @endif
        </header>

        <div class="row">
            {{-- TODO: make this more dynamic --}}
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                <nav>
                    {!! $liveversion->getNav() !!}
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                {!! $liveversion->getContent() !!}
            </div>
        </div>
    </div>

    @include('downloads.button_modal')
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
