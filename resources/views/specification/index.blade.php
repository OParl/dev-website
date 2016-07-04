@extends ('base')

@section ('content')
    <div class="row" id="spec">
        <div class="col-xs-11 col-md-2 col-md-offset-2">
            <div class="dropdown">
                <button type="button" class="btn btn-default dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <span class="glyphicon glyphicon-book"></span>
                    <span class="caret"></span>
                </button>
                {!! $liveversion->getNav() !!}
            </div>
        </div>

        <div class="col-xs-1 col-md-1 col-md-offset-2">
            <button class="btn btn-default pull-right"><span class="glyphicon glyphicon-download-alt"></span></button>
        </div>
        <div class="col-xs-12 col-md-3">
            <f-select label="Wähle eine andere Version" data="versions"></f-select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            {!! $liveversion->getContent() !!}
        </div>
    </div>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
