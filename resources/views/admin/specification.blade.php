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
                            <a href="{{ route('admin.specification.update', 'versions') }}" class="btn btn-sm btn-default">Neu laden</a>
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
                <li class="list-group-item">get missing</li>
                <li class="list-group-item">remove all</li>
                <li class="list-group-item">remove extraneous</li>
            </ul>
            <div class="panel-footer">&nbsp;</div>
        </div>
    </div>
@stop