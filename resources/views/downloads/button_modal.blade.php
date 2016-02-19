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