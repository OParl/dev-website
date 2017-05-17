@extends ('base')

@section ('subheader')
    @include('developers.partials.subheader')
@stop

@section ('content')
    <section class="row">
        <h2 class="col-xs-12 col-md-3">@lang('app.developers.about-oparl.title')</h2>
        <div class="col-xs-12 col-md-9">
            <p>@press(trans('app.developers.about-oparl.text'))</p>
        </div>
    </section>
    <section class="row">
        <h2 class="col-xs-12 col-md-3">@lang('app.developers.about-dev.title')</h2>
        <div class="col-xs-12 col-md-9">
            <p>@press(trans('app.developers.about-dev.text'))</p>
            <p>@lang('app.developers.demo')</p>
            {{--
            <p>@lang('app.developers.liboparl')</p>
            <p>@lang('app.developers.implementors')</p>
            <p>@lang('app.developers.usage-examples')</p>
            --}}
        </div>
    </section>
    {{--
    @include ('developers.partials.validator_form')
    --}}
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/developers'.$app->environment('production') ? '.min' : ''.'.js') }}"></script>
@stop
