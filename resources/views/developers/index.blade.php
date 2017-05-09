@extends ('base')

@section ('subheader')
    <li class="col-xs-12"><a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a></li>
    {{-- TODO: Add wiki base links here (list of implementations, etc.) --}}
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
    <section class="row">
        <h2 class="col-xs-12 col-md-3">@lang('app.developers.validator.title')</h2>
        <div class="col-xs-12 col-md-9">
            <p>@lang('app.developers.validator.info-text')</p>

            @if (session('message'))
                <ul class="messages col-xs-12">
                    <li class="success">{{ session('message') }}</li>
                </ul>
            @endif

            @if ($errors->count() > 0)
                <ul class="messages col-xs-12">
                    @foreach ($errors->all() as $type => $message)
                        <li class="error">{{ $message }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('validator.validate') }}" method="post" class="row pure-form pure-form-stacked">
                {{ csrf_field() }}

                <label for="endpoint" class="col-sm-4 col-xs-12">@lang('app.validation.form.endpoint')</label>
                <input type="url" name="endpoint" id="endpoint" class="col-sm-8 col-xs-12" value="{{ old('endpoint') }}"
                       required>

                <label for="email" class="col-sm-4 col-xs-12">@lang('app.validation.form.email')</label>
                <input type="email" name="email" id="email" class="col-sm-8 col-xs-12" value="{{ old('email') }}"
                       required>

                <label class="pure-checkbox col-sm-offset-1 col-sm-offset-11 col-xs-12">
                    <input type="checkbox" name="save" class="form-control">
                    @lang('app.validation.form.save')
                </label>

                <button type="submit"
                        class="col-xs-4 col-xs-offset-8 pure-button pure-button-primary">
                    @lang('app.validation.start')
                </button>
            </form>

            <br>
        </div>
    </section>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/developers.js') }}"></script>
@stop
