@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        <h2>Einstellungen</h2>

        @include ('admin.errors')

        <form method="POST" action="{{ route('admin.settings.save') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name"
                       placeholder="Name" name="name" value="{{ Auth::user()->name }}" />
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email Adresse</label>
                <input type="email" class="form-control" id="email"
                       placeholder="Email" name="email" value="{{ Auth::user()->email }}" />
            </div>
            <div class="form-group">
                <label for="password">Passwort</label>
                <input type="password" class="form-control" placeholder="Password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Passwort wiederholen</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" />
            </div>

            <button type="submit" class="btn btn-primary">Einstellungen speichern</button>
        </form>
    </div>
@stop
