@extends ('base')

@section ('subheader')
    <li><a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a></li>
    {{-- TODO: Add wiki base links here (list of implementations, etc.) --}}
@stop

@section ('content')
    <section class="row">
        <h2 class="row-item--shrink-1">@lang('app.developers.about-oparl.title')</h2>
        <div class="row-item">
            <p>@press(trans('app.developers.about-oparl.text'))</p>
        </div>
    </section>
    <section class="row">
        <h2 class="row-item--shrink-1">@lang('app.developers.about-dev.title')</h2>
        <div class="row-item">
            <p>@press(trans('app.developers.about-dev.text'))</p>
            <p>@lang('app.developers.demo')</p>
            <p>@lang('app.developers.liboparl')</p>
            <p>@lang('app.developers.implementors')</p>
            <p>@lang('app.developers.usage-examples')</p>
        </div>
    </section>
    <section class="row">
        <h2 class="row-item--shrink-1">@lang('app.developers.validator.title')</h2>
        <div class="row-item">
            <p>@lang('app.developers.validator.info-text')</p>
        </div>
    </section>
@stop
