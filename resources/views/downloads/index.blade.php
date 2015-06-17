@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <div class="text-center">
                <a href="{{ route('downloads.latest') }}" class="btn btn-lg btn-success">Aktuelle Version herunterladen!</a>
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
                            Die von Ihnen angeforderte Spezifikationsversion liegt zur Zeit nicht aufbereitet vor.
                            Die Erstellung benötigt unter Umständen einen kleinen Moment. Wir bitten Sie daher
                            um Ihre E-Mail-Adresse, um Ihnen den Downloadlink direkt zusenden zu können, sobald
                            der Erstellungsprozess abgeschlossen ist.
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