@extends ('base')

@section ('content')
    <open-api></open-api>
@stop

@section ('bundle')
    <script src="{{ asset('js/api.js') }}"></script>
@overwrite
