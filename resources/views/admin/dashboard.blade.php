@extends ('admin.base')

@section ('content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Nachrichten</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">Test</li>
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
                <li class="list-group-item">Test</li>
            </ul>
            <div class="panel-footer">
                <a href="{{ route('admin.comments.index') }}">
                    Zur Kommentaradministration
                    <span class="glyphicon glyphicon-arrow-right"></span>
                </a>
            </div>
        </div>
    </div>
@stop
