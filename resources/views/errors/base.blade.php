@extends ('base')

@section ('content')
    <div class="center-block">
        <div class="alert alert-danger text-center">
            <p>
                {{ $exception->getMessage() }}
            </p>

            <p>
                Dieser Fehler wurde bereits den verantwortlichen Administratoren übermittelt
                und wird sobald wie möglich beseitigt.
            </p>
        </div>

        <span class="pull-right">
            <a href="{{ \Request::server('HTTP_REFERER') }}">
                <span class="glyphicon glyphicon-chevron-left"></span>
                Zurück zur vorherigen Seite
            </a>
        </span>
        <span class="clearfix"></span>
    </div>
@stop