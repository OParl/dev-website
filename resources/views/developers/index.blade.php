@extends ('base')

@section ('subheader')
    @include('developers.partials.subheader')
@stop

@section ('content')
    <section class="section">
        <h2>@lang('app.developers.about-oparl.title')</h2>
        <div>
            <p>@press(trans('app.developers.about-oparl.text'))</p>
        </div>
    </section>
    <section class="section">
        <h2>@lang('app.developers.about-dev.title')</h2>
        <div>
            <p>@press(trans('app.developers.about-dev.text'))</p>
            <p>@lang('app.developers.demo')</p>
            {{--
            <p>@lang('app.developers.liboparl')</p>
            <p>@lang('app.developers.implementors')</p>
            <p>@lang('app.developers.usage-examples')</p>
            --}}
        </div>
    </section>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/developers.js') }}"></script>
@stop
