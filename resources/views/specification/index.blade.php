@extends ('base')

@section ('content')
    <div class="row" data-spy="scroll" data-target="#nav > div">
        <div class="col-md-3">
            @include ('specification.toc')
        </div>
        <div class="col-md-9">
            {!! $livecopy->getContent() !!}
        </div>
    </div>
@stop