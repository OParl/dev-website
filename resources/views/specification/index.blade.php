@extends ('base')

@section ('subheader')
    <div class="row--center">
        <h2 class="row-item--shrink-2" title="git: {{ $liveversion->getHash() }}, {{ $liveversion->getLastModified() }}">
            OParl 1.0
        </h2>

        <div class="row-item--shrink-2">
            <button
                    class="btn btn-xs btn-default pull-right"
                    data-toggle="modal"
                    data-target="#downloadFormatSelectModal"
            >
                <img src="{{ asset('/img/icons/download.svg') }}" alt="Downloadicon">
            </button>
        </div>
    </div>
@stop

@section ('content')
    <aside>
        {{-- TODO: make this more dynamic --}}
        <nav>
            {!! $liveversion->getNav() !!}
        </nav>
    </aside>

    <div>
        {!! $liveversion->getContent() !!}
    </div>

    @include('downloads.button_modal')
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
