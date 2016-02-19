@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <div class="text-center">
                @include ('downloads.button', ['buttonSize' => 'lg'])
            </div>
        </div>
        <div class="col-xs-12">
            <br />
            <ul class="list-unstyled text-center">
                <li><em>{{ $builds->first()->commit_message  }}</em></li>
                <li><small>(Erstellt {{ $builds->first()->created_at->diffForHumans() }}.)</small></li>
            </ul>
        </div>
    </div>
@stop