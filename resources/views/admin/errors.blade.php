@if (session('info'))
    <div class="alert alert-success">
        {{ session('info') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-warning">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif