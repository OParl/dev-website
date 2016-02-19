<a data-toggle="modal" class="btn btn-{{ $buttonSize }} btn-success" aria-haspopup="true"
   data-target="#downloadFormatSelectModal" hreflang="de"
   aria-label="Formatauswahl für die aktuelle Spezifikationsversion öffnen">
    Aktuelle Version herunterladen!
</a>

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