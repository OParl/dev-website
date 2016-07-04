@extends ('base')

@section ('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
@stop

@section ('content')
    <div class="row" id="spec">
        <div class="col-xs-12 col-md-4">
            <f-drop-list label="Inhaltsverzeichnis" route="{{ url('/spezifikation/toc.json') }}">
            </f-drop-list>
        </div>
        <div class="col-xs-12 col-md-2 col-md-offset-4">
            <button>Download</button>
        </div>
        <div class="col-xs-12 col-md-2">
            <f-select label="Wähle eine andere Version" data="versions"></f-select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            {!! $liveversion->getContent() !!}
        </div>
    </div>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
