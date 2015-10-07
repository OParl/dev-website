@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <div class="text-center">
                <a data-toggle="modal" class="btn btn-lg btn-success" aria-haspopup="true"
                   data-target="#downloadFormatSelectModal" hreflang="de"
                   aria-label="Formatauswahl für die aktuelle Spezifikationsversion öffnen">
                    Aktuelle Version herunterladen!
                </a>
            </div>
        </div>
        <div class="col-xs-12">
            <br />
            <ul class="list-unstyled text-center">
                <li><em>{{ $builds->first()->commit_message  }}</em></li>
                <li><small>(Erstellt {{ $builds->first()->created_at->diffForHumans() }}.)</small></li>
            </ul>
        </div>

        <div class="col-xs-12">
            <hr />
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>Eine ältere Version anfordern:</h3>
        </div>

        <div class="col-xs-12">
            @include('downloads.complex_form')
        </div>
    </div>

    <div class="modal fade" id="downloadFormatSelectModal" tabindex="1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Bitte ein Downloadformat auswählen</h3>
                </div>
                <div class="modal-body">
                    @include ('downloads.simple_form', ['build' => $builds->first()])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>
        </div>
    </div>
@stop