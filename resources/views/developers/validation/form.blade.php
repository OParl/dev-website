@extends ('base')

@section ('subheader')
    @include('developers.partials.subheader')
@stop

@section ('content')
        @if (Session::has('message'))
            <div class="success">
                {{ Session::get('message') }}
            </div>
        @endif

        <form
                action="{{ route('validator.schedule') }}"
                method="post"
                class="col-xs-12 pure-form"
        >
            <div class="row">
                {{ csrf_field() }}

                <div class="col-xs-12 col-sm-2">
                    <label for="endpoint">
                        {{ trans('app.validation.form.endpoint') }}
                    </label>
                </div>
                <div class="col-xs-12 col-sm-10">
                    <input type="url" name="endpoint" id="endpoint" style="width: 100%;">
                </div>

                <div class="pure-form-message col-xs-12">
                    {{ trans('app.validation.form.endpoint.description') }}
                </div>

                @if ($errors->has('endpoint'))
                    @foreach ($errors->get('endpoint') as $message )
                        <div class="pure-form-message warning col-xs-12">
                            {{ $message }}
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-2">
                    <label for="email">
                        {{ trans('app.validation.form.email') }}
                    </label>
                </div>
                <div class="col-xs-12 col-sm-10">
                    <input type="email" name="email" id="email" style="width: 100%;">
                </div>

                <div class="pure-form-message col-xs-12">
                    {{ trans('app.validation.form.email.description') }}
                </div>

                @if ($errors->has('email'))
                    @foreach ($errors->get('email') as $message )
                        <div class="pure-form-message warning  col-xs-12">
                            {{ $message }}
                        </div>
                    @endforeach
                @endif

                <label for="save">
                    {{ trans('app.validation.form.save') }}
                    <input type="checkbox" name="save">
                </label>
            </div>

            <button type="submit" class="pure-button pure-button-primary">Submit</button>
        </form>
@stop