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
            <form class="form-horizontal" id="download-selector" method="post" action="{{ route('downloads.select') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="version" class="control-label col-sm-4">
                        Gewünschte Version:
                    </label>
                    <div class="col-sm-8">
                        <select class="form-control" name="version" aria-describedby="version-help">
                            @include ('downloads.version', ['version' => $versions[0], 'selected' => true])

                            @for ($i = 1; isset($versions[$i]); $i++)
                                @include('downloads.version', ['version' => $versions[$i]])
                            @endfor
                        </select>
                        <div id="version-help" class="help-block">
                            Falls die von Ihnen gewünschte Version nicht mehr in der obigen Auswahl sein sollte,
                            kontaktieren Sie uns bitte per E-Mail unter
                            <a href="mailto:info@oparl.org">info@oparl.org</a>.
                        </div>
                    </div>
                </div>
                <div class="form-group" style="display: none;">
                    <label for="email" class="control-label col-sm-4">
                        Ihre E-Mail-Adresse:
                    </label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="email" placeholder="email@example.com" aria-describedby="email-help" />
                        <div id="email-help" class="help-block">
                            Die von Ihnen angeforderte Spezifikationsversion liegt zur Zeit nicht aufbereitet vor.
                            Die Erstellung benötigt unter Umständen einen kleinen Moment. Wir bitten Sie daher
                            um Ihre E-Mail-Adresse, um Ihnen den Downloadlink direkt zusenden zu können, sobald
                            der Erstellungsprozess abgeschlossen ist.
                        </div>
                    </div>
                </div>
                <div class=form-group">
                    <label for="format" class="control-label col-sm-4">
                        Gewünschtes Format:
                    </label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="radio"><label for="docx"><input type="radio" name="format" value="docx" />Microsoft&reg; Word&reg;</label></div>
                                <div class="radio"><label for="epub"><input type="radio" name="format" value="epub" />ePub</label></div>
                                <div class="radio"><label for="odt"><input type="radio" name="format" value="odt" />OpenOffice.org Text</label></div>
                                <div class="radio"><label for="pdf"><input type="radio" name="format" value="pdf" />PDF&reg;</label></div>
                                <div class="radio"><label for="html"><input type="radio" name="format" value="html" />HTML (Standalone)</label></div>
                                <div class="radio"><label for="txt"><input type="radio" name="format" value="txt" />Plain Text</label></div>
                            </div>
                            <div class="col-sm-6">
                                <h4>Archive</h4>
                                <p class="text-muted">
                                    Die Archive enthalten alle Ausgabeformate.
                                </p>

                                <div class="radio"><label for="zip"><input type="radio" name="format" value="zip" checked="checked" />Zip</label></div>
                                <div class="radio"><label for="gz"><input type="radio" name="format" value="gz" />Gzip</label></div>
                                <div class="radio"><label for="bz2"><input type="radio" name="format" value="bz2" />Bzip2</label></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-10 text-right">
                        <input type="submit" value="Laden" class="btn btn-primary" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="downloadFormatSelectModal" tabindex="1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Bitte ein Downloadformat auswählen</h3>
                </div>
                <div class="modal-body">
                    @include ('downloads.formatselectform', ['version' => $versions[0]])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>
        </div>
    </div>
@stop