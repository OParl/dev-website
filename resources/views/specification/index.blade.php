@extends ('base')

@section ('subheader')
    <h2 title="git: {{ $liveversion->getHash() }}, {{ $liveversion->getLastModified() }}">
        OParl Spezifikation v{{ $liveversion->getVersion() }}
    </h2>

    {{-- TODO: implement navigation as sticky submenu --}}
@stop

@section ('content')
    {!! $liveversion->getContent() !!}
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
