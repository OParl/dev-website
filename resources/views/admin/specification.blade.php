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
                            Versionsliste
                            <small class="text-muted">({{ $lastModified['versions']->format('d.m.Y h:i:s') }})</small>
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
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th>Hash</th>
                    <th>Erstellt am</th>
                    <th>Beschreibung</th>
                    <th>Verfügbarkeit</th>
                    <th>Optionen</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($builds as $build)
                    <tr>
                        <td>
                            <a href="//github.com/OParl/spec/commits/{{ $build->hash }}">
                                {{ $build->short_hash }}
                            </a>
                        </td>
                        <td>{{ $build->created_at->formatLocalized('%d.%m.%Y') }}</td>
                        <td>{!! $build->linked_commit_message  !!}</td>
                        <td class="text-center">
                            @if ($build->isAvailable)
                                <span class="glyphicon glyphicon-ok text-success"></span>
                            @else
                                <span class="glyphicon glyphicon-remove text-danger"></span>
                            @endif
                        </td>
                        <td>
                            @if ($build->is_available)
                                <a href="{{ route('admin.specification.delete', $build->hash) }}" class="btn btn-sm btn-danger">Löschen</a>
                            @else
                                <a href="{{ route('admin.specification.fetch', $build->hash) }}" class="btn btn-sm btn-default">Laden</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Momentan sind keine weiteren Build-Informationen verfügbar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@stop