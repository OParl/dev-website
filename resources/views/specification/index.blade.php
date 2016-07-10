@extends ('base')

@section ('content')
    <div class="row sub-nav fixed-top" id="specification-sub-nav">
        <div class="col-xs-11 col-md-2 col-md-offset-2">
            <f-select from="{{ route('specification.toc') }}" label="toc"></f-select>
        </div>

        @if (!$isLoggedIn)
            <div class="hidden-xs col-md-4 text-center">
                <h2 title="git: {{ $liveversion->getHash() }}, {{ $liveversion->getLastModified() }}">
                    OParl 1.0
                </h2>
            </div>

            <div class="col-xs-1 col-md-1 col-md-offset-1">
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

    @include('downloads.button_modal')
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
