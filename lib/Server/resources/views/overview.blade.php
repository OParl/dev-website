@extends ('base')

@section ('content')
    <ul>
        @foreach ($routes as $route)
            <li>{{ $route->getName() }}</li>
        @endforeach
    </ul>
@stop
