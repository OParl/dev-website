@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <div class="text-center">
                <a href="#" class="btn btn-lg btn-success">Aktuelle Version herunterladen!</a>
            </div>
        </div>
        <div class="col-xs-12">
            Versionsinformationen
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
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="version" class="control-label col-sm-4">
                        Gewünschte Version:
                    </label>
                    <div class="col-sm-8">
                        <select class="form-control" name="version" aria-describedby="version-help">
                            <option>Hashes von GitHub</option>
                        </select>
                        <div id="version-help" class="help-block">
                            Falls die von Ihnen gewünschte Version nicht mehr in der obigen Auswahl sein sollte,
                            kontaktieren Sie uns bitte per E-Mail unter
                            <a href="mailto:kontakt@oparl.org">kontakt@oparl.org</a>.
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-4">
                        Ihre E-Mail-Adresse:
                    </label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="email" placeholder="email@example.com" aria-describedby="email-help" />
                        <div id="email-help" class="help-block">
                            Die Angabe ihrer E-Mail-Adresse ist erforderlich, da wir Sie via E-Mail informieren,
                            sobald der Download der von Ihnen angeforderten Spezifikationsversion zur Verfügung
                            steht.
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-10 text-right">
                        <input type="submit" value="Anfordern" class="btn btn-primary" />
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop