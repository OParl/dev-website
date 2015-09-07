@extends ('admin.base')

@section ('content')
    @include ('admin.errors')

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Nachrichten</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">Einträge insgesamt: {{ $post['all'] }}</li>
                <li class="list-group-item">- davon veröffentlicht: {{ $post['published'] }}</li>
                <li class="list-group-item">- Entwürfe: {{ $post['drafts'] }}</li>
                <li class="list-group-item">- geplant zur Veröffentlichung: {{ $post['scheduled'] }}</li>
            </ul>
            <div class="panel-footer">
                <a href="{{ route('admin.news.index') }}">
                    Zur Nachrichtenadministration
                    <span class="glyphicon glyphicon-arrow-right"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Kommentare</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">Kommentare insgesamt: {{ $comment['all'] }}</li>
                <li class="list-group-item">- davon bestätigt: {{ $comment['ham'] }}</li>
                <li class="list-group-item">- Spam: {{ $comment['spam'] }}</li>
                <li class="list-group-item">- nicht überprüft: {{ $comment['unvalidated'] }}</li>
            </ul>
            <div class="panel-footer">
                <a href="{{ route('admin.comments.index') }}">
                    Zur Kommentaradministration
                    <span class="glyphicon glyphicon-arrow-right"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Spezifikation</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-9">Livekopie aktualisieren</div>
                        <div class="col-sm-3">
                            <div class="btn-group">
                                <a href="{{ route('admin.dashboard.update', 'livecopy') }}" class="btn btn-sm btn-default">Pull</a>
                                <a href="{{ route('admin.dashboard.update', 'livecopy-force') }}" class="btn btn-sm btn-danger">Clone</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-9">Versionsliste</div>
                        <div class="col-sm-3">
                            <a href="{{ route('admin.dashboard.update', 'versions') }}" class="btn btn-sm btn-default">Aktualisieren</a>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="panel-footer">&nbsp;</div>
        </div>
    </div>
@stop
