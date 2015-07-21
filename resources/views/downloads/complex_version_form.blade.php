@if ($errors->has('email'))
    <div class="alert alert-danger" role="alert">
        Bitte überprüfen Sie die eingegebene E-Mail-Adresse auf Korrektheit.
    </div>
@endif

<form class="form-horizontal" id="download-selector" method="post" action="{{ route('downloads.select') }}">
    {{ csrf_field() }}

    <input type="hidden" name="available" class="available" value="0" />

    <div class="form-group">
        <label for="version" class="control-label col-sm-4">
            Gewünschte Version:
        </label>
        <div class="col-sm-8">
            <select class="form-control" name="version" aria-describedby="version-help">
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
    <div class="form-group @if ($errors->has('email')) has-error @endif" style="display: none;">
        <label for="email" class="control-label col-sm-4">
            Ihre E-Mail-Adresse:
        </label>
        <div class="col-sm-8">
            <input type="email" class="form-control" value="{{ old('email') }}"
                   name="email" placeholder="email@example.com" aria-describedby="email-help" />

            <div id="email-help" class="help-block">
                Die von Ihnen angeforderte Spezifikationsversion liegt zur Zeit nicht aufbereitet vor.
                Die Erstellung benötigt unter Umständen einen kleinen Moment. Wir bitten Sie daher
                um Ihre E-Mail-Adresse, um Ihnen den Downloadlink direkt zusenden zu können, sobald
                der Erstellungsprozess abgeschlossen ist.

                Sobald wir ihnen die Informationsmail gesendet haben, löschen wir Ihre E-Mail-Adresse
                wieder aus unserer Datenbank.
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
                    <div class="radio"><label for="tar.gz"><input type="radio" name="format" value="gz" />Gzip</label></div>
                    <div class="radio"><label for="tar.bz2"><input type="radio" name="format" value="bz2" />Bzip2</label></div>
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