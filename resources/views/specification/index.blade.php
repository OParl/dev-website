@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-3">
            @include ('specification.toc')
            <footer class="text-muted text-right">
                <small>
                    Zuletzt aktualisiert {{ $livecopy->getLastModified()->diffForHumans() }}
                </small>
            </footer>
        </div>
        <div class="col-md-9">
            <div>
                {!! $livecopy->getContent() !!}

                @include ('specification.license')
            </div>
        </div>
    </div>
@stop