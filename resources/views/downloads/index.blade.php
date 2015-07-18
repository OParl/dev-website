@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <div class="text-center">
                <a data-toggle="modal" class="btn btn-lg btn-success" data-target="#downloadFormatSelectModal">
                    Aktuelle Version herunterladen!
                </a>
            </div>
        </div>
        <div class="col-xs-12">
            <br />
            <ul class="list-unstyled text-center">
                <li><em>{{ $versions[0]->getCommitMessage()  }}</em></li>
                <li><small>(Erstellt {{ $versions[0]->getDate()->diffForHumans() }}.)</small></li>
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
            @include('downloads.complex_version_form')
        </div>
    </div>

    <div class="modal fade" id="downloadFormatSelectModal" tabindex="1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Bitte ein Downloadformat auswählen</h3>
                </div>
                <div class="modal-body">
                    @include ('downloads.simple_version_form', ['version' => $versions[0]])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>
        </div>
    </div>
@stop