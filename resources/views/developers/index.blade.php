@extends ('base')

@section ('subheader')
    {{-- TODO: Add wiki base links here (list of implementations, etc.) --}}
@stop

@section ('content')
    <div class="row">
        <section>
            <h2>@lang('app.developers.about-oparl.title')</h2>
            <p class="left">@lang('app.developers.about-oparl.text')</p>
        </section>
        <section class="row-item--shrink-2">
            <h2>@lang('app.developers.about-dev.title')</h2>
            <p class="left">@lang('app.developers.about-dev.text')</p>
        </section>
    </div>
@stop
