@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-md-3">
            @include ('specification.toc')
        </div>
        <div class="col-md-9 spec-running-text">
            <div class="col-md-11">
                {!! $liveversion->getContent() !!}

                @include ('specification.license')
            </div>
        </div>
    </div>

    @include ('downloads.button_modal')
@stop