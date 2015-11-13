@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-3">
            @include ('specification.toc')
        </div>
        <div class="col-md-9">
            <div>
                {!! $liveversion->getContent() !!}

                @include ('specification.license')
            </div>
        </div>
    </div>
@stop