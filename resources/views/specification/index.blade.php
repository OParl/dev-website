@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-3">
            @include ('specification.toc')
        </div>
        <div class="col-xs-12 col-md-9">
            {!! $livecopy !!}

            <div id="endnotes" style="display:none;">
                <h3>Anmerkungen</h3>
            </div>
        </div>
    </div>
@stop