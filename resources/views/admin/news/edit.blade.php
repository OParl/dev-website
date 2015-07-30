@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        @include ('admin.errors')

        <h2>
            @if (ends_with(\Route::currentRouteName(), 'edit'))
                Eintrag “{{ $post->title }}” bearbeiten
            @else
                Neuen Eintrag erstellen
            @endif
        </h2>

        <form action="{{ route('admin.news.save') }}" method="POST">
            {{ csrf_field() }}

            <input type="submit" class="btn btn-primary" value="Speichern!">
        </form>
    </div>
@stop
