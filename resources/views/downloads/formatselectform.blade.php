<div class="modal fade" id="downloadFormatSelectModal" tabindex="1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Bitte ein Downloadformat auswählen</h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <p>
                                Wir stellen die Downloads der Spezifikation in verschiedenen Formaten zur
                                Verfügung. Bitte wählen Sie unten das von Ihnen gewünschte aus.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <h3>Ausgabeformate</h3>
                            <ul>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'docx']) }}">Microsoft&reg; Word&reg;</a></li>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'epub']) }}">ePub</a></li>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'odt']) }}">OpenOffice.org Text</a></li>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'pdf']) }}">PDF&reg;</a></li>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'html']) }}">HTML (Standalone)</a></li>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'txt']) }}">Plain Text</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <h3>Archive</h3>
                            <p class="text-muted">Die Archive enthalten alle Ausgabeformate.</p>
                            <ul>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'zip']) }}">Zip</a></li>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'tar.gz']) }}">Gzip</a></li>
                                <li><a href="{{ route('downloads.provide', [$versions[0]->getHash(7), 'tar.bz2']) }}">Bzip2</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
            </div>
        </div>
    </div>
</div>