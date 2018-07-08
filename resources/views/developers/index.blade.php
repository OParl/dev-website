@extends ('base')

@section ('subheader')
    @include('developers.partials.subheader')
@stop

@section ('content')
    <h1>OParl Entwicklerportal</h1>

    <section>
        <h2 class="subtitle">@lang('app.developers.about-oparl.title')</h2>
        <div>
            <p>@press(trans('app.developers.about-oparl.text'))</p>
        </div>
    </section>

    <section>
        <h2 class="subtitle">@lang('app.developers.about-dev.title')</h2>
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
