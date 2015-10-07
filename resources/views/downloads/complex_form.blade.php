<form class="form-horizontal" id="download-selector" method="post" action="{{ route('downloads.select') }}">
    {{ csrf_field() }}

    <input type="hidden" name="available" class="available" value="{{ $builds->first()->is_available }}" />

    <div class="form-group">
        <label for="version" class="control-label col-sm-4">
            Gewünschte Version:
        </label>
        <div class="col-sm-8">
            <select class="form-control" name="version" aria-describedby="version-help">
                @foreach ($builds->slice(1) as $build)
                    <option value="{{ $build->hash }}" {{ (isset($selected) && $selected) ? 'selected' : '' }}>

                        {{ $build->human_version }} ({{ $build->short_hash }}
                        vom {{ $build->created_at->formatLocalized('%d.%m.%Y') }})

                    </option>
                @endforeach
            </select>
            <div id="version-help" class="help-block">
                Falls die von Ihnen gewünschte Version nicht in der obigen Auswahl sein sollte,
                kontaktieren Sie uns bitte per E-Mail unter
                <a href="mailto:info@oparl.org">info@oparl.org</a>.
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
                    <div class="radio"><label><input type="radio" name="format" value="docx" />Microsoft Word</label></div>
                    <div class="radio"><label><input type="radio" name="format" value="epub" />ePub</label></div>
                    <div class="radio"><label><input type="radio" name="format" value="odt" />OpenOffice.org Text</label></div>
                    <div class="radio"><label><input type="radio" name="format" value="pdf" />PDF</label></div>
                    <div class="radio"><label><input type="radio" name="format" value="html" />HTML (Standalone)</label></div>
                    <div class="radio"><label><input type="radio" name="format" value="txt" />Plain Text</label></div>
                </div>
                <div class="col-sm-6">
                    <h4>Archive</h4>
                    <p class="text-muted">
                        Die Archive enthalten alle Ausgabeformate.
                    </p>

                    <div class="radio"><label><input type="radio" name="format" value="zip" checked="checked" />Zip</label></div>
                    <div class="radio"><label><input type="radio" name="format" value="gz" />Gzip</label></div>
                    <div class="radio"><label><input type="radio" name="format" value="bz2" />Bzip2</label></div>
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