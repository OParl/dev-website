@extends ('base')

@section ('subheader')
    <h2>@lang('app.contact.title')</h2>
@stop

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <p>
                @lang('app.contact.info')
            </p>

            <ul>
                <li>
                    <a href="https://lists.okfn.org/mailman/listinfo/oparl-tech">@OParl-Tech</a>
                </li>

                <li><a href="{{ route('contact.index') }}">Kontakt</a></li>
            </ul>
        </div>
    </div>
@stop
