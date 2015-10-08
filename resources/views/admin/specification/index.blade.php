@extends ('admin.base')

@section ('content')
    @include ('admin.errors')

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Livekopie und Versionsliste</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-9">
                            Livekopie
                            <small class="text-muted">({{ $lastModified['livecopy']->format('d.m.Y h:i:s') }})</small>
                        </div>
                        <div class="col-sm-3">
                            <div class="btn-group">
                                <a href="{{ route('admin.specification.update', 'livecopy') }}" class="btn btn-sm btn-default">Pull</a>
                                <a href="{{ route('admin.specification.update', 'livecopy-force') }}" class="btn btn-sm btn-danger">Clone</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-9">
                            Verfügbare Versionsinformationen
                            <small class="text-muted">({{ $lastModified['builds']->format('d.m.Y h:i:s') }})</small>
                        </div>
                        <div class="col-sm-3">
                            <a href="{{ route('admin.specification.update', 'versions') }}" class="btn btn-sm btn-default">Aktualisieren</a>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="panel-footer">&nbsp;</div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Downloads</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-9">Fehlende Downloads bereitstellen</div>
                        <div class="col-sm-3">
                            <a href="{{ route('admin.specification.fetch', '_missing_') }}" class="btn btn-sm btn-default">Bereitstellen</a>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-9">
                            Alle Downloads löschen
                            <br />
                            <span class="text-muted">
                                (Die aktuellste Version und andere als persistent markierte
                                Versionen werden nie automatisch gelöscht.)
                            </span>
                        </div>
                        <div class="col-sm-3">
                            <a href="{{ route('admin.specification.clean', 'all') }}" class="btn btn-sm btn-danger">Löschen</a>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-9">Überschüssige Downloads löschen</div>
                        <div class="col-sm-3">
                            <a href="{{ route('admin.specification.clean', 'extraneous') }}" class="btn btn-sm btn-danger">Löschen</a>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="panel-footer">&nbsp;</div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><a name="buildinformation">Versionsinformationen</a></h3>
            </div>
            @include ('admin.specification.builds-table')
            <div class="panel-footer">
                <div class="text-center">
                    {!! $builds->fragment('buildinformation')->render() !!}
                </div>
            </div>
        </div>
    </div>
@stop