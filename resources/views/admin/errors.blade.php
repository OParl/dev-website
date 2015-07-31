@if (session('info'))
    <div class="alert alert-success">
        {{ session('info') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-warning">
        <h3>Es sind Fehler aufgetreten!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif