@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-3">
            @include ('specification.toc')
        </div>
        <div class="col-md-9">
            <h1>OParl Schnittstellenspezifikation Version 1.0 (Entwurf)</h1>

            {!! $livecopy->getContent() !!}

            @include ('specification.license')
        </div>
    </div>
@stop