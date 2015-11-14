@extends ('about.base')

@section ('about-content')
    <div class="jumbotron">
        <h1>Willkommen!</h1>

        <p>
            OParl ist eine Initiative zur Standardisierung des offenen Zugriffs auf
            parlamentarische Informationssysteme in Deutschland.
        </p>

        <p>
            Das Ziel von OParl ist die Schaffung einer Standard-API für den Zugang zu öffentlichen Inhalten in
            kommunalen <a href="https://de.wikipedia.org/wiki/Ratsinformationssystem">Ratsinformationssystemen</a>,
            damit die Inhalte daraus im Sinne von Open Data für möglichst viele verschiedene Zwecke
            eingesetzt werden können.
        </p>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Für Kommunen</h2>
                </div>
                <div class="panel-body">
                    <p>
                        {{-- TODO: 2 line definition --}}
                    </p>
                </div>

                <div class="panel-footer">
                    <a href="{{ route('about.councils') }}">Weitere Informationen</a>
                </div>
            </div>


        </div>
        <div class="col-xs-12 col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Für Entwickler</h2>
                </div>
                <div class="panel-body">
                    <p>
                        {{-- TODO: 2 line definition --}}
                    </p>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('about.developers') }}">Weitere Informationen</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Für RIS-Hersteller</h2>
                </div>
                <div class="panel-body">
                    <p>
                        {{-- TODO: 2 line definition --}}
                    </p>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('about.ris') }}">Weitere Informationen</a>
                </div>
            </div>
        </div>
    </div>
@stop
