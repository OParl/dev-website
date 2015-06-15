@extends ('app')

@section ('content')
    <div class="editable">
  {{ app('github')->api('repo')->contents()->download('OParl', 'specs', 'dokument/master/gliederung.txt') }}
    </div>
@stop